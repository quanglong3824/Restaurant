<?php
// ============================================================
// MenuType Model — Aurora Restaurant
// ============================================================

class MenuType extends Model
{
    protected string $table = 'menu_types';

    /** Lấy tất cả các loại menu */
    public function getAll(): array
    {
        return $this->findAll(
            "SELECT * FROM menu_types ORDER BY sort_order, name"
        );
    }

    /** Lấy các loại menu đang active */
    public function getActive(): array
    {
        return $this->findAll(
            "SELECT * FROM menu_types WHERE is_active = 1 ORDER BY sort_order, name"
        );
    }

    public function findById(int $id): ?array
    {
        return $this->findOne("SELECT * FROM menu_types WHERE id = ?", [$id]);
    }

    public function findByKey(string $key): ?array
    {
        return $this->findOne("SELECT * FROM menu_types WHERE type_key = ?", [$key]);
    }

    public function create(array $data): int
    {
        $this->execute(
            "INSERT INTO menu_types (name, name_en, type_key, description, color, icon, sort_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?, 1)",
            [
                $data['name'],
                $data['name_en'] ?? null,
                $data['type_key'],
                $data['description'] ?? null,
                $data['color'] ?? '#0ea5e9',
                $data['icon'] ?? 'fa-utensils',
                $data['sort_order'] ?? 0,
            ]
        );
        return (int) $this->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->execute(
            "UPDATE menu_types
             SET name = ?, name_en = ?, type_key = ?, description = ?, color = ?, 
                 icon = ?, sort_order = ?, is_active = ?
             WHERE id = ?",
            [
                $data['name'],
                $data['name_en'] ?? null,
                $data['type_key'],
                $data['description'] ?? null,
                $data['color'] ?? '#0ea5e9',
                $data['icon'] ?? 'fa-utensils',
                $data['sort_order'] ?? 0,
                $data['is_active'] ?? 1,
                $id,
            ]
        );
    }

    public function delete(int $id): bool
    {
        // Kiểm tra xem có danh mục nào đang sử dụng loại menu này không
        $hasCategories = $this->findOne(
            "SELECT id FROM menu_categories WHERE menu_type = (SELECT type_key FROM menu_types WHERE id = ?) LIMIT 1",
            [$id]
        );
        if ($hasCategories)
            return false;

        $this->execute("DELETE FROM menu_types WHERE id = ?", [$id]);
        return true;
    }

    public function toggleActive(int $id): void
    {
        $this->execute("UPDATE menu_types SET is_active = NOT is_active WHERE id = ?", [$id]);
    }
}