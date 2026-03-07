<?php
// ============================================================
// Support Model — Aurora Restaurant
// ============================================================

class Support extends Model
{
    public function createRequest(int $tableId, string $type): int
    {
        // Kiểm tra xem bàn này đã có yêu cầu loại này đang pending chưa để tránh spam
        $existing = $this->findOne(
            "SELECT id FROM support_requests WHERE table_id = ? AND type = ? AND status = 'pending'",
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
        return $this->findAll(
            "SELECT sr.*, t.name as table_name, t.area 
             FROM support_requests sr
             JOIN tables t ON t.id = sr.table_id
             WHERE sr.status = 'pending'
             ORDER BY sr.created_at ASC"
        );
    }

    public function resolveRequest(int $id): void
    {
        $this->execute(
            "UPDATE support_requests SET status = 'completed' WHERE id = ?",
            [$id]
        );
    }
}
