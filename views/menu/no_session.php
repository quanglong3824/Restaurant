<?php // views/menu/no_session.php — No active session found ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/menu/no_session.css">

<div class="no-session-container">
    <div class="no-session-card">
        <div class="no-session-icon">
            <i class="fas fa-search-location"></i>
        </div>
        <h2>KHÔNG TÌM THẤY PHIÊN</h2>
        <p>Hệ thống không nhận diện được thiết bị này hoặc bạn chưa quét mã QR tại bàn.</p>
        
        <div class="instruction-box">
            <h4>Cách bắt đầu:</h4>
            <ul>
                <li>1. Tìm mã QR được dán tại bàn/phòng.</li>
                <li>2. Sử dụng Camera điện thoại để quét mã.</li>
                <li>3. Đưa thông tin xác thực vị trí để xem menu.</li>
            </ul>
        </div>

        <a href="<?= BASE_URL ?>" class="btn-gold-premium w-100">QUAY LẠI TRANG CHỦ</a>
    </div>
</div>
