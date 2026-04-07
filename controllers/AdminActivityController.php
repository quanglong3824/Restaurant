<?php
// ============================================================
// AdminActivityController — Aurora Restaurant
// Quản lý nhật ký hoạt động hệ thống
// ============================================================

require_once BASE_PATH . '/models/ActivityLog.php';

class AdminActivityController extends Controller
{
    private ActivityLog $activityLog;

    public function __construct()
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        $this->activityLog = new ActivityLog();
    }

    /**
     * Hiển thị trang nhật ký hoạt động
     */
    public function index(): void
    {
        // Kiểm tra bảng activity_logs có tồn tại không
        try {
            $db = getDB();
            $stmt = $db->query("SHOW TABLES LIKE 'activity_logs'");
            if ($stmt->rowCount() === 0) {
                $_SESSION['flash'] = [
                    'type' => 'warning',
                    'message' => 'Bảng activity_logs chưa tồn tại. Vui lòng chạy migration trong database/migration_activity_logs.sql trước.',
                ];
                $this->redirect('/admin/realtime');
                return;
            }
        } catch (\Throwable $e) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Lỗi kết nối database: ' . $e->getMessage(),
            ];
            $this->redirect('/admin/realtime');
            return;
        }

        $filters = [
            'action' => $this->input('action', ''),
            'entity' => $this->input('entity', ''),
            'entity_id' => $this->input('entity_id', ''),
            'user_id' => $this->input('user_id', ''),
            'level' => $this->input('level', ''),
            'ip_address' => $this->input('ip_address', ''),
            'from' => $this->input('from', date('Y-m-d', strtotime('-7 days'))),
            'to' => $this->input('to', date('Y-m-d')),
        ];

        $page = max(1, (int) $this->input('page', 1));
        $limit = min(100, max(10, (int) $this->input('limit', 50)));

        $logs = $this->activityLog->getLogs($filters, $page, $limit);
        $total = $this->activityLog->countLogs($filters);
        $totalPages = ceil($total / $limit);

        // Lấy danh sách users để filter
        $userModel = new User();
        $users = $userModel->getAll();

        // Lấy danh sách actions đã có
        $actions = $this->activityLog->getActionTypes();

        // Thống kê nhanh
        $stats = $this->getQuickStats();

        $this->view('admin/activity/index', [
            'pageTitle' => 'Nhật Ký Hoạt Động',
            'pageSubtitle' => 'Theo dõi toàn bộ hoạt động trong hệ thống',
            'logs' => $logs,
            'filters' => $filters,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' => $totalPages,
            ],
            'users' => $users,
            'actions' => $actions,
            'stats' => $stats,
        ]);
    }

    /**
     * API: Lấy danh sách logs (JSON)
     */
    public function data(): void
    {
        $filters = [
            'action' => $this->input('action', ''),
            'entity' => $this->input('entity', ''),
            'entity_id' => $this->input('entity_id', ''),
            'user_id' => $this->input('user_id', ''),
            'level' => $this->input('level', ''),
            'from' => $this->input('from', date('Y-m-d', strtotime('-7 days'))),
            'to' => $this->input('to', date('Y-m-d')),
        ];

        $page = max(1, (int) $this->input('page', 1));
        $limit = min(100, max(10, (int) $this->input('limit', 50)));

        $logs = $this->activityLog->getLogs($filters, $page, $limit);
        $total = $this->activityLog->countLogs($filters);

        // Format logs for JSON response
        foreach ($logs as &$log) {
            $log['metadata'] = json_decode($log['metadata'], true) ?? [];
            $log['created_at_formatted'] = date('d/m/Y H:i:s', strtotime($log['created_at']));
            $log['level_class'] = $this->getLevelClass($log['level']);
            $log['action_label'] = $this->getActionLabel($log['action']);
        }

        $this->json([
            'ok' => true,
            'data' => $logs,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' => ceil($total / $limit),
            ],
        ]);
    }

    /**
     * API: Lấy hoạt động của một entity cụ thể
     */
    public function entityLogs(): void
    {
        $entity = $this->input('entity', '');
        $entityId = (int) $this->input('entity_id', 0);
        $limit = min(50, max(5, (int) $this->input('limit', 20)));

        if (!$entity || !$entityId) {
            $this->json(['ok' => false, 'message' => 'Thiếu thông tin entity'], 400);
            return;
        }

        $logs = $this->activityLog->getRecentByEntity($entity, $entityId, $limit);
        
        foreach ($logs as &$log) {
            $log['metadata'] = json_decode($log['metadata'], true) ?? [];
            $log['created_at_formatted'] = date('d/m/Y H:i:s', strtotime($log['created_at']));
        }

        $this->json([
            'ok' => true,
            'data' => $logs,
        ]);
    }

    /**
     * API: Xóa logs cũ
     */
    public function cleanup(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        if (!$this->isPost()) {
            $this->json(['ok' => false, 'message' => 'Method not allowed'], 405);
            return;
        }

        $days = (int) $this->input('days', 90);
        if ($days < 1 || $days > 365) {
            $this->json(['ok' => false, 'message' => 'Số ngày không hợp lệ (1-365)'], 400);
            return;
        }

        $deleted = $this->activityLog->cleanupOldLogs($days);

        // Log hoạt động cleanup
        $this->activityLog->log(
            ActivityLog::ACTION_CLEANUP,
            'activity_logs',
            null,
            ['days' => $days, 'deleted_count' => $deleted],
            ActivityLog::LEVEL_NOTICE
        );

        $this->json([
            'ok' => true,
            'message' => "Đã xóa {$deleted} bản ghi cũ hơn {$days} ngày",
            'deleted' => $deleted,
        ]);
    }

    /**
     * API: Export logs ra CSV
     */
    public function export(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $filters = [
            'action' => $this->input('action', ''),
            'entity' => $this->input('entity', ''),
            'from' => $this->input('from', date('Y-m-d', strtotime('-30 days'))),
            'to' => $this->input('to', date('Y-m-d')),
        ];

        // Lấy tất cả logs (không phân trang)
        $logs = $this->activityLog->getLogs($filters, 1, 10000);

        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="activity_logs_' . date('Y-m-d_H-i-s') . '.csv"');

        $output = fopen('php://output', 'w');

        // Header row
        fputcsv($output, [
            'ID',
            'Thời gian',
            'Hành động',
            'Thực thể',
            'ID Thực thể',
            'Tên Thực thể',
            'Người thực hiện',
            'Role',
            'IP Address',
            'Mức độ',
            'Metadata',
        ]);

        // Data rows
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                date('d/m/Y H:i:s', strtotime($log['created_at'])),
                $this->getActionLabel($log['action']),
                $log['entity'],
                $log['entity_id'] ?? '',
                $log['entity_label'] ?? '',
                $log['user_name'] ?? 'System',
                $log['user_role'] ?? '',
                $log['ip_address'],
                $log['level'],
                $log['metadata'],
            ]);
        }

        fclose($output);

        // Log export action
        $this->activityLog->log(
            ActivityLog::ACTION_EXPORT,
            'activity_logs',
            null,
            ['filters' => $filters, 'count' => count($logs)],
            ActivityLog::LEVEL_INFO
        );

        exit;
    }

    /**
     * Lấy thống kê nhanh
     */
    private function getQuickStats(): array
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $weekAgo = date('Y-m-d', strtotime('-7 days'));

        return [
            'today' => [
                'total' => $this->activityLog->countLogs(['from' => $today, 'to' => $today]),
                'errors' => $this->activityLog->countLogs(['from' => $today, 'to' => $today, 'level' => 'error']),
                'warnings' => $this->activityLog->countLogs(['from' => $today, 'to' => $today, 'level' => 'warning']),
            ],
            'yesterday' => [
                'total' => $this->activityLog->countLogs(['from' => $yesterday, 'to' => $yesterday]),
            ],
            'week' => [
                'total' => $this->activityLog->countLogs(['from' => $weekAgo, 'to' => $today]),
                'unique_users' => $this->getUniqueUsersCount($weekAgo, $today),
            ],
        ];
    }

    /**
     * Đếm số user duy nhất trong khoảng thời gian
     */
    private function getUniqueUsersCount(string $from, string $to): int
    {
        $result = $this->activityLog->findOne(
            "SELECT COUNT(DISTINCT user_id) as count FROM activity_logs 
             WHERE DATE(created_at) BETWEEN ? AND ? AND user_id IS NOT NULL",
            [$from, $to]
        );
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get CSS class for level
     */
    private function getLevelClass(string $level): string
    {
        return match ($level) {
            'error' => 'text-danger',
            'warning' => 'text-warning',
            'notice' => 'text-info',
            'critical' => 'text-danger font-weight-bold',
            default => 'text-muted',
        };
    }

    /**
     * Get label for action
     */
    private function getActionLabel(string $action): string
    {
        $labels = [
            'login' => 'Đăng nhập',
            'logout' => 'Đăng xuất',
            'create' => 'Tạo mới',
            'update' => 'Cập nhật',
            'delete' => 'Xóa',
            'view' => 'Xem',
            'export' => 'Xuất dữ liệu',
            'import' => 'Nhập dữ liệu',
            'confirm' => 'Xác nhận',
            'cancel' => 'Hủy',
            'transfer' => 'Chuyển',
            'merge' => 'Ghép',
            'split' => 'Tách',
            'payment' => 'Thanh toán',
            'call_waiter' => 'Gọi phục vụ',
            'request_bill' => 'Yêu cầu thanh toán',
            'scan_qr' => 'Quét QR',
            'location_check' => 'Kiểm tra vị trí',
            'session_create' => 'Tạo session',
            'session_clear' => 'Xóa session',
            'upload' => 'Tải lên',
            'download' => 'Tải xuống',
            'backup' => 'Sao lưu',
            'cleanup' => 'Dọn dẹp',
            'error' => 'Lỗi',
            'warning' => 'Cảnh báo',
            'system' => 'Hệ thống',
        ];
        return $labels[$action] ?? ucfirst($action);
    }
}