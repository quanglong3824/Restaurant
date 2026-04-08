<?php // views/admin/realtime/qr_sessions.php ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin/qr-sessions.css">

<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-mobile-alt me-2"></i> Thiết bị QR đang hoạt động</h3>
                <span class="badge bg-primary"><?= count($sessions) ?> thiết bị</span>
            </div>
            <div class="card-body">
                <?php if (empty($sessions)): ?>
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-qrcode fa-4x mb-3 text-muted"></i>
                        <h4>Không có thiết bị QR nào đang hoạt động</h4>
                        <p>Thông tin sẽ xuất hiện khi khách quét mã QR để gọi món.</p>
                    </div>
                <?php else: ?>
                    <div class="session-monitor-grid">
                        <?php foreach ($sessions as $session): ?>
                            <div class="session-monitor-card">
                                <div class="session-id-header">
                                    <div class="device-icon">
                                        <i class="fas fa-tablet-screen-button"></i>
                                    </div>
                                    <div class="device-info">
                                        <span class="sid-label">MÃ THIẾT BỊ</span>
                                        <span class="sid-value"><?= e(substr($session['session_id'], 0, 12)) ?>...</span>
                                    </div>
                                    <div class="session-stat-badge">
                                        <?= count($session['tables']) ?> BÀN
                                    </div>
                                </div>
                                
                                <div class="session-connected-tables">
                                    <?php foreach ($session['tables'] as $table): 
                                        $isRoom = $table['table_type'] === 'room';
                                    ?>
                                        <div class="connected-table-item">
                                            <div class="table-name-group">
                                                <i class="fas <?= $isRoom ? 'fa-bed' : 'fa-chair' ?> fade-icon"></i>
                                                <strong><?= e($table['table_name']) ?></strong>
                                            </div>
                                            <div class="table-price">
                                                <?= formatPrice($table['total'] ?? 0) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="session-footer">
                                    <div class="footer-col">
                                        <span class="lbl">HỒI ĐÁP CUỐI</span>
                                        <span class="val"><?= date('H:i', strtotime($session['since'])) ?></span>
                                    </div>
                                    <div class="footer-col text-end">
                                        <span class="lbl">TỔNG TẤT CẢ</span>
                                        <span class="val total"><?= formatPrice($session['total_all']) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>