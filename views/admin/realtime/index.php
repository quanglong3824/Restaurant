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
                            <p style="margin:0; opacity:0.8; font-size:0.85rem;">Bàn đang ăn</p>
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

    <!-- Active Orders List (Accordion Style) -->
    <div class="card" style="border-radius: 12px; overflow: hidden; border: 1px solid var(--border);">
        <div class="card-header" style="background: #f8f9fa; font-weight: 800; padding: 1rem 1.5rem;">
            DANH SÁCH GIÁM SÁT TRỰC TIẾP
        </div>
        <div class="card-body p-0">
            <div id="realtimeListContainer">
                <?php if (empty($orders)): ?>
                    <div style="padding: 3rem; text-align: center;">
                        <i class="fas fa-coffee" style="font-size: 2.5rem; color: var(--border); margin-bottom: 1rem;"></i>
                        <p style="color: var(--text-muted); margin:0;">Hiện tại không có bàn nào cần giám sát.</p>
                    </div>
                <?php else: ?>
                    <div class="accordion accordion-flush" id="realtimeAccordion">
                        <?php foreach ($orders as $index => $order): 
                            $isClosed = ($order['status'] === 'closed');
                        ?>
                            <div class="accordion-item" id="order-row-<?= $order['id'] ?>" style="border-bottom: 1px solid #eee;">
                                <div class="accordion-header d-flex align-items-center" style="padding: 10px 15px;">
                                    <button class="accordion-button collapsed p-0" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapse-<?= $order['id'] ?>" 
                                            style="background:none; border:none; box-shadow:none; width: auto; flex: 1; text-align: left; display: flex; align-items:center;">
                                        
                                        <div style="width: 40px; text-align: center; margin-right: 15px;">
                                            <i class="fas fa-chevron-right arrow-icon" style="transition: transform 0.2s;"></i>
                                        </div>

                                        <div style="flex: 1;">
                                            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                                <span style="font-weight: 800; font-size: 1.1rem; color: var(--dark);"><?= e($order['full_name']) ?></span>
                                                
                                                <?php if ($isClosed): ?>
                                                    <span class="badge" style="background:#15803d; color:white; font-size:0.7rem;">ĐÃ THANH TOÁN</span>
                                                <?php else: ?>
                                                    <span class="badge" style="background:#eab308; color:white; font-size:0.7rem;">CHƯA THANH TOÁN</span>
                                                <?php endif; ?>

                                                <span style="font-size: 0.85rem; color: var(--text-muted);">
                                                    <i class="fas fa-user-friends"></i> <?= e($order['guest_count']) ?> |
                                                    <i class="fas fa-clock"></i> <?= $isClosed ? 'Xong: '.date('H:i', strtotime($order['closed_at'])) : 'Vào: '.date('H:i', strtotime($order['opened_at'])) ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div style="text-align: right; margin: 0 20px;">
                                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700;">TỔNG TIỀN</div>
                                            <div style="font-weight: 800; color: <?= $isClosed ? '#15803d' : 'var(--danger)' ?>; font-size: 1.1rem;">
                                                <?= formatPrice($order['total']) ?>
                                            </div>
                                        </div>
                                    </button>

                                    <!-- Quick Dismiss Button (Always visible) -->
                                    <button onclick="dismissOrder(<?= $order['id'] ?>)" 
                                            class="btn btn-sm btn-ghost" 
                                            style="color: var(--text-muted); padding: 5px 10px;"
                                            title="Ẩn khỏi danh sách">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </div>

                                <div id="collapse-<?= $order['id'] ?>" class="accordion-collapse collapse" data-bs-parent="#realtimeAccordion">
                                    <div class="accordion-body" style="background: #fafafa; border-top: 1px solid #f0f0f0; padding: 1.5rem;">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h6 style="font-weight: 800; color: var(--gold-dark); border-bottom: 2px solid var(--gold-light); display: inline-block; padding-bottom: 3px; margin-bottom: 1rem;">
                                                    DANH SÁCH MÓN ĐÃ GỌI
                                                </h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-borderless">
                                                        <thead>
                                                            <tr style="font-size: 0.75rem; color: var(--text-muted); border-bottom: 1px solid #eee;">
                                                                <th>TÊN MÓN</th>
                                                                <th style="text-align: center;">S.L</th>
                                                                <th style="text-align: right;">ĐƠN GIÁ</th>
                                                                <th style="text-align: right;">THÀNH TIỀN</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($order['items'] as $it): ?>
                                                                <tr style="font-size: 0.9rem; border-bottom: 1px dotted #eee;">
                                                                    <td>
                                                                        <div style="font-weight: 700;"><?= e($it['item_name']) ?></div>
                                                                        <?php if ($it['note']): ?>
                                                                            <div style="font-size: 0.8rem; color: var(--danger); font-style: italic;">
                                                                                <i class="fas fa-comment-dots"></i> <?= e($it['note']) ?>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td style="text-align: center; font-weight: 700;">x<?= $it['quantity'] ?></td>
                                                                    <td style="text-align: right;"><?= formatPrice($it['item_price'] / 1000) ?>k</td>
                                                                    <td style="text-align: right; font-weight: 700;"><?= formatPrice(($it['item_price'] * $it['quantity'])) ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div style="background: white; padding: 1rem; border-radius: 8px; border: 1px solid #eee;">
                                                    <h6 style="font-weight: 800; margin-bottom: 1rem;">THÔNG TIN PHỤ</h6>
                                                    <p style="font-size: 0.85rem; margin-bottom: 8px;">
                                                        <span style="color: var(--text-muted);">Nhân viên:</span> 
                                                        <span style="font-weight: 700;"><?= e($order['waiter_name'] ?? 'N/A') ?></span>
                                                    </p>
                                                    <p style="font-size: 0.85rem; margin-bottom: 8px;">
                                                        <span style="color: var(--text-muted);">Đợt gọi món:</span> 
                                                        <span class="badge badge-info" style="font-weight: 800;"><?= $order['rounds'] ?> đợt</span>
                                                    </p>
                                                    <p style="font-size: 0.85rem; margin-bottom: 8px;">
                                                        <span style="color: var(--text-muted);">Khu vực:</span> 
                                                        <span style="font-weight: 700;"><?= e($order['table_area']) ?></span>
                                                    </p>
                                                    <div style="margin-top: 1rem;">
                                                        <button onclick="dismissOrder(<?= $order['id'] ?>)" class="btn btn-sm btn-success style="width: 100%;"">
                                                            <i class="fas fa-check"></i> Xác nhận Hoàn tất & Đóng
                                                        </button>
                                                    </div>
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
</div>

<style>
    .accordion-button::after { display: none; }
    .accordion-button:not(.collapsed) .arrow-icon { transform: rotate(90deg); }
    .accordion-item:hover { background-color: #fdfdfd; }
    .badge-info { background: #e0f2fe; color: #0369a1; }
    .btn-ghost:hover { background: #f0f0f0; color: var(--danger); }
</style>

<script>
    let timerCount = 15;
    const reloadCountEl = document.getElementById('reloadCount');

    function dismissOrder(orderId) {
        if (!confirm('Bạn muốn ẩn đơn này khỏi danh sách giám sát vĩnh viễn?')) return;
        
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
                    el.style.opacity = '0';
                    el.style.backgroundColor = '#fef2f2';
                    setTimeout(() => el.remove(), 400);
                }
            }
        });
    }

    function refreshData() {
        fetch('<?= BASE_URL ?>/admin/realtime/data')
            .then(res => res.json())
            .then(res => {
                if (res.ok) {
                    renderList(res.data);
                    timerCount = 15;
                }
            })
            .catch(err => console.error(err));
    }

    function renderList(orders) {
        const container = document.getElementById('realtimeListContainer');
        if (!orders || orders.length === 0) {
            container.innerHTML = '<div style="padding: 3rem; text-align: center;"><i class="fas fa-coffee" style="font-size: 2.5rem; color: var(--border); margin-bottom: 1rem;"></i><p style="color: var(--text-muted); margin:0;">Hiện tại không có bàn nào cần giám sát.</p></div>';
            return;
        }

        // Reuse accordion structure
        let html = '<div class="accordion accordion-flush" id="realtimeAccordion">';
        orders.forEach(order => {
            const isClosed = (order.status === 'closed');
            const statusBadge = isClosed ? 
                '<span class="badge" style="background:#15803d; color:white; font-size:0.7rem;">ĐÃ THANH TOÁN</span>' : 
                '<span class="badge" style="background:#eab308; color:white; font-size:0.7rem;">CHƯA THANH TOÁN</span>';
            const priceColor = isClosed ? '#15803d' : 'var(--danger)';
            const timeText = isClosed ? `Xong: ${order.closed_at_fmt}` : `Vào: ${order.opened_at_fmt}`;

            let itemsRows = '';
            order.items.forEach(it => {
                itemsRows += `
                    <tr style="font-size: 0.9rem; border-bottom: 1px dotted #eee;">
                        <td>
                            <div style="font-weight: 700;">${it.item_name}</div>
                            ${it.note ? `<div style="font-size: 0.8rem; color: var(--danger); font-style: italic;"><i class="fas fa-comment-dots"></i> ${it.note}</div>` : ''}
                        </td>
                        <td style="text-align: center; font-weight: 700;">x${it.quantity}</td>
                        <td style="text-align: right;">${new Intl.NumberFormat('vi-VN').format(it.item_price / 1000)}k</td>
                        <td style="text-align: right; font-weight: 700;">${new Intl.NumberFormat('vi-VN').format(it.item_price * it.quantity)}₫</td>
                    </tr>`;
            });

            html += `
                <div class="accordion-item" id="order-row-${order.id}" style="border-bottom: 1px solid #eee;">
                    <div class="accordion-header d-flex align-items-center" style="padding: 10px 15px;">
                        <button class="accordion-button collapsed p-0" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#collapse-${order.id}" 
                                style="background:none; border:none; box-shadow:none; width: auto; flex: 1; text-align: left; display: flex; align-items:center;">
                            <div style="width: 40px; text-align: center; margin-right: 15px;">
                                <i class="fas fa-chevron-right arrow-icon" style="transition: transform 0.2s;"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    <span style="font-weight: 800; font-size: 1.1rem; color: var(--dark);">${order.full_name}</span>
                                    ${statusBadge}
                                    <span style="font-size: 0.85rem; color: var(--text-muted);">
                                        <i class="fas fa-user-friends"></i> ${order.guest_count} |
                                        <i class="fas fa-clock"></i> ${timeText}
                                    </span>
                                </div>
                            </div>
                            <div style="text-align: right; margin: 0 20px;">
                                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700;">TỔNG TIỀN</div>
                                <div style="font-weight: 800; color: ${priceColor}; font-size: 1.1rem;">
                                    ${new Intl.NumberFormat('vi-VN').format(order.total)}₫
                                </div>
                            </div>
                        </button>
                        <button onclick="dismissOrder(${order.id})" class="btn btn-sm btn-ghost" style="color: var(--text-muted); padding: 5px 10px;"><i class="fas fa-check-circle"></i></button>
                    </div>
                    <div id="collapse-${order.id}" class="accordion-collapse collapse" data-bs-parent="#realtimeAccordion">
                        <div class="accordion-body" style="background: #fafafa; border-top: 1px solid #f0f0f0; padding: 1.5rem;">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 style="font-weight: 800; color: var(--gold-dark); border-bottom: 2px solid var(--gold-light); display: inline-block; padding-bottom: 3px; margin-bottom: 1rem;">DANH SÁCH MÓN ĐÃ GỌI</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless">
                                            <thead>
                                                <tr style="font-size: 0.75rem; color: var(--text-muted); border-bottom: 1px solid #eee;">
                                                    <th>TÊN MÓN</th>
                                                    <th style="text-align: center;">S.L</th>
                                                    <th style="text-align: right;">ĐƠN GIÁ</th>
                                                    <th style="text-align: right;">THÀNH TIỀN</th>
                                                </tr>
                                            </thead>
                                            <tbody>${itemsRows}</tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div style="background: white; padding: 1rem; border-radius: 8px; border: 1px solid #eee;">
                                        <h6 style="font-weight: 800; margin-bottom: 1rem;">THÔNG TIN PHỤ</h6>
                                        <p style="font-size: 0.85rem; margin-bottom: 8px;"><span style="color: var(--text-muted);">Nhân viên:</span> <span style="font-weight: 700;">${order.waiter_name || 'N/A'}</span></p>
                                        <p style="font-size: 0.85rem; margin-bottom: 8px;"><span style="color: var(--text-muted);">Đợt gọi món:</span> <span class="badge badge-info" style="font-weight: 800;">${order.rounds} đợt</span></p>
                                        <p style="font-size: 0.85rem; margin-bottom: 8px;"><span style="color: var(--text-muted);">Khu vực:</span> <span style="font-weight: 700;">${order.table_area}</span></p>
                                        <div style="margin-top: 1rem;"><button onclick="dismissOrder(${order.id})" class="btn btn-sm btn-success" style="width: 100%;"><i class="fas fa-check"></i> Xác nhận Hoàn tất & Đóng</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        });
        html += '</div>';
        container.innerHTML = html;
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