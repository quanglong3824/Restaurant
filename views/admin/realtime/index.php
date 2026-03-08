<?php
// views/admin/realtime/index.php
?>

<div class="realtime-dashboard">
    <!-- Premium Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chair"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?= $counts['occupied'] ?></div>
                <div class="stat-label">Bàn đang ăn</div>
            </div>
        </div>

        <div class="stat-card" style="border-color: rgba(16, 185, 129, 0.2);">
            <div class="stat-icon"
                style="background: rgba(16, 185, 129, 0.1); color: var(--success); border-color: rgba(16, 185, 129, 0.2);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value" style="color: var(--success);"><?= $counts['available'] ?></div>
                <div class="stat-label">Bàn trống</div>
            </div>
        </div>

        <div class="stat-card" style="grid-column: span 2;">
            <div class="stat-info"
                style="width: 100%; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="stat-label">Tự động cập nhật</div>
                    <div style="font-size: 0.9rem; color: var(--text-light);"><i
                            class="fas fa-sync-alt fa-spin me-1"></i> Làm mới sau <span id="reloadCount"
                            class="fw-bold text-white">15</span> giây</div>
                </div>
                <button onclick="refreshData()" class="btn btn-gold btn-sm">
                    <i class="fas fa-redo"></i> CẬP NHẬT NGAY
                </button>
            </div>
        </div>
    </div>

    <!-- Active Orders Section -->
    <div class="card card-premium">
        <div class="card-header">
            <h2 class="playfair"><i class="fas fa-satellite-dish me-2"></i> Giám sát bàn trực tiếp</h2>
            <div class="badge badge-gold"><?= count($orders) ?> active</div>
        </div>

        <div id="realtimeListContainer">
            <?php if (empty($orders)): ?>
                <div class="empty-state py-5 text-center">
                    <i class="fas fa-mug-hot fa-3x mb-3 opacity-20"></i>
                    <h4 class="text-muted">Nhà hàng đang yên tĩnh</h4>
                    <p class="small text-muted">Hiện tại không có bàn nào đang hoạt động.</p>
                </div>
            <?php else: ?>
                <div class="accordion custom-accordion" id="realtimeAccordion">
                    <?php foreach ($orders as $order):
                        $isClosed = ($order['status'] === 'closed');
                        ?>
                        <div class="accordion-item" id="order-row-<?= $order['id'] ?>">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-<?= $order['id'] ?>">
                                    <div class="acc-header-content">
                                        <div class="acc-id-circle">
                                            <i class="fas fa-chevron-right arrow"></i>
                                        </div>
                                        <div class="acc-main-info">
                                            <div class="acc-title"><?= e($order['full_name']) ?></div>
                                            <div class="acc-sub">
                                                <span class="badge <?= $isClosed ? 'badge-success' : 'badge-warning' ?>">
                                                    <?= $isClosed ? 'ĐÃ THANH TOÁN' : 'ĐANG PHỤC VỤ' ?>
                                                </span>
                                                <span class="acc-meta">
                                                    <i class="fas fa-clock"></i>
                                                    <?= $isClosed ? date('H:i', strtotime($order['closed_at'])) : date('H:i', strtotime($order['opened_at'])) ?>
                                                    <span class="mx-1">|</span>
                                                    <i class="fas fa-user-friends"></i> <?= e($order['guest_count']) ?> khách
                                                </span>
                                            </div>
                                        </div>
                                        <div class="acc-price-box">
                                            <div class="acc-price-label">TỔNG CỘNG</div>
                                            <div class="acc-price-val <?= $isClosed ? 'text-success' : 'text-gold' ?>">
                                                <?= formatPrice($order['total']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                                <div class="acc-actions">
                                    <button onclick="dismissOrder(<?= $order['id'] ?>)" class="dismiss-btn" title="Ẩn đơn này">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </div>
                            </div>

                            <div id="collapse-<?= $order['id'] ?>" class="accordion-collapse collapse"
                                data-bs-parent="#realtimeAccordion">
                                <div class="accordion-body">
                                    <div class="row g-4">
                                        <div class="col-lg-8">
                                            <div class="items-list-card">
                                                <div class="it-card-header">CHI TIẾT MÓN ĂN</div>
                                                <div class="table-responsive">
                                                    <table class="table table-dark-luxury m-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Món ăn / Ghi chú</th>
                                                                <th class="text-center">Số lượng</th>
                                                                <th class="text-end">Đơn giá</th>
                                                                <th class="text-end">Thành tiền</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($order['items'] as $it): ?>
                                                                <tr>
                                                                    <td>
                                                                        <div class="item-name"><?= e($it['item_name']) ?></div>
                                                                        <?php if ($it['note']): ?>
                                                                            <div class="item-note"><i
                                                                                    class="fas fa-comment-dots text-warning"></i>
                                                                                <?= e($it['note']) ?></div>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td class="text-center fw-bold">x<?= $it['quantity'] ?></td>
                                                                    <td class="text-end opacity-70">
                                                                        <?= formatPrice($it['item_price']) ?></td>
                                                                    <td class="text-end fw-bold text-white">
                                                                        <?= formatPrice($it['item_price'] * $it['quantity']) ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="info-sidebar-card">
                                                <div class="it-card-header">LỊCH SỬ & NHÂN VIÊN</div>
                                                <div class="info-row">
                                                    <span>Nhân viên phục vụ:</span>
                                                    <strong><?= e($order['waiter_name'] ?? 'N/A') ?></strong>
                                                </div>
                                                <div class="info-row">
                                                    <span>Số đợt gọi món:</span>
                                                    <span class="badge badge-info"><?= $order['rounds'] ?> đợt</span>
                                                </div>
                                                <div class="info-row">
                                                    <span>Khu vực bàn:</span>
                                                    <strong><?= e($order['table_area']) ?></strong>
                                                </div>
                                                <div class="info-row">
                                                    <span>Giờ mở bàn:</span>
                                                    <strong><?= date('H:i:s', strtotime($order['opened_at'])) ?></strong>
                                                </div>
                                                <hr class="my-3 opacity-10">
                                                <button onclick="dismissOrder(<?= $order['id'] ?>)" class="btn btn-gold w-100">
                                                    <i class="fas fa-archive me-2"></i> LƯU TRỮ VÀ ẨN DANH SÁCH
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Custom Realtime Styles using Variable from admin.css */
    .card-premium {
        border-top: 3px solid var(--gold);
    }

    .custom-accordion .accordion-item {
        background: transparent;
        border: none;
        margin-bottom: 0.75rem;
        border-radius: var(--radius) !important;
        overflow: hidden;
        border: 1px solid var(--border);
        transition: all 0.3s;
    }

    .custom-accordion .accordion-item:hover {
        border-color: var(--gold-dark);
        box-shadow: var(--shadow-gold);
    }

    .custom-accordion .accordion-header {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.02);
    }

    .custom-accordion .accordion-button {
        background: transparent !important;
        box-shadow: none !important;
        padding: 1.25rem;
        flex: 1;
    }

    .custom-accordion .accordion-button::after {
        display: none;
    }

    .acc-header-content {
        display: flex;
        align-items: center;
        width: 100%;
        gap: 1.5rem;
    }

    .acc-id-circle {
        width: 32px;
        height: 32px;
        background: var(--bg-muted);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .acc-id-circle .arrow {
        font-size: 0.8rem;
        color: var(--gold);
        transition: transform 0.3s;
    }

    .accordion-button:not(.collapsed) .arrow {
        transform: rotate(90deg);
    }

    .acc-main-info {
        flex: 1;
    }

    .acc-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.25rem;
    }

    .acc-sub {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .acc-meta {
        font-size: 0.85rem;
        color: var(--text-light);
    }

    .acc-price-box {
        text-align: right;
        min-width: 120px;
    }

    .acc-price-label {
        font-size: 0.65rem;
        color: var(--text-lighter);
        letter-spacing: 1px;
    }

    .acc-price-val {
        font-size: 1.25rem;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
    }

    .text-gold {
        color: var(--gold);
    }

    .acc-actions {
        padding: 0 1.25rem;
        border-left: 1px solid var(--border);
    }

    .dismiss-btn {
        background: none;
        border: none;
        color: var(--text-lighter);
        font-size: 1.5rem;
        cursor: pointer;
        transition: color 0.2s;
    }

    .dismiss-btn:hover {
        color: var(--success);
    }

    .accordion-body {
        background: rgba(255, 255, 255, 0.01);
        border-top: 1px solid var(--border);
        padding: 2rem !important;
    }

    .it-card-header {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--gold);
        letter-spacing: 1.5px;
        margin-bottom: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border-gold);
    }

    .items-list-card,
    .info-sidebar-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.5rem;
        height: 100%;
    }

    .item-name {
        font-weight: 600;
        color: #fff;
    }

    .item-note {
        font-size: 0.8rem;
        color: var(--danger);
        font-style: italic;
        margin-top: 0.2rem;
    }

    .table-dark-luxury th {
        border: none;
        padding-bottom: 1rem;
    }

    .table-dark-luxury td {
        vertical-align: middle;
        border-color: rgba(255, 255, 255, 0.05);
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }

    .info-row span {
        color: var(--text-light);
    }

    .badge-info {
        background: rgba(14, 165, 233, 0.1);
        color: var(--info);
        border: 1px solid rgba(14, 165, 233, 0.2);
    }
</style>

<script>
    let timerCount = 15;
    const reloadCountEl = document.getElementById('reloadCount');

    // Reuse existing functions but ensure UI is updated
    function dismissOrder(orderId) {
        if (!confirm('Bạn muốn lưu trữ và ẩn đơn này khỏi danh sách giám sát trực tiếp?')) return;

        fetch('<?= BASE_URL ?>/admin/realtime/dismiss', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ order_id: orderId })
        })
            .then(res => res.json())
            .then(res => {
                if (res.ok) {
                    const el = document.getElementById('order-row-' + orderId);
                    if (el) {
                        el.style.transform = 'scale(0.95)';
                        el.style.opacity = '0';
                        setTimeout(() => el.remove(), 300);
                    }
                }
            });
    }

    function refreshData() {
        const btn = document.querySelector('button[onclick="refreshData()"]');
        btn.innerHTML = '<i class="fas fa-sync fa-spin"></i> ĐANG TẢI...';

        fetch('<?= BASE_URL ?>/admin/realtime/data')
            .then(res => res.json())
            .then(res => {
                if (res.ok) {
                    // Note: Here you'd ideally use a front-end framework like Vue/React 
                    // for seamless updates. Since this is vanilla PHP, a full refresh 
                    // is cleaner for complex UI like this, or we'd have to rewrite renderList.
                    // For now, let's reload the page to apply the luxury styles correctly.
                    location.reload();
                }
                btn.innerHTML = '<i class="fas fa-redo"></i> CẬP NHẬT NGAY';
            })
            .catch(err => {
                console.error(err);
                btn.innerHTML = '<i class="fas fa-redo"></i> CẬP NHẬT NGAY';
            });
    }

    // Timer logic
    setInterval(() => {
        timerCount--;
        if (timerCount <= 0) {
            location.reload(); // Simple reload to maintain luxury UI
        }
        if (reloadCountEl) reloadCountEl.textContent = timerCount;
    }, 1000);
</script>