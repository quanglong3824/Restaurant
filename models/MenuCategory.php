<?php
// ============================================================
// MenuCategory Model — Aurora Restaurant
// ============================================================

class MenuCategory extends Model
{
    public function getAll(): array
    {
        return $this->findAll(
            "SELECT * FROM menu_categories ORDER BY sort_order, name"
        );
    }

    /** Lấy tất cả danh mục đang hoạt động, gom nhóm tên */
    public function getActive(): array
    {
        return $this->findAll(
            "SELECT * FROM (SELECT * FROM menu_categories WHERE is_active = 1 ORDER BY id ASC) as t GROUP BY name ORDER BY sort_order"
        );
    }

    public function findById(int $id): ?array
    {
        return $this->findOne("SELECT * FROM menu_categories WHERE id = ?", [$id]);
    }

    public function create(array $data): int
    {
        $this->execute(
            "INSERT INTO menu_categories (name, name_en, icon, sort_order, is_active)
             VALUES (?, ?, ?, ?, 1)",
            [
                $data['name'],
                $data['name_en'] ?? null,
                $data['icon'] ?? 'fa-utensils',
                $data['sort_order'] ?? 0,
            ]
        );
        return (int) $this->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->execute(
            "UPDATE menu_categories
             SET name = ?, name_en = ?, icon = ?, sort_order = ?, is_active = ?
             WHERE id = ?",
            [
                $data['name'],
                $data['name_en'] ?? null,
                $data['icon'] ?? 'fa-utensils',
                $data['sort_order'] ?? 0,
                $data['is_active'] ?? 1,
                $id,
            ]
        );
    }

    public function delete(int $id): bool
    {
        $hasItems = $this->findOne(
            "SELECT id FROM menu_items WHERE category_id = ? LIMIT 1",
            [$id]
        );
        if ($hasItems)
            return false;

        $this->execute("DELETE FROM menu_categories WHERE id = ?", [$id]);
        return true;
    }
}
