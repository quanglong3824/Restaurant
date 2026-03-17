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

    /** GET /notifications */
    public function waiterIndex(): void
    {
        try {
            // Check if model works before loading view
            $this->notifModel->getRecent(1);
            
            $this->view('layouts/waiter', [
                'view' => 'notifications/waiter',
                'pageTitle' => 'Thông báo',
            ]);
        } catch (\Throwable $e) {
            echo "<h1>Lỗi trang thông báo (500)</h1>";
            echo "<p>Lỗi SQL/Hệ thống: " . e($e->getMessage()) . "</p>";
            echo "<p>File: " . e($e->getFile()) . " trên dòng " . e($e->getLine()) . "</p>";
            echo "<hr><pre>" . e($e->getTraceAsString()) . "</pre>";
            exit;
        }
    }

    /** Poll for notifications (recent history) */
    public function poll(): void
    {
        try {
            $recent = $this->notifModel->getRecent(20);
            $unreadCount = count(array_filter($recent, fn($n) => !$n['is_read']));
            
            $this->json([
                'count' => $unreadCount,
                'notifications' => $recent
            ]);
        } catch (\Throwable $e) {
            $this->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
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
