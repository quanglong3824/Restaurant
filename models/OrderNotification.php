<?php
// ============================================================
// OrderNotification Model — Aurora Restaurant
// ============================================================

class OrderNotification extends Model
{
    protected string $table = 'order_notifications';

    /** Tạo thông báo mới */
    public function create(array $data): int
    {
        $orderId = empty($data['order_id']) ? null : $data['order_id'];

        $this->execute(
            "INSERT INTO order_notifications 
             (order_id, table_id, notification_type, title, message) 
             VALUES (?, ?, ?, ?, ?)",
            [
                $orderId,
                $data['table_id'],
                $data['notification_type'],
                $data['title'],
                $data['message']
            ]
        );
        return (int) $this->lastInsertId();
    }

    /** Lấy danh sách thông báo mới nhất kèm chi tiết bàn */
    public function getRecent(int $limit = 50): array
    {
        return $this->findAll(
            "SELECT n.*, t.name as table_name, t.area as table_area,
                    o.status as order_status
             FROM order_notifications n
             JOIN tables t ON n.table_id = t.id
             LEFT JOIN orders o ON n.order_id = o.id
             ORDER BY n.is_read ASC, n.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }

    /** Đánh dấu đã đọc */
    public function markAsRead(int $id, int $userId): void
    {
        $this->execute(
            "UPDATE order_notifications 
             SET is_read = 1, read_at = NOW(), read_by = ? 
             WHERE id = ?",
            [$userId, $id]
        );
    }

    /** Đánh dấu tất cả đã đọc cho một nhân viên */
    public function markAllAsRead(int $userId): void
    {
        $this->execute(
            "UPDATE order_notifications 
             SET is_read = 1, read_at = NOW(), read_by = ? 
             WHERE is_read = 0",
            [$userId]
        );
    }

    /** Xóa thông báo cũ (giữ lại 200 cái gần nhất) */
    public function cleanup(): void
    {
        $this->execute(
            "DELETE FROM order_notifications 
             WHERE id NOT IN (
                SELECT id FROM (
                    SELECT id FROM order_notifications 
                    ORDER BY created_at DESC LIMIT 200
                ) tmp
             )"
        );
    }
}
