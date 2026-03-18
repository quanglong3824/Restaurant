-- Thêm cột đánh dấu đã in cho mã QR
ALTER TABLE qr_tables ADD COLUMN is_printed TINYINT(1) DEFAULT 0;

-- Đảm bảo menu_type có đủ các giá trị cần thiết
-- Lưu ý: Nếu cột menu_type đã là ENUM, bạn có thể cần chạy lệnh này để mở rộng các giá trị
ALTER TABLE menu_categories MODIFY COLUMN menu_type ENUM('asia', 'europe', 'alacarte', 'set', 'other') DEFAULT 'asia';
