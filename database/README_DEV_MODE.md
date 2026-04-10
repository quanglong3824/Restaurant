# HƯỚNG DẪN SỬ DỤNG DEV MODE
## Tắt kiểm tra vị trí để phát triển và kiểm thử

---

## 📋 Tổng quan

Tính năng **DEV MODE** cho phép lập trình viên và nhân viên kiểm thử hệ thống đặt món từ bất kỳ đâu mà không cần phải có mặt tại nhà hàng. Điều này đặc biệt hữu ích khi:

- Dev ngồi ở nhà test tính năng
- Nhân viên training từ xa
- Kiểm thử các tính năng mới trước khi triển khai
- Demo hệ thống cho khách hàng từ xa

---

## 🚀 Cách kích hoạt DEV MODE

### Cách 1: Qua giao diện quản trị (Khuyến nghị)

1. **Đăng nhập với quyền IT hoặc Admin**
   - URL: `https://your-domain.com/auth/login`

2. **Truy cập trang Cài đặt hệ thống**
   - Click vào menu **"Quản trị IT"** → **"Cài đặt hệ thống"**
   - Hoặc truy cập trực tiếp: `https://your-domain.com/it/settings`

3. **Bật DEV MODE**
   - Tìm card **"Chế Độ Phát Triển (DEV MODE)"**
   - Gạt công tắc từ **TẮT** sang **BẬT**
   - Click **"Lưu Thay Đổi"**

4. **Xác nhận**
   - Khi DEV MODE được bật, một banner màu tím sẽ xuất hiện ở trên cùng giao diện menu khách hàng
   - Banner hiển thị: `🔧 DEV MODE — Kiểm tra vị trí đã tắt 🔧`

### Cách 2: Chạy SQL trực tiếp (Dành cho Database Admin)

```sql
-- Chạy migration để tạo bảng settings
SOURCE /path/to/restaurant/database/settings_migration.sql;

-- Hoặc chạy thủ công:
UPDATE settings SET setting_value = '1' WHERE setting_key = 'dev_mode';
```

---

## 📱 Cách sử dụng

Sau khi kích hoạt DEV MODE:

1. **Mở menu khách hàng**
   - Quét mã QR hoặc truy cập: `https://your-domain.com/qr/menu?table_id=1&token=your_token`

2. **Xác nhận DEV MODE đang hoạt động**
   - Nhìn thấy banner màu tím ở trên cùng
   - Không cần xác nhận vị trí nữa
   - Có thể đặt món từ bất kỳ đâu

3. **Test các tính năng**
   - Xem menu
   - Thêm món vào giỏ
   - Đặt món
   - Gọi nhân viên
   - Yêu cầu thanh toán

---

## ⚠️ Lưu ý quan trọng

### Bảo mật
- **KHÔNG BẬT DEV MODE TRÊN PRODUCTION** khi không cần thiết
- Chỉ sử dụng trong môi trường phát triển hoặc kiểm thử
- Tắt DEV MODE sau khi hoàn thành công việc

### Hiệu năng
- Khi DEV MODE bật, hệ thống sẽ bỏ qua bước kiểm tra vị trí
- Điều này giúp tăng tốc độ load trang
- Nhưng có thể cho phép khách đặt món từ xa (không mong muốn trong thực tế)

### Đối tượng sử dụng
- **IT/Developer**: Phát triển và kiểm thử tính năng
- **Admin**: Quản lý và giám sát hệ thống
- **Waiter (có quyền IT)**: Training và học quy trình

---

## 🔄 Tắt DEV MODE

1. Truy cập: `https://your-domain.com/it/settings`
2. Tìm card **"Chế Độ Phát Triển (DEV MODE)"**
3. Gạt công tắc từ **BẬT** sang **TẮT**
4. Click **"Lưu Thay Đổi"**

Hoặc chạy SQL:
```sql
UPDATE settings SET setting_value = '0' WHERE setting_key = 'dev_mode';
```

---

## 🛠️ Cài đặt kỹ thuật

### Database Migration

File migration được lưu tại: `database/settings_migration.sql`

Để tạo bảng settings, chạy lệnh sau trong MySQL:

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/Github/AURORA\ HOTEL\ PLAZA/DOANH\ NGHIỆP/restaurant/database
mysql -u root -p aurora_restaurant < settings_migration.sql
```

### Cấu trúc bảng `settings`

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| `id` | INT(11) | Khóa chính, tự tăng |
| `setting_key` | VARCHAR(100) | Khóa cài đặt (duy nhất) |
| `setting_value` | TEXT | Giá trị cài đặt |
| `description` | VARCHAR(255) | Mô tả (tùy chọn) |
| `created_at` | DATETIME | Thời gian tạo |
| `updated_at` | DATETIME | Thời gian cập nhật cuối |

### Các setting mặc định

| setting_key | Giá trị mặc định | Mô tả |
|-------------|-----------------|-------|
| `dev_mode` | `0` | Chế độ phát triển - Tắt kiểm tra vị trí |
| `maintenance_mode` | `0` | Chế độ bảo trì - Ẩn website |
| `allow_online_payment` | `1` | Cho phép thanh toán online |
| `auto_print_orders` | `1` | Tự động in đơn hàng |

---

## 📂 Các file đã thay đổi

| File | Mô tả thay đổi |
|------|---------------|
| `models/Setting.php` | Model mới để làm việc với bảng settings |
| `controllers/SettingController.php` | Thêm methods quản lý settings |
| `views/it/settings.php` | View quản lý cài đặt hệ thống |
| `views/layouts/admin.php` | Thêm link menu "Cài đặt hệ thống" |
| `controllers/QrMenuController.php` | Đọc dev_mode từ database và truyền vào view |
| `views/menu/customer.php` | Sử dụng biến $devMode thay vì constant |
| `config/constants.php` | Cập nhật comment cho DEV_MODE constant |
| `index.php` | Thêm routes mới cho settings |
| `database/settings_migration.sql` | Migration tạo bảng settings |

---

## 🎯 Tính năng bổ sung trong tương lai

- [ ] Tự động tắt DEV_MODE sau một khoảng thời gian nhất định
- [ ] Gửi notification khi DEV_MODE được bật
- [ ] Log lịch sử bật/tắt DEV_MODE
- [ ] Giới hạn DEV_MODE theo IP hoặc user
- [ ] Thêm các setting khác (maintenance mode, online payment, etc.)

---

## 📞 Hỗ trợ

Nếu bạn gặp vấn đề khi sử dụng tính năng này, vui lòng liên hệ:
- **Developer**: LongDev
- **Email**: support@aurorarestaurant.com
- **Hotline**: 1900 xxxx

---

*Phiên bản tài liệu: 1.0 - Cập nhật: 2026-04-10*