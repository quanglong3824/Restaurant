<?php
// ============================================================
// Support Model — Aurora Restaurant
// ============================================================

class Support extends Model
{
    public function createRequest(int $tableId, string $type): int
    {
        // Chống spam: Nếu cùng một bàn gửi yêu cầu cùng loại trong 5 phút qua
        $existing = $this->findOne(
            "SELECT id FROM support_requests 
             WHERE table_id = ? AND type = ? AND status = 'pending' 
             AND created_at >= NOW() - INTERVAL 5 MINUTE",
            [$tableId, $type]
        );

        if ($existing) {
            return (int)$existing['id'];
        }

        $this->execute(
            "INSERT INTO support_requests (table_id, type, status) VALUES (?, ?, 'pending')",
            [$tableId, $type]
        );
        return (int) $this->lastInsertId();
    }

    public function getPendingRequests(): array
    {
        // Lấy từ support_requests
        $requests = $this->findAll(
            "SELECT CONCAT('sr_', sr.id) as id, sr.table_id, sr.type, sr.status, sr.created_at, t.name as table_name, t.area 
             FROM support_requests sr
             JOIN tables t ON t.id = sr.table_id
             WHERE sr.status = 'pending'
             ORDER BY sr.created_at ASC"
        );

        // Lấy từ order_notifications
        $notifications = $this->findAll(
            "SELECT CONCAT('on_', n.id) as id, n.table_id, n.notification_type as type, 'pending' as status, n.created_at, t.name as table_name, t.area 
             FROM order_notifications n
             JOIN tables t ON n.table_id = t.id
             WHERE n.is_read = 0
             ORDER BY n.created_at ASC"
        );

        return array_merge($requests, $notifications);
    }

    public function resolveRequest(string $prefixedId): void
    {
        if (strpos($prefixedId, 'sr_') === 0) {
            $id = (int)str_replace('sr_', '', $prefixedId);
            $this->execute("UPDATE support_requests SET status = 'completed' WHERE id = ?", [$id]);
        } elseif (strpos($prefixedId, 'on_') === 0) {
            $id = (int)str_replace('on_', '', $prefixedId);
            $this->execute("UPDATE order_notifications SET is_read = 1, read_at = NOW() WHERE id = ?", [$id]);
        } else {
            // Fallback for old numeric IDs
            $id = (int)$prefixedId;
            $this->execute("UPDATE support_requests SET status = 'completed' WHERE id = ?", [$id]);
            $this->execute("UPDATE order_notifications SET is_read = 1, read_at = NOW() WHERE id = ?", [$id]);
        }
    }
}
