<?php
// views/admin/realtime/index.php — Professional POS-Style Monitoring
?>

<div class="pos-monitor">
    <!-- Top Command Bar -->
    <div class="command-bar">
        <div class="brand-unit">
            <span class="unit-code">UNIT-01</span>
            <h1 class="unit-name">REAL-TIME MONITOR</h1>
        </div>
        
        <div class="system-stats">
            <div class="stat-item">
                <span class="label">ĐANG PHỤC VỤ</span>
                <span class="value highlight" id="statOccupied"><?= $counts['occupied'] ?></span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="label">BÀN TRỐNG</span>
                <span class="value" id="statAvailable"><?= $counts['available'] ?></span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="label">DOANH THU TẠM TÍNH</span>
                <span class="value gold" id="statTempRevenue">...</span>
            </div>
        </div>

        <div class="sync-box">
            <div class="sync-timer">
                <span id="reloadCount">8</span>s
            </div>
            <button onclick="refreshData()" class="refresh-circle-btn">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <!-- Monitoring Grid -->
    <div id="realtimeListContainer" class="pos-grid">
        <!-- Loader -->
        <div class="pos-loader">
            <div class="spinner-border spinner-border-sm"></div>
            <span>Đang đồng bộ trạm dữ liệu...</span>
        </div>
    </div>
</div>

<style>
    /* ── Root & Variables ─────────────────────────────────────── */
    :root {
        --pos-bg: #0f172a;
        --pos-card: #1e293b;
        --pos-border: #334155;
        --pos-accent: #d4af37;
        --pos-text: #f1f5f9;
        --pos-text-muted: #94a3b8;
        --pos-success: #10b981;
        --pos-warning: #f59e0b;
    }

    body { background-color: var(--pos-bg); color: var(--pos-text); font-family: 'Inter', -apple-system, sans-serif; }

    /* ── Command Bar ────────────────────────────────────────── */
    .command-bar {
        display: flex; justify-content: space-between; align-items: center;
        background: #020617; padding: 15px 30px; border-bottom: 1px solid var(--pos-border);
        position: sticky; top: 0; z-index: 100;
    }
    .brand-unit { display: flex; flex-direction: column; }
    .unit-code { font-size: 0.65rem; font-weight: 800; color: var(--pos-accent); letter-spacing: 2px; }
    .unit-name { font-size: 1.1rem; font-weight: 700; margin: 0; color: #fff; }

    .system-stats { display: flex; align-items: center; gap: 30px; }
    .stat-item { display: flex; flex-direction: column; align-items: center; }
    .stat-item .label { font-size: 0.6rem; font-weight: 700; color: var(--pos-text-muted); text-transform: uppercase; margin-bottom: 2px; }
    .stat-item .value { font-size: 1.3rem; font-weight: 800; color: #fff; }
    .stat-item .value.highlight { color: var(--pos-warning); }
    .stat-item .value.gold { color: var(--pos-accent); }
    .stat-divider { width: 1px; height: 30px; background: var(--pos-border); }

    .sync-box { display: flex; align-items: center; gap: 12px; }
    .sync-timer { 
        width: 35px; height: 35px; border-radius: 50%; border: 2px solid var(--pos-border);
        display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800;
    }
    .refresh-circle-btn {
        background: var(--pos-card); border: 1px solid var(--pos-border); color: var(--pos-text);
        width: 35px; height: 35px; border-radius: 50%; cursor: pointer; transition: all 0.2s;
    }
    .refresh-circle-btn:hover { background: var(--pos-accent); color: #000; border-color: var(--pos-accent); }

    /* ── POS Grid ────────────────────────────────────────────── */
    .pos-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px; padding: 25px;
    }
    .pos-loader { grid-column: 1/-1; text-align: center; padding: 100px; color: var(--pos-text-muted); display: flex; flex-direction: column; gap: 15px; align-items: center; }

    /* ── POS Card ────────────────────────────────────────────── */
    .pos-card {
        background: var(--pos-card); border: 1px solid var(--pos-border);
        border-radius: 12px; overflow: hidden; display: flex; flex-direction: column;
        transition: transform 0.2s, border-color 0.2s;
    }
    .pos-card:hover { border-color: var(--pos-text-muted); }
    
    .card-header-pos {
        padding: 15px 20px; background: rgba(0,0,0,0.2);
        display: flex; justify-content: space-between; align-items: flex-start;
        border-bottom: 1px solid var(--pos-border);
    }
    .table-main-info h2 { font-size: 1.25rem; font-weight: 800; margin: 0; color: #fff; }
    .table-sub-info { font-size: 0.75rem; color: var(--pos-text-muted); margin-top: 4px; display: flex; gap: 10px; }
    
    .status-tag { padding: 4px 10px; border-radius: 4px; font-size: 0.6rem; font-weight: 800; text-transform: uppercase; }
    .status-tag.open { background: var(--pos-warning); color: #000; }
    .status-tag.closed { background: var(--pos-success); color: #fff; }

    .card-body-pos { padding: 0; flex: 1; overflow-y: auto; max-height: 300px; }
    .pos-table { width: 100%; border-collapse: collapse; }
    .pos-table th { 
        position: sticky; top: 0; background: #263349; 
        padding: 8px 20px; font-size: 0.65rem; color: var(--pos-text-muted);
        text-align: left; text-transform: uppercase; border-bottom: 1px solid var(--pos-border);
    }
    .pos-table td { padding: 10px 20px; border-bottom: 1px solid rgba(255,255,255,0.03); font-size: 0.85rem; }
    
    .item-title { font-weight: 600; color: #fff; }
    .item-note { font-size: 0.75rem; color: var(--pos-warning); margin-top: 2px; display: block; }
    .qty-badge { font-weight: 800; color: var(--pos-accent); }
    .price-col { text-align: right; color: var(--pos-text-muted); font-family: 'Monaco', monospace; }

    .card-footer-pos {
        padding: 15px 20px; background: rgba(0,0,0,0.1);
        border-top: 1px solid var(--pos-border);
    }
    .total-summary { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 12px; }
    .summary-left { display: flex; flex-direction: column; gap: 2px; }
    .summary-left span:first-child { font-size: 0.6rem; color: var(--pos-text-muted); font-weight: 700; text-transform: uppercase; }
    .summary-left span:last-child { font-size: 0.9rem; font-weight: 700; color: #fff; }
    
    .summary-right { text-align: right; }
    .total-label { font-size: 0.65rem; color: var(--pos-text-muted); font-weight: 700; display: block; margin-bottom: 2px; }
    .total-value { font-size: 1.2rem; font-weight: 900; color: var(--pos-accent); }

    .action-row { display: flex; gap: 10px; }
    .btn-pos {
        flex: 1; padding: 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700;
        cursor: pointer; transition: all 0.2s; border: none;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-pos-dim { background: #334155; color: #cbd5e1; }
    .btn-pos-dim:hover { background: #475569; color: #fff; }
    
    @media (max-width: 768px) {
        .system-stats { display: none; }
        .pos-grid { grid-template-columns: 1fr; }
    }
</style>

<script>
    let timerCount = 8;
    let isRefreshing = false;

    async function refreshData() {
        if (isRefreshing) return;
        isRefreshing = true;

        const btn = document.querySelector('.refresh-circle-btn');
        if (btn) btn.innerHTML = '<i class="fas fa-sync fa-spin"></i>';

        try {
            const res = await fetch('<?= BASE_URL ?>/admin/realtime/data?t=' + Date.now());
            const data = await res.json();
            
            if (data.ok) {
                updateStats(data);
                renderPOSGrid(data.data);
            }
        } catch (err) {
            console.error('Lỗi POS Sync:', err);
        } finally {
            if (btn) btn.innerHTML = '<i class="fas fa-sync-alt"></i>';
            isRefreshing = false;
            timerCount = 8;
        }
    }

    function updateStats(data) {
        document.getElementById('statOccupied').textContent = data.counts.occupied;
        document.getElementById('statAvailable').textContent = data.counts.available;
        
        let tempTotal = 0;
        data.data.forEach(o => { if (o.status === 'open') tempTotal += parseFloat(o.total || 0); });
        document.getElementById('statTempRevenue').textContent = new Intl.NumberFormat('vi-VN').format(tempTotal) + 'đ';
    }

    function renderPOSGrid(orders) {
        const container = document.getElementById('realtimeListContainer');
        if (orders.length === 0) {
            container.innerHTML = `
                <div class="pos-loader">
                    <i class="fas fa-coffee fa-3x mb-3 opacity-20"></i>
                    <h3>KHÔNG CÓ DỮ LIỆU</h3>
                    <p class="small text-muted">Hệ thống đang chờ đơn hàng mới từ khách hàng.</p>
                </div>
            `;
            return;
        }

        let html = '';
        orders.forEach(order => {
            const isClosed = (order.status === 'closed');
            const statusTag = isClosed ? 'closed' : 'open';
            const statusText = isClosed ? 'Đã thanh toán' : 'Đang ăn';
            
            let rows = '';
            order.items.forEach(it => {
                rows += `
                    <tr>
                        <td>
                            <span class="item-title">${it.item_name}</span>
                            ${it.note ? `<span class="item-note">${it.note}</span>` : ''}
                        </td>
                        <td class="qty-badge">x${it.quantity}</td>
                        <td class="price-col">${it.subtotal_fmt}</td>
                    </tr>
                `;
            });

            html += `
                <div class="pos-card" id="card-${order.id}">
                    <div class="card-header-pos">
                        <div class="table-main-info">
                            <h2>${order.full_name}</h2>
                            <div class="table-sub-info">
                                <span><i class="fas fa-user-friends"></i> ${order.guest_count}</span>
                                <span><i class="fas fa-user-tie"></i> ${order.waiter_name || 'System'}</span>
                            </div>
                        </div>
                        <div class="status-tag ${statusTag}">${statusText}</div>
                    </div>
                    
                    <div class="card-body-pos">
                        <table class="pos-table">
                            <thead>
                                <tr>
                                    <th>Món</th>
                                    <th>SL</th>
                                    <th style="text-align:right">Tiền</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer-pos">
                        <div class="total-summary">
                            <div class="summary-left">
                                <span>Bắt đầu</span>
                                <span>${order.opened_at_fmt}</span>
                            </div>
                            <div class="summary-right">
                                <span class="total-label">TỔNG CỘNG</span>
                                <span class="total-value">${order.total_fmt}</span>
                            </div>
                        </div>
                        <div class="action-row">
                            <button onclick="dismissOrder(${order.id})" class="btn-pos btn-pos-dim">
                                <i class="fas fa-check"></i> XỬ LÝ XONG & ẨN
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }

    async function dismissOrder(id) {
        try {
            const fd = new FormData();
            fd.append('order_id', id);
            const res = await fetch('<?= BASE_URL ?>/admin/realtime/dismiss', { method: 'POST', body: fd });
            if ((await res.json()).ok) {
                const card = document.getElementById(`card-${id}`);
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => refreshData(), 200);
                }
            }
        } catch (err) { console.error(err); }
    }

    setInterval(() => {
        timerCount--;
        if (timerCount <= 0) refreshData();
        const el = document.getElementById('reloadCount');
        if (el) el.textContent = timerCount;
    }, 1000);

    refreshData();
</script>
