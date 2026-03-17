# Database Migration v1.5 — Enhanced Table Split/Merge

## 📋 Tổng quan

Migration v1.5 bổ sung tính năng **Ghép/Tách bàn nâng cao**, cho phép:
- Ghép nhiều bàn vào 1 order lớn
- Tách món từ bàn ghép sang bàn riêng để thanh toán
- Chuyển món giữa các bàn trong cùng 1 order
- Track lịch sử split/transfer

## 🚀 Hướng dẫn chạy migration

### Cách 1: Command Line (Recommended)

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/Github/AURORA\ HOTEL\ PLAZA/DOANH\ NGHIỆP/restaurant/database
mysql -u root -p auroraho_restaurant < update_v1.5.sql
```

### Cách 2: MySQL Client

```sql
mysql -u root -p
USE auroraho_restaurant;
source /Applications/XAMPP/xamppfiles/htdocs/Github/AURORA\ HOTEL\ PLAZA/DOANH\ NGHIỆP/restaurant/database/update_v1.5.sql;
```

### Cách 3: phpMyAdmin

1. Mở phpMyAdmin
2. Chọn database `auroraho_restaurant`
3. Click tab "SQL"
4. Copy toàn bộ nội dung file `update_v1.5.sql`
5. Click "Go"

## ✅ Verify Migration

Sau khi chạy, kiểm tra các cột mới:

```sql
DESCRIBE order_items;
-- Phải có: table_id, split_from_item_id, is_split_item

SHOW INDEX FROM order_items;
-- Phải có: idx_order_items_table, idx_split_tracking, idx_table_status

SHOW PROCEDURE STATUS WHERE Db = 'auroraho_restaurant' AND Name = 'sp_split_order_items';
-- Phải tồn tại stored procedure
```

## 📊 Các thay đổi Database

### Bảng `order_items`

| Cột mới | Kiểu | Mô tả |
|---------|------|-------|
| `table_id` | INT UNSIGNED | ID bàn vật lý mà món thuộc về |
| `split_from_item_id` | INT UNSIGNED | ID món gốc (nếu được tách) |
| `is_split_item` | TINYINT(1) | 1 = món đã được tách từ bàn khác |

### Indexes mới

- `idx_order_items_table` — Tìm món theo table_id
- `idx_split_tracking` — Query món split
- `idx_table_status` — Composite index (table_id, status)

### Stored Procedures

- `sp_split_order_items` — DB-level split operation (safety net)

## 🎯 Use Cases

### 1. Ghép bàn (Table Merge)

```
Bàn 1 (Order ID: 10) + Bàn 2 (Order ID: 11) 
→ Merge vào Bàn 1 (Order ID: 10)
→ Bàn 2 trở thành child của Bàn 1 (parent_id = 1)
→ Tất cả món mới vào Order 10 với table_id = 1
```

### 2. Tách bàn (Table Split)

```
Bàn A (Order ID: 20) có 5 món
→ Chọn 2 món cần tách
→ Tạo Order mới (ID: 21) cho Bàn B
→ 2 món được chuyển sang Order 21 với table_id = B
→ is_split_item = 1, split_from_item_id = ID món gốc
```

### 3. Transfer món (Item Transfer)

```
Bàn A1 (merged) có món nháp
→ Transfer sang Bàn A2 (cùng order)
→ Cập nhật table_id của món từ A1 → A2
→ Chỉ hoạt động với status = 'draft'
```

## ⚠️ Lưu ý quan trọng

1. **Backup database trước khi chạy migration:**
   ```bash
   mysqldump -u root -p auroraho_restaurant > backup_before_v1.5.sql
   ```

2. **Thời gian downtime:** ~5-10 giây (tùy số lượng dữ liệu)

3. **Không chạy migration nhiều lần** — Các câu lệnh đã có kiểm tra tồn tại

4. **Sau migration:** Restart Apache/MySQL để đảm bảo cache được clear

## 🔧 Troubleshooting

### Lỗi: "Foreign key constraint fails"

```sql
-- Kiểm tra bảng tables có tồn tại không
SHOW TABLES LIKE 'tables';

-- Nếu không có, đổi tên từ table sang tables
RENAME TABLE table TO tables;
```

### Lỗi: "Duplicate column name"

Migration đã có kiểm tra, nếu vẫn bị:
```sql
-- Xóa cột trùng lặp (cẩn thận!)
ALTER TABLE order_items DROP COLUMN table_id;
-- Chạy lại migration
```

### Lỗi: "Cannot add foreign key"

```sql
-- Kiểm tra constraint cũ
SHOW CREATE TABLE order_items;

-- Xóa constraint cũ nếu có
ALTER TABLE order_items DROP FOREIGN KEY fk_order_items_table;

-- Chạy lại migration
```

## 📞 Support

Nếu có vấn đề, tạo issue trên GitHub với:
- Lỗi cụ thể (copy nguyên văn)
- MySQL version
- PHP version
- Screenshot (nếu có)

---

**Version:** 1.5  
**Date:** 2026-03-17  
**Author:** Aurora Restaurant Dev Team
