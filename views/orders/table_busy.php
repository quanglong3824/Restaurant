<?php // views/orders/table_busy.php — Table Busy Notification for Customers ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/table_busy.css">

<div class="table-busy-wrapper">
    <div class="busy-header">
        <div class="busy-icon-box">
            <i class="fas fa-user-lock"></i>
        </div>
        <h2 class="playfair">Bàn này đang bận</h2>
        <div class="table-badge">BÀN <?= e($table['name'] ?? 'NÀY') ?></div>
    </div>

    <div class="busy-content-card">
        <p class="busy-intro">
            Dường như bàn này hiện đã có khách đang sử dụng hoặc phiên làm việc của Quý khách đã hết hạn.
        </p>
        
        <div class="busy-guidelines">
            <div class="guide-item">
                <i class="fas fa-users-cog"></i>
                <div>
                    <strong>Dành cho khách đi cùng đoàn:</strong>
                    <span>Vui lòng xem chung thực đơn với người cùng bàn hoặc sử dụng thiết bị đã gọi món trước đó.</span>
                </div>
            </div>
            
            <div class="guide-item">
                <i class="fas fa-concierge-bell"></i>
                <div>
                    <strong>Dành cho khách vừa mới đến:</strong>
                    <span>Nếu bàn này thực tế còn trống, vui lòng liên hệ nhân viên phục vụ để được hỗ trợ mở bàn mới.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="busy-actions">
        <button type="button" class="btn-gold w-100 mb-3" onclick="location.reload()">
            <i class="fas fa-sync-alt me-2"></i> THỬ TẢI LẠI TRANG
        </button>
        <button type="button" class="btn-ghost w-100" onclick="window.history.back()">
            <i class="fas fa-chevron-left me-2"></i> QUAY LẠI
        </button>
    </div>

    <div class="busy-footer">
        <p>© AURORA HOTEL PLAZA — Trải nghiệm đẳng cấp</p>
    </div>
</div>
