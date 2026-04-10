<?php
/**
 * Setting Model — Lưu trữ các cài đặt hệ thống
 */
class Setting extends Model
{
    /**
     * Lấy tất cả settings
     */
    public function getAll(): array
    {
        return $this->execute("SELECT * FROM settings ORDER BY created_at DESC");
    }

    /**
     * Lấy giá trị của một setting
     */
    public function getValue(string $key, $default = null)
    {
        $result = $this->execute("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
        if (empty($result)) {
            return $default;
        }
        return $result[0]['setting_value'];
    }

    /**
     * Cập nhật hoặc tạo mới một setting
     */
    public function setValue(string $key, string $value, string $description = ''): bool
    {
        // Kiểm tra xem setting đã tồn tại chưa
        $existing = $this->execute("SELECT id FROM settings WHERE setting_key = ?", [$key]);
        
        if (empty($existing)) {
            // Tạo mới
            return $this->execute(
                "INSERT INTO settings (setting_key, setting_value, description, updated_at) VALUES (?, ?, ?, NOW())",
                [$key, $value, $description]
            ) !== false;
        } else {
            // Cập nhật
            return $this->execute(
                "UPDATE settings SET setting_value = ?, description = ?, updated_at = NOW() WHERE setting_key = ?",
                [$value, $description, $key]
            ) !== false;
        }
    }

    /**
     * Xóa một setting
     */
    public function delete(string $key): bool
    {
        return $this->execute("DELETE FROM settings WHERE setting_key = ?", [$key]) !== false;
    }

    /**
     * Lấy setting dưới dạng boolean
     */
    public function getBoolean(string $key, bool $default = false): bool
    {
        $value = $this->getValue($key, null);
        if ($value === null) {
            return $default;
        }
        return in_array(strtolower($value), ['1', 'true', 'yes', 'on']);
    }
}