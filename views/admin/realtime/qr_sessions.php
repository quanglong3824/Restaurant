<?php // views/admin/realtime/qr_sessions.php ?>
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

<style>
.session-monitor-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 10px;
}

.session-monitor-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.2s;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}
.session-monitor-card:hover {
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
}

.session-id-header {
    padding: 15px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 15px;
}
.device-icon {
    width: 40px;
    height: 40px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #475569;
    font-size: 1.2rem;
}
.device-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.sid-label {
    font-size: 0.6rem;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 1px;
}
.sid-value {
    font-size: 0.85rem;
    font-weight: 700;
    color: #1e293b;
    font-family: 'JetBrains Mono', monospace;
}
.session-stat-badge {
    background: #1e293b;
    color: #fff;
    font-size: 0.65rem;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 50px;
}

.session-connected-tables {
    padding: 10px 20px;
    background: #fff;
    min-height: 80px;
}
.connected-table-item {
    padding: 10px 0;
    border-bottom: 1px dashed #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.connected-table-item:last-child {
    border-bottom: none;
}
.table-name-group {
    display: flex;
    align-items: center;
    gap: 10px;
}
.fade-icon {
    color: #cbd5e1;
    font-size: 0.9rem;
}
.table-price {
    font-weight: 600;
    color: #475569;
    font-size: 0.9rem;
}

.session-footer {
    padding: 15px 20px;
    background: #fdfdfd;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
}
.footer-col {
    display: flex;
    flex-direction: column;
}
.footer-col .lbl {
    font-size: 0.6rem;
    font-weight: 800;
    color: #94a3b8;
    margin-bottom: 2px;
}
.footer-col .val {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
}
.footer-col .val.total {
    color: #b8860b;
    font-size: 1.1rem;
}

.empty-state i {
    opacity: 0.2;
}
</style>
