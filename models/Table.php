<?php
// ============================================================
// Table Model — Aurora Restaurant
// ============================================================

class Table extends Model
{
    protected string $table = 'tables';

    /** Tất cả bàn đang active, sắp xếp theo khu vực + thứ tự */
    public function getAll(): array
    {
        return $this->findAll(
            "SELECT * FROM (SELECT * FROM tables WHERE is_active = 1 ORDER BY id ASC) as t GROUP BY name ORDER BY area, sort_order, name"
        );
    }

    /** Tất cả bàn kể cả inactive (cho admin) */
    public function getAllForAdmin(): array
    {
        return $this->findAll(
            "SELECT * FROM tables ORDER BY area, sort_order, name"
        );
    }

    /** Lấy tất cả bàn đang trống */
    public function getAvailable(): array
    {
        return $this->findAll(
            "SELECT * FROM tables WHERE is_active = 1 AND status = 'available' ORDER BY area, sort_order, name"
        );
    }

    /** Lấy theo ID */
    public function findById(int $id): ?array
    {
        return $this->findOne(
            "SELECT * FROM tables WHERE id = ?",
            [$id]
        );
    }

    /** Nhóm bàn theo khu vực */
    public function getAllGroupedByArea(): array
    {
        $rows = $this->getAll();
        $grouped = [];
        foreach ($rows as $row) {
            $area = $row['area'] ?? 'Chung';
            $grouped[$area][] = $row;
        }
        return $grouped;
    }

    /** Mở bàn: đổi status → occupied */
    public function open(int $id): void
    {
        $this->execute(
            "UPDATE tables SET status = 'occupied', updated_at = NOW() WHERE id = ?",
            [$id]
        );
    }

    /** Đóng bàn: đổi status → available */
    public function close(int $id): void
    {
        $this->execute(
            "UPDATE tables SET status = 'available', updated_at = NOW() WHERE id = ?",
            [$id]
        );
    }

    /** Thêm bàn mới */
    public function create(array $data): int
    {
        $this->execute(
            "INSERT INTO tables (name, area, capacity, sort_order, is_active)
             VALUES (?, ?, ?, ?, 1)",
            [
                $data['name'],
                $data['area'] ?? null,
                $data['capacity'] ?? 4,
                $data['sort_order'] ?? 0,
            ]
        );
        return (int) $this->lastInsertId();
    }

    /** Cập nhật bàn */
    public function update(int $id, array $data): void
    {
        $this->execute(
            "UPDATE tables SET name = ?, area = ?, capacity = ?, sort_order = ?, is_active = ?
             WHERE id = ?",
            [
                $data['name'],
                $data['area'] ?? null,
                $data['capacity'] ?? 4,
                $data['sort_order'] ?? 0,
                $data['is_active'] ?? 1,
                $id,
            ]
        );
    }

    /** Xóa bàn (chỉ khi không có order đang open) */
    public function delete(int $id): bool
    {
        $inUse = $this->findOne(
            "SELECT id FROM orders WHERE table_id = ? AND status = 'open' LIMIT 1",
            [$id]
        );
        if ($inUse)
            return false;

        $this->execute("DELETE FROM tables WHERE id = ?", [$id]);
        return true;
    }

    /** Đếm theo trạng thái */
    public function countByStatus(): array
    {
        $rows = $this->findAll(
            "SELECT status, COUNT(*) as cnt FROM tables WHERE is_active = 1 GROUP BY status"
        );
        $result = ['available' => 0, 'occupied' => 0];
        foreach ($rows as $r) {
            $result[$r['status']] = (int) $r['cnt'];
        }
        return $result;
    }
}
