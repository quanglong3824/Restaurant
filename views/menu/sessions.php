<?php // views/menu/sessions.php — My Active Tables / Sessions Management ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/menu/sessions.css">

<div class="sessions-container">
    <header class="sessions-header">
        <h1 class="playfair">PHIÊN LÀM VIỆC</h1>
        <p class="visitor-token-label">Mã thiết bị: <code><?= substr(e($visitorToken), 0, 8) ?>...</code></p>
    </header>

    <div class="sessions-content">
        <?php if (empty($orders)): ?>
            <div class="empty-sessions">
                <i class="fas fa-qrcode"></i>
                <h3>Chưa có phiên hoạt động</h3>
                <p>Quét mã QR tại bàn để bắt đầu đặt món.</p>
                <a href="<?= BASE_URL ?>" class="btn-gold-premium mt-4">QUAY LẠI TRANG CHỦ</a>
            </div>
        <?php else: ?>
            <div class="active-orders-list">
                <?php foreach ($orders as $order): 
                    $isRoom = $order['table_type'] === 'room';
                    $total = $order['total'] ?? 0;
                ?>
                    <div class="session-card">
                        <div class="session-card-header">
                            <div class="table-badge-large <?= $isRoom ? 'room' : 'restaurant' ?>">
                                <i class="fas <?= $isRoom ? 'fa-bed' : 'fa-utensils' ?>"></i>
                                <span><?= e($order['table_name']) ?></span>
                            </div>
                            <div class="session-status occupied">Đang mở</div>
                        </div>

                        <div class="session-card-body">
                            <div class="session-info-row">
                                <span class="label">Bắt đầu:</span>
                                <span class="value"><?= date('H:i, d/m', strtotime($order['opened_at'])) ?></span>
                            </div>
                            <div class="session-info-row">
                                <span class="label">Tạm tính:</span>
                                <span class="value price"><?= formatPrice($total) ?></span>
                            </div>
                        </div>

                        <div class="session-card-footer">
                            <a href="<?= BASE_URL ?>/qr/menu?table_id=<?= $order['table_id'] ?>&token=<?= $order['qr_hash'] ?>" class="btn-enter-menu">
                                QUAY LẠI ĐẶT MÓN <i class="fas fa-chevron-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <p class="sessions-footer-note">
                <i class="fas fa-info-circle me-1"></i> 
                Hệ thống tự động ghi nhớ các bàn bạn đã quét bằng thiết bị này.
            </p>
        <?php endif; ?>
    </div>
</div>
