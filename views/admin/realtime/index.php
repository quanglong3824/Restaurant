<?php
// views/admin/realtime/index.php
?>

<div class="realtime-dashboard">
    <!-- Status Overview -->
    <div class="row" style="margin-bottom: 2rem;">
        <div class="col-md-3">
            <div class="card stat-card"
                style="background: linear-gradient(135deg, #d4af37, #b8860b); color: white; border: none;">
                <div class="card-body">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <p style="margin:0; opacity:0.8; font-size:0.85rem;">Bàn đang phục vụ</p>
                            <h2 style="margin:0; font-weight:800;"><?= $counts['occupied'] ?></h2>
                        </div>
                        <i class="fas fa-utensils" style="font-size:2rem; opacity:0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <p style="margin:0; color:var(--text-muted); font-size:0.85rem;">Bàn trống</p>
                            <h2 style="margin:0; font-weight:800; color:var(--success);"><?= $counts['available'] ?>
                            </h2>
                        </div>
                        <i class="fas fa-chair" style="font-size:2rem; color:var(--success); opacity:0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div style="text-align:right;">
                <p style="margin-bottom:0.5rem; font-size:0.85rem; color:var(--text-muted);">
                    <i class="fas fa-sync-alt fa-spin"></i> Tự động cập nhật sau: <span id="reloadCount">15</span>s
                </p>
                <button onclick="refreshData()" class="btn btn-outline btn-sm">
                    <i class="fas fa-redo"></i> Cập nhật ngay
                </button>
            </div>
        </div>
    </div>

    <!-- Active Tables Grid/List -->
    <div class="row" id="realtimeGrid">
        <?php if (empty($orders)): ?>
            <div class="col-12">
                <div class="card" style="padding: 3rem; text-align: center; border-style: dashed;">
                    <i class="fas fa-mug-hot" style="font-size: 3rem; color: var(--border); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--text-muted);">Hiện tại không có bàn nào đang hoạt động</h3>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order):
                $isClosed = ($order['status'] === 'closed');
                ?>
                <div class="col-md-6 col-xl-4 mb-4 order-card-wrapper" id="order-card-<?= $order['id'] ?>"
                    data-id="<?= $order['id'] ?>">
                    <div class="card h-100"
                        style="border-radius:15px; overflow:hidden; border: 1px solid <?= $isClosed ? 'var(--success)' : 'var(--border-gold)' ?>; position: relative;">
                        <!-- Dismiss Button -->
                        <button onclick="dismissOrder(<?= $order['id'] ?>)"
                            style="position: absolute; top: 10px; right: 10px; z-index: 10; border: none; background: rgba(0,0,0,0.1); width: 28px; height: 28px; border-radius: 50%; color: #666; cursor: pointer;">
                            <i class="fas fa-times"></i>
                        </button>

                        <div class="card-header"
                            style="background: <?= $isClosed ? '#f0fdf4' : '#fffcf0' ?>; border-bottom: 1px solid <?= $isClosed ? '#dcfce7' : 'var(--gold-light)' ?>; padding-right: 45px;">
                            <div>
                                <h4 style="margin:0; font-weight:800; color:<?= $isClosed ? '#15803d' : 'var(--gold-dark)' ?>;">
                                    <?= e($order['full_name']) ?>
                                    <?php if ($isClosed): ?>
                                        <span class="badge"
                                            style="background:#15803d; color:white; font-size:0.6rem; vertical-align:middle; margin-left:5px;">ĐÃ
                                            THANH TOÁN</span>
                                    <?php endif; ?>
                                </h4>
                                <small style="color:var(--text-muted);">
                                    <i class="fas fa-user-friends"></i> <?= e($order['guest_count']) ?> khách |
                                    <i class="fas fa-clock"></i>
                                    <?= $isClosed ? 'Dứt điểm: ' . date('H:i', strtotime($order['closed_at'])) : 'Mở lúc: ' . date('H:i', strtotime($order['opened_at'])) ?>
                                </small>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 1.25rem;">
                            <!-- Items list -->
                            <div class="realtime-items-list"
                                style="margin-bottom: 1.5rem; max-height: 200px; overflow-y: auto;">
                                <?php foreach ($order['items'] as $it): ?>
                                    <div
                                        style="display:flex; justify-content:space-between; margin-bottom: 8px; font-size: 0.9rem; padding-bottom: 5px; border-bottom: 1px dotted #eee;">
                                        <div style="flex:1;">
                                            <span style="font-weight:700;">x<?= $it['quantity'] ?></span> <?= e($it['item_name']) ?>
                                            <?php if ($it['note']): ?>
                                                <div style="font-size: 0.75rem; color: var(--danger); font-style: italic;">
                                                    <i class="fas fa-comment-dots"></i> <?= e($it['note']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div style="display:flex; justify-content:space-between; align-items:flex-end;">
                                <div style="color:var(--text-muted); font-size:0.8rem;">
                                    <i class="fas fa-user"></i> PV: <?= e($order['waiter_name'] ?? 'N/A') ?>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:0.8rem; color:var(--text-muted); font-weight:700;">TỔNG TIỀN</div>
                                    <div
                                        style="font-size:1.4rem; font-weight:800; color:<?= $isClosed ? '#15803d' : 'var(--danger)' ?>;">
                                        <?= formatPrice($order['total']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($isClosed): ?>
                            <div class="card-footer"
                                style="padding: 0.75rem 1.25rem; background:#f8f9fa; display: flex; gap: 0.5rem;">
                                <button onclick="dismissOrder(<?= $order['id'] ?>)" class="btn btn-sm btn-success"
                                    style="flex: 1; justify-content:center;">
                                    <i class="fas fa-check"></i> Hoàn tất / Đóng
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
    .order-card-wrapper {
        transition: all 0.3s ease;
    }

    .badge-info {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .realtime-items-list::-webkit-scrollbar {
        width: 4px;
    }

    .realtime-items-list::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 10px;
    }
</style>

<script>
    let timerCount = 15;
    const reloadCountEl = document.getElementById('reloadCount');

    function dismissOrder(orderId) {
        if (!confirm('Bạn muốn ẩn thẻ này khỏi danh sách giám sát?')) return;
        
        fetch('<?= BASE_URL ?>/admin/realtime/dismiss', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ order_id: orderId })
        })
        .then(res => res.json())
        .then(res => {
            if (res.ok) {
                const el = document.getElementById('order-card-' + orderId);
                if (el) {
                    el.style.opacity = '0';
                    el.style.transform = 'scale(0.9)';
                    setTimeout(() => el.remove(), 300);
                }
            }
        });
    }

    function refreshData() {
        fetch('<?= BASE_URL ?>/admin/realtime/data')
            .then(res => res.json())
            .then(res => {
                if (res.ok) {
                    renderGrid(res.data);
                    timerCount = 15;
                }
            })
            .catch(err => console.error(err));
    }

    function renderGrid(orders) {
        const grid = document.getElementById('realtimeGrid');
        if (!orders || orders.length === 0) {
            grid.innerHTML = '<div class="col-12"><div class="card" style="padding: 3rem; text-align: center; border-style: dashed;"><i class="fas fa-mug-hot" style="font-size: 3rem; color: var(--border); margin-bottom: 1rem;"></i><h3 style="color: var(--text-muted);">Hiện tại không có bàn nào đang hoạt động</h3></div></div>';
            return;
        }

        let html = '';
        orders.forEach(order => {
            const isClosed = (order.status === 'closed');
            let itemsHtml = '';
            order.items.forEach(it => {
                itemsHtml += `
                    <div style="display:flex; justify-content:space-between; margin-bottom: 8px; font-size: 0.9rem; padding-bottom: 5px; border-bottom: 1px dotted #eee;">
                        <div style="flex:1;">
                            <span style="font-weight:700;">x${it.quantity}</span> ${it.item_name}
                            ${it.note ? `<div style="font-size: 0.75rem; color: var(--danger); font-style: italic;"><i class="fas fa-comment-dots"></i> ${it.note}</div>` : ''}
                        </div>
                    </div>`;
            });

            const headerBg = isClosed ? '#f0fdf4' : '#fffcf0';
            const headerBorder = isClosed ? '#dcfce7' : 'var(--gold-light)';
            const titleColor = isClosed ? '#15803d' : 'var(--gold-dark)';
            const totalColor = isClosed ? '#15803d' : 'var(--danger)';
            const statusLabel = isClosed ? `<span class="badge" style="background:#15803d; color:white; font-size:0.6rem; vertical-align:middle; margin-left:5px;">ĐÃ THANH TOÁN</span>` : '';
            const clockIcon = isClosed ? 'Dứt điểm: ' : 'Mở lúc: ';
            const clockTime = isClosed ? order.closed_at_fmt : order.opened_at_fmt; // Need to update controller to pass closed_at_fmt

            html += `
                <div class="col-md-6 col-xl-4 mb-4 order-card-wrapper" id="order-card-${order.id}" data-id="${order.id}">
                    <div class="card h-100" style="border-radius:15px; overflow:hidden; border: 1px solid ${isClosed ? 'var(--success)' : 'var(--border-gold)'}; position: relative;">
                        <button onclick="dismissOrder(${order.id})" 
                                style="position: absolute; top: 10px; right: 10px; z-index: 10; border: none; background: rgba(0,0,0,0.1); width: 28px; height: 28px; border-radius: 50%; color: #666; cursor: pointer;">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="card-header" style="background: ${headerBg}; border-bottom: 1px solid ${headerBorder}; padding-right: 45px;">
                            <div>
                                <h4 style="margin:0; font-weight:800; color:${titleColor};">
                                    ${order.full_name} ${statusLabel}
                                </h4>
                                <small style="color:var(--text-muted);">
                                    <i class="fas fa-user-friends"></i> ${order.guest_count} khách | 
                                    <i class="fas fa-clock"></i> ${clockIcon} ${order.opened_at_fmt}
                                </small>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 1.25rem;">
                            <div class="realtime-items-list" style="margin-bottom: 1.5rem; max-height: 200px; overflow-y: auto;">
                                ${itemsHtml}
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:flex-end;">
              <div style="color:var(--text-muted); font-size:0.8rem;">
                                    <i class="fas fa-user"></i> PV: ${order.waiter_name || 'N/A'}
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:0.8rem; color:var(--text-muted); font-weight:700;">TỔNG TIỀN</div>
                                    <div style="font-size:1.4rem; font-weight:800; color:${totalColor};">${new Intl.NumberFormat('vi-VN').format(order.total)}₫</div>
                                </div>
                            </div>
                        </div>
                        ${isClosed ? `
                        <div class="card-footer" style="padding: 0.75rem 1.25rem; background:#f8f9fa; display: flex; gap: 0.5rem;">
                            <button onclick="dismissOrder(${order.id})" class="btn btn-sm btn-success" style="flex: 1; justify-content:center;"><i class="fas fa-check"></i> Hoàn tất / Đóng</button>
                        </div>` : ''}
                    </div>
                </div>`;
        });
        grid.innerHTML = html;
    }

    // Timer logic
    setInterval(() => {
        timerCount--;
        if (timerCount <= 0) {
            refreshData();
        }
        if (reloadCountEl) reloadCountEl.textContent = timerCount;
    }, 1000);
</script>