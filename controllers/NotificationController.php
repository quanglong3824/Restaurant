<?php
// ============================================================
// NotificationController — Aurora Restaurant
// ============================================================

require_once BASE_PATH . '/models/OrderNotification.php';
require_once BASE_PATH . '/models/Support.php';

class NotificationController extends Controller
{
    private OrderNotification $notifModel;
    private Support $supportModel;

    public function __construct()
    {
        Auth::requireRole(['waiter', 'admin', 'it']);
        $this->notifModel = new OrderNotification();
        $this->supportModel = new Support();
    }

    /** GET /notifications — Trang trung tâm thông báo */
    public function waiterIndex(): void
    {
        try {
            $this->view('layouts/waiter', [
                'view' => 'notifications/waiter',
                'pageTitle' => 'Trung tâm điều hành',
            ]);
        } catch (\Throwable $e) {
            echo "<h1>Lỗi hệ thống (500)</h1>";
            echo "<p>" . e($e->getMessage()) . "</p>";
            exit;
        }
    }

    /** API: Poll for notifications */
    public function poll(): void
    {
        try {
            $recent = $this->notifModel->getRecent(30) ?: [];
            
            // Lấy thêm các support requests chưa xử lý để gộp vào (nếu cần)
            $pendingSupports = $this->supportModel->getPending();
            
            $unreadCount = 0;
            foreach ($recent as $n) {
                if (!$n['is_read']) $unreadCount++;
            }
            
            $this->json([
                'count' => $unreadCount,
                'notifications' => $recent,
                'pending_supports' => $pendingSupports,
                'server_time' => date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /** API: Đánh dấu đã đọc/xử lý */
    public function markRead(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $userId = Auth::user()['id'];

        if ($id) {
            $this->notifModel->markAsRead($id, $userId);
        } else {
            $this->notifModel->markAllAsRead($userId);
        }

        $this->json(['success' => true]);
    }

    /** API: Xử lý nhanh yêu cầu hỗ trợ từ thông báo */
    public function resolveSupport(): void
    {
        $tableId = (int)($_POST['table_id'] ?? 0);
        $type = $_POST['type'] ?? '';

        if ($tableId) {
            $this->supportModel->resolveRequest($tableId, $type);
            $this->json(['success' => true]);
        } else {
            $this->json(['error' => 'Thiếu thông tin bàn'], 400);
        }
    }
}
