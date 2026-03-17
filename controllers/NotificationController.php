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

    /** Poll for notifications (recent history) */
    public function poll(): void
    {
        $recent = $this->notifModel->getRecent(20);
        $unreadCount = count(array_filter($recent, fn($n) => !$n['is_read']));
        
        $this->json([
            'count' => $unreadCount,
            'notifications' => $recent
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
