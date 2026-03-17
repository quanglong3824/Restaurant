<?php
// ============================================================
// NotificationController — Aurora Restaurant
// ============================================================

require_once BASE_PATH . '/models/OrderNotification.php';

class NotificationController extends Controller
{
    private OrderNotification $notifModel;

    public function __construct()
    {
        // Require any staff role
        Auth::requireRole(['waiter', 'admin', 'it']);
        $this->notifModel = new OrderNotification();
    }

    /** Poll for new unread notifications */
    public function poll(): void
    {
        $notifications = $this->notifModel->getUnread();
        $this->json([
            'count' => count($notifications),
            'notifications' => $notifications
        ]);
    }

    /** Mark a notification as read */
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
}
