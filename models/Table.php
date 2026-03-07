<?php
// ============================================================
// Table Model — Aurora Restaurant
// ============================================================

class Table extends Model
{
    protected string $table = 'tables';

    /** Lấy bàn theo ID */
    public function findById(int $id): ?array
    {
        return $this->findOne(
            "SELECT t.*, p.name as parent_name
             FROM tables t
             LEFT JOIN tables p ON t.parent_id = p.id
             WHERE t.id = ?",
            [$id]
        );
    }

    /** Tất cả bàn đang active, sắp xếp theo khu vực + thứ tự */
    public function getAll(): array
    {
        return $this->findAll(
            "SELECT t.*, p.name as parent_name
             FROM tables t
             LEFT JOIN tables p ON t.parent_id = p.id
             WHERE t.is_active = 1
             ORDER BY 
                CASE t.area
                    WHEN 'A1' THEN 1
                    WHEN 'B1' THEN 2
                    WHEN 'C1' THEN 3
                    WHEN 'VIP 1' THEN 4
                    WHEN 'VIP 2' THEN 5
                    WHEN 'VIP 3' THEN 6
                    WHEN 'VIP 4' THEN 7
                    WHEN 'Âu' THEN 8
                    ELSE 99
                END,
                t.sort_order, t.name"
        );
    }

    /** Lấy tất cả bàn đang trống (không phải bàn đang ghép vào bàn khác) */
    public function getAvailable(): array
    {
        return $this->findAll(
            "SELECT * FROM tables 
             WHERE is_active = 1 AND status = 'available' AND parent_id IS NULL 
             ORDER BY area, sort_order, name"
        );
    }

    /** Nhóm bàn theo khu vực (loại bỏ bàn đang ghép nếu cần, hoặc hiển thị lồng nhau) */
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

    /** Ghép bàn: childId ghép vào parentId */
    public function mergeTable(int $childId, int $parentId): bool
    {
        // Kiểm tra xem bàn con có đang có order không
        $inUse = $this->findOne(
            "SELECT id FROM orders WHERE table_id = ? AND status = 'open' LIMIT 1",
            [$childId]
        );
        if ($inUse) return false;

        $this->execute(
            "UPDATE tables SET parent_id = ?, status = 'occupied', updated_at = NOW() WHERE id = ?",
            [$parentId, $childId]
        );
        return true;
    }

    /** Hủy ghép bàn */
    public function unmergeTable(int $childId): void
    {
        $this->execute(
            "UPDATE tables SET parent_id = NULL, status = 'available', updated_at = NOW() WHERE id = ?",
            [$childId]
        );
    }

    /** Lấy tên hiển thị đầy đủ (bao gồm cả các bàn đang ghép chung) */
    public function getFullDisplayName(int $tableId): string
    {
        $table = $this->findById($tableId);
        if (!$table) return "N/A";

        // Nếu bàn này là con, lấy bàn cha của nó
        $effectiveParentId = $table['parent_id'] ?: $table['id'];
        
        // Lấy tất cả bàn trong nhóm (cha + con)
        $group = $this->findAll(
            "SELECT name FROM tables 
             WHERE id = ? OR parent_id = ? 
             ORDER BY name ASC",
            [$effectiveParentId, $effectiveParentId]
        );

        if (count($group) <= 1) return $table['name'];

        $names = array_column($group, 'name');
        return implode(' + ', $names);
    }

    /** Lấy các bàn đang ghép vào bàn cha */
    public function getMergedTables(int $parentId): array
    {
        return $this->findAll("SELECT * FROM tables WHERE parent_id = ?", [$parentId]);
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

    /** Lấy tất cả bàn cho Admin (bao gồm cả inactive) */
    public function getAllForAdmin(): array
    {
        return $this->findAll(
            "SELECT t.*, p.name as parent_name
             FROM tables t
             LEFT JOIN tables p ON t.parent_id = p.id
             ORDER BY 
                CASE t.area
                    WHEN 'A1' THEN 1
                    WHEN 'B1' THEN 2
                    WHEN 'C1' THEN 3
                    WHEN 'VIP 1' THEN 4
                    WHEN 'VIP 2' THEN 5
                    WHEN 'VIP 3' THEN 6
                    WHEN 'VIP 4' THEN 7
                    WHEN 'Âu' THEN 8
                    ELSE 99
                END,
                t.sort_order, t.name"
        );
    }
}
