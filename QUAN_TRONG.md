# ⚠️ QUAN TRỌNG - Sau khi restore database từ backup

## Vấn đề

Bạn đã restore database từ backup cũ, nhưng **code đã được update** với tính năng mới:
- ✅ Ghép/Tách bàn nâng cao
- ✅ Toast notification
- ✅ Checkbox "Xem hoá đơn"
- ✅ Fix thanh toán bị đơ

Database cũ **thiếu các cột cần thiết**:
- `order_items.table_id`
- `order_items.split_from_item_id`
- `order_items.is_split_item`

## ✅ Giải pháp - CHẠY NGAY

### Cách 1: Qua phpMyAdmin (Recommended)

1. Mở phpMyAdmin: `http://localhost/phpmyadmin`
2. Chọn database: `auroraho_restaurant`
3. Click tab **SQL**
4. Mở file: `database/update_v1.5.sql` (copy toàn bộ nội dung)
5. Paste vào ô SQL
6. Click **Go**

### Cách 2: Nếu có MySQL CLI

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/Github/AURORA\ HOTEL\ PLAZA/DOANH\ NGHIỆP/restaurant/database
mysql -u root -p auroraho_restaurant < update_v1.5.sql
```

## ✅ Verify sau khi chạy

Trong phpMyAdmin, chạy:

```sql
DESCRIBE order_items;
```

Phải thấy các cột:
- ✅ `table_id`
- ✅ `split_from_item_id`
- ✅ `is_split_item`

## 🎯 Sau khi migration xong

1. Clear cache trình duyệt (Ctrl+Shift+R)
2. Vào menu page: `https://aurorahotelplaza.com/restaurant/menu?table_id=19&order_id=42`
3. Test thêm món → Phải hoạt động
4. Test tách bàn → Phải thấy nút "Tách"

## 📞 Nếu vẫn lỗi

1. Clear cache XAMPP: Stop Apache → Start lại
2. Clear browser cache
3. Check console (F12) xem lỗi gì
4. Report lỗi cụ thể

---

**Files cần thiết:**
- `database/update_v1.5.sql` ← CHẠY FILE NÀY
- `database/README_MIGRATION.md` ← Hướng dẫn chi tiết

**Version:** 1.5  
**Date:** 2026-03-17
