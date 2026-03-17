<?php
// ============================================================
// QrTable Model — Aurora Restaurant
// ============================================================

class QrTable extends Model
{
    protected string $table = 'qr_tables';

    public function findByTableId(int $tableId): ?array
    {
        return $this->findOne("SELECT * FROM qr_tables WHERE table_id = ? AND is_active = 1", [$tableId]);
    }

    public function findByToken(string $token): ?array
    {
        return $this->findOne("SELECT * FROM qr_tables WHERE qr_token = ? AND is_active = 1", [$token]);
    }

    public function incrementScanCount(int $id): void
    {
        $this->execute(
            "UPDATE qr_tables SET scan_count = scan_count + 1, last_scanned_at = NOW() WHERE id = ?",
            [$id]
        );
    }

    public function getAllWithTableInfo(): array
    {
        return $this->findAll(
            "SELECT qr.*, t.name as table_name, t.area as table_area 
             FROM qr_tables qr
             JOIN tables t ON qr.table_id = t.id
             ORDER BY t.area, t.sort_order, t.name"
        );
    }

    public function generate(int $tableId, string $token): int
    {
        $url = "/qr/menu?table_id=$tableId&token=$token";
        $this->execute(
            "INSERT INTO qr_tables (table_id, qr_token, qr_url, is_active) 
             VALUES (?, ?, ?, 1)
             ON DUPLICATE KEY UPDATE qr_token = VALUES(qr_token), qr_url = VALUES(qr_url), updated_at = NOW()",
            [$tableId, $token, $url]
        );
        return (int) $this->lastInsertId();
    }
}
