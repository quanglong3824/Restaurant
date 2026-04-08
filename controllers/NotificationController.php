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
        // Fix: Auth::requireRole uses variadic args
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN, ROLE_IT);
        $this->notifModel = new OrderNotification();
        $this->supportModel = new Support();
    }

    /** GET /notifications — Trang trung tâm điều hành */
    public function waiterIndex(): void
    {
        $this->view('layouts/waiter', [
            'view' => 'notifications/waiter',
            'pageTitle' => 'Trung tâm điều hành',
            'pageCSS' => 'notifications/waiter',
            'pageJS' => 'notifications/waiter',
        ]);
    }

    /** API: Data endpoint for waiter notifications page */
    public function data(): void
    {
        try {
            $page = max(1, (int)($this->input('page', 1)));
            $limit = 15;
            $filter = 'all';
            $status = '';

            $notifications = $this->notifModel->getPaged($page, $limit, $filter, $status);
            $totalCount = $this->notifModel->countAll($filter, $status);
            $unreadCount = $this->notifModel->countUnread();
            
            $this->json([
                'ok' => true,
                'notifications' => $notifications,
                'pagination' => [
                    'page' => $page,
                    'totalPages' => max(1, ceil($totalCount / $limit))
                ],
                'unreadCount' => $unreadCount
            ]);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /** API: Poll for notifications (legacy) */
    public function poll(): void
    {
        try {
            $page = max(1, (int)($this->input('page', 1)));
            $limit = max(1, (int)($this->input('limit', 15)));
            $filter = $this->input('filter', 'all');
            $status = $this->input('status', '');

            $notifications = $this->notifModel->getPaged($page, $limit, $filter, $status);
            $totalCount = $this->notifModel->countAll($filter, $status);
            
            $stats = [
                'unread'     => $this->notifModel->countUnread(),
                'payment'    => $this->notifModel->countUnreadByType('payment_request'),
                'order'      => $this->notifModel->countUnreadByType('new_order'),
                'order_item' => $this->notifModel->countUnreadByType('order_item'),
                'support'    => $this->notifModel->countUnreadByType('support_request'),
                'scan'       => $this->notifModel->countUnreadByType('scan_qr'),
            ];

            $this->json([
                'ok' => true,
                'notifications' => $notifications,
                'total_count' => $totalCount,
                'page' => $page,
                'limit' => $limit,
                'stats' => $stats,
                'server_time' => date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /** POST /notifications/waiter/mark-read — Mark notification as read */
    public function markRead(): void
    {
        $id = (int)($this->input('id', 0));
        $userId = Auth::user()['id'];

        if ($id > 0) {
            $this->notifModel->markAsRead($id, $userId);
        }

        $this->json(['ok' => true]);
    }

    /** POST /notifications/waiter/dismiss — Dismiss notification */
    public function dismiss(): void
    {
        $id = (int)($this->input('id', 0));
        if ($id > 0) {
            $this->notifModel->dismiss($id);
        }
        $this->json(['ok' => true]);
    }

    /** API: Xử lý nhanh yêu cầu hỗ trợ từ thông báo */
    public function resolveSupport(): void
    {
        $tableId = (int)($this->input('table_id', 0));
        $type = $this->input('type', '');

        if ($tableId > 0) {
            // Đánh dấu hoàn thành trong bảng support_requests nếu có
            $this->supportModel->resolveByTableAndType($tableId, $type);
            $this->json(['ok' => true]);
        } else {
            $this->json(['ok' => false, 'error' => 'Thiếu thông tin bàn'], 400);
        }
    }
}
