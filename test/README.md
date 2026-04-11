# Aurora Restaurant Management System v2.1.1 (Phối hợp Lưu trú)

![Version](https://img.shields.io/badge/version-2.1.1--experimental-gold)
![License](https://img.shields.io/badge/Copyright-%C2%A9%202026%20LongDev-blue)
![Platform](https://img.shields.io/badge/Platform-iPad%20%7C%20Mobile%20%7C%20Web-green)

**Aurora Restaurant** là hệ thống quản lý nhà hàng kỹ thuật số cao cấp, được thiết kế chuyên biệt cho Aurora Hotel Plaza. Hệ thống tối ưu hóa quy trình từ lúc khách vào bàn, gọi món qua QR cho đến khi thanh toán, với khả năng đồng bộ dữ liệu thời gian thực (Real-time) tuyệt đối.

---

## 🚀 Tính năng nổi bật (v2.1.1)

- **Hỗ trợ Lưu trú**: Tối ưu hóa sơ đồ phòng và quản lý khách lưu trú (tối đa 3 khách/phòng).
- **Phối hợp thử nghiệm**: Bản thử nghiệm phối hợp đa nền tảng cho Hotel & Restaurant.
- **Real-time Ajax Polling**: Hệ thống thông báo và dashboard giám sát cập nhật tức thì (không cần tải lại trang).
- **Geofencing Security**: Giới hạn phạm vi đặt món trong bán kính 500m quanh nhà hàng bằng tọa độ GPS thực tế.
- **Smart QR Ordering**: Chống spam bằng Session lượt khách; tự động dọn dẹp đơn hàng ảo sau 5 phút không hoạt động.
- **Bilingual Menu**: Hỗ trợ tìm kiếm và hiển thị song ngữ Việt - Anh linh hoạt.
- **iPad Optimization**: Giao diện Landing Page sang trọng, hỗ trợ tính năng "Add to Home Screen" như một ứng dụng gốc.

---

## 🛠 Quy trình làm việc (Workflows)

### 1. Quy trình dành cho Khách hàng (Customer QR)
1.  **Quét mã QR**: Khách quét mã tại bàn. Hệ thống yêu cầu xác nhận vị trí (GPS) để đảm bảo khách đang ngồi tại nhà hàng.
2.  **Xem thực đơn**: Menu hiển thị với bộ lọc thông minh (Món Á, Âu, Set Menu, Alacarte).
3.  **Gọi món**: 
    *   Khách chọn món, thêm ghi chú (không hành, ít cay...).
    *   Hệ thống tạo Session duy nhất cho lượt ngồi đó.
    *   Nếu khách đã quét bàn A nhưng chưa gọi món mà sang bàn B quét tiếp, hệ thống tự động chuyển vùng phục vụ.
4.  **Xác nhận đặt món**: Đơn hàng được gửi đi và chờ nhân viên xác nhận.
5.  **Theo dõi**: Khách xem được trạng thái món ăn (Đã xác nhận/Đang chế biến) và xem hóa đơn tạm tính.

### 2. Quy trình dành cho Nhân viên (Waiter iPad)
1.  **Đăng nhập & Chọn ca**: Nhân viên đăng nhập bằng mã PIN và chọn ca trực tương ứng.
2.  **Quản lý bàn**: 
    *   Mở bàn mới hoặc tiếp nhận đơn hàng từ khách quét QR.
    *   Thực hiện ghép bàn (nếu khách đi đoàn đông) hoặc tách đơn, chuyển bàn, chuyển món giữa các bàn.
3.  **Xử lý yêu cầu**: Nhận thông báo Real-time (âm thanh + badge số) khi khách gọi phục vụ, yêu cầu tăm/nước hoặc thanh toán.
4.  **In hóa đơn**: Xuất hóa đơn in nhiệt (80mm) cho khách kiểm tra trước khi thu tiền.

### 3. Quy trình dành cho Quản lý (Admin Dashboard)
1.  **Giám sát thực tế**: Dashboard cập nhật mỗi 8 giây, hiển thị chi tiết số đợt gọi món, tổng tiền và thời gian ngồi của từng bàn.
2.  **Quản lý QR**: Tạo mới hoặc Reset mã QR cho từng bàn. Hệ thống cảnh báo nếu mã QR đó đã được in ra giấy hoặc bàn đang có khách.
3.  **Cấu hình Menu**: Thay đổi giá, cập nhật ảnh món ăn, quản lý các Set Combo đặc biệt.
4.  **Báo cáo**: Thống kê doanh thu theo ngày/tuần/tháng và phân tích hiệu suất nhân viên.

---

## 🏗 Cấu trúc hệ thống (Architecture)

- **Backend**: PHP thuần (Core MVC Architecture), tối ưu hóa tốc độ xử lý.
- **Frontend**: JavaScript (Ajax Polling), CSS3 (Premium UI/UX), FontAwesome 6.
- **Database**: MySQL với cấu trúc Schema chặt chẽ, hỗ trợ Indexing cho các truy vấn Real-time.
- **Security**: 
    *   Xác thực Session lượt khách.
    *   Kiểm tra Geolocation (Haversine Formula).
    *   Chống SQL Injection & XSS qua hệ thống helper tích hợp.

---

## 💻 Hướng dẫn cài đặt

1.  Clone dự án về thư mục web (XAMPP/Laragon): `htdocs/restaurant`.
2.  Cấu hình Database trong `config/database.php`.
3.  Thiết lập tọa độ nhà hàng trong `config/constants.php`.
4.  Tài khoản mặc định: `admin` / PIN: `123456`.

---

### 📄 Bản quyền & Phát triển
- **Lead Developer**: LongDev
- **Release Date**: 18/03/2026
- **Copyright**: © 2026 LongDev. All rights reserved.

*Hệ thống được phát triển và vận hành trực tiếp trên môi trường Production của Aurora Hotel Plaza.*
