<?php
// ============================================================
// OrderNotification Model — Aurora Restaurant
// ============================================================

class OrderNotification extends Model
{
    protected string $table = 'order_notifications';

    public function create(array $data): int
    {
        $this->execute(
            "INSERT INTO order_notifications 
             (order_id, table_id, notification_type, title, message) 
             VALUES (?, ?, ?, ?, ?)",
            [
                $data['order_id'],
                $data['table_id'],
                $data['notification_type'],
                $data['title'],
                $data['message']
            ]
        );
        return (int) $this->lastInsertId();
    }

    public function getUnread(): array
    {
        return $this->findAll(
            "SELECT n.*, t.name as table_name, t.area as table_area 
             FROM order_notifications n
             JOIN tables t ON n.table_id = t.id
             WHERE n.is_read = 0
             ORDER BY n.created_at DESC"
        );
    }

    public function markAsRead(int $id, int $userId): void
    {
        $this->execute(
            "UPDATE order_notifications 
             SET is_read = 1, read_at = NOW(), read_by = ? 
             WHERE id = ?",
            [$userId, $id]
        );
    }

    public function markAllAsRead(int $userId): void
    {
        $this->execute(
            "UPDATE order_notifications 
             SET is_read = 1, read_at = NOW(), read_by = ? 
             WHERE is_read = 0",
            [$userId]
        );
    }
}
