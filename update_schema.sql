-- Thêm cột đánh dấu đã in cho mã QR
ALTER TABLE qr_tables ADD COLUMN is_printed TINYINT(1) DEFAULT 0;

-- Đảm bảo menu_type có đủ các giá trị cần thiết
-- Lưu session_id của khách vào Order để chống spam từ xa
ALTER TABLE orders ADD COLUMN session_id VARCHAR(64) DEFAULT NULL;
CREATE INDEX idx_orders_session ON orders(session_id);
