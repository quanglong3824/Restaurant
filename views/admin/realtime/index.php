<?php
// views/admin/realtime/index.php — Premium Real-time Monitoring Dashboard
?>

<div class="realtime-dashboard">
    <!-- Header System -->
    <div class="dashboard-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="playfair mb-1 text-white"><i class="fas fa-satellite-dish me-2 text-gold"></i> TRUNG TÂM ĐIỀU HÀNH</h1>
                <p class="text-muted small mb-0">Giám sát hoạt động phục vụ trực tiếp tại nhà hàng</p>
            </div>
            <div class="sync-status">
                <div class="sync-indicator">
                    <div class="pulse-dot"></div>
                    <span>Dữ liệu trực tiếp</span>
                </div>
                <div class="next-sync">
                    Làm mới sau <span id="reloadCount" class="fw-bold">8</span>s
                </div>
                <button onclick="refreshData()" class="btn btn-gold-outline btn-sm ms-3">
                    <i class="fas fa-sync-alt"></i> CẬP NHẬT
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="admin-stat-card primary">
                <div class="stat-body">
                    <div class="stat-title">ĐANG PHỤC VỤ</div>
                    <div class="stat-value" id="statOccupied"><?= $counts['occupied'] ?></div>
                </div>
                <div class="stat-icon"><i class="fas fa-fire-alt"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-stat-card success">
                <div class="stat-body">
                    <div class="stat-title">BÀN TRỐNG</div>
                    <div class="stat-value" id="statAvailable"><?= $counts['available'] ?></div>
                </div>
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-stat-card warning">
                <div class="stat-body">
                    <div class="stat-title">TỔNG ĐƠN GẦN ĐÂY</div>
                    <div class="stat-value" id="statTotalOrders"><?= count($orders) ?></div>
                </div>
                <div class="stat-icon"><i class="fas fa-history"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-stat-card info">
                <div class="stat-body">
                    <div class="stat-title">DOANH THU TẠM TÍNH</div>
                    <div class="stat-value" id="statTempRevenue">...</div>
                </div>
                <div class="stat-icon"><i class="fas fa-wallet"></i></div>
            </div>
        </div>
    </div>

    <!-- Monitoring List Container -->
    <div id="realtimeListContainer" class="row g-4">
        <!-- Cards will be rendered here by JS -->
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-gold" role="status"></div>
            <p class="mt-3 text-muted">Đang kết nối hệ thống...</p>
        </div>
    </div>
</div>

<style>
    /* ── Dashboard Layout ─────────────────────────────────────── */
    .realtime-dashboard { color: #e2e8f0; }
    
    .sync-status { 
        background: rgba(30, 41, 59, 0.7); padding: 8px 15px; border-radius: 50px; 
        display: flex; align-items: center; border: 1px solid rgba(255,255,255,0.05);
        backdrop-filter: blur(10px);
    }
    .sync-indicator { display: flex; align-items: center; margin-right: 15px; font-size: 0.75rem; color: #10b981; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .pulse-dot { width: 8px; height: 8px; background: #10b981; border-radius: 50%; margin-right: 8px; box-shadow: 0 0 10px #10b981; animation: pulse-green 1.5s infinite; }
    @keyframes pulse-green { 0% { transform: scale(0.9); opacity: 0.7; } 50% { transform: scale(1.2); opacity: 1; } 100% { transform: scale(0.9); opacity: 0.7; } }
    .next-sync { font-size: 0.85rem; color: #94a3b8; }

    /* ── Stat Cards ────────────────────────────────────────── */
    .admin-stat-card {
        background: #1e293b; border-radius: 20px; padding: 25px; display: flex;
        justify-content: space-between; align-items: center; border: 1px solid rgba(255,255,255,0.05);
        position: relative; overflow: hidden; transition: all 0.3s;
    }
    .admin-stat-card::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: rgba(255,255,255,0.1); }
    .admin-stat-card.primary::after { background: #3b82f6; }
    .admin-stat-card.success::after { background: #10b981; }
    .admin-stat-card.warning::after { background: #f59e0b; }
    .admin-stat-card.info::after { background: #8b5cf6; }
    
    .stat-title { font-size: 0.7rem; font-weight: 800; color: #94a3b8; letter-spacing: 1px; margin-bottom: 5px; }
    .stat-value { font-size: 2rem; font-weight: 800; color: #fff; line-height: 1; }
    .stat-icon { font-size: 2.2rem; opacity: 0.15; transform: rotate(-15deg); }

    /* ── Table Monitoring Cards ────────────────────────────── */
    .monitoring-card {
        background: #1e293b; border-radius: 24px; border: 1px solid rgba(255,255,255,0.05);
        height: 100%; display: flex; flex-direction: column; transition: all 0.3s;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .monitoring-card:hover { transform: translateY(-5px); border-color: rgba(212, 175, 55, 0.3); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
    
    .m-card-header { padding: 20px 25px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: flex-start; }
    .m-table-info h3 { font-size: 1.4rem; font-weight: 800; color: #fff; margin-bottom: 4px; }
    .m-table-info p { font-size: 0.8rem; color: #94a3b8; margin: 0; }
    
    .m-status-badge { padding: 5px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; letter-spacing: 0.5px; }
    .m-status-badge.open { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
    .m-status-badge.closed { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }

    .m-card-body { padding: 0; flex: 1; }
    .m-items-table { width: 100%; border-collapse: collapse; }
    .m-items-table th { padding: 12px 25px; font-size: 0.65rem; color: #64748b; background: rgba(0,0,0,0.1); text-transform: uppercase; text-align: left; }
    .m-items-table td { padding: 10px 25px; border-bottom: 1px solid rgba(255,255,255,0.02); font-size: 0.9rem; }
    
    .item-name-box { font-weight: 600; color: #cbd5e1; }
    .item-sub { font-size: 0.75rem; color: #64748b; font-style: italic; display: block; }
    .item-qty { font-weight: 800; color: var(--gold); }
    .item-price { font-size: 0.8rem; font-weight: 600; color: #94a3b8; text-align: right; }

    .m-card-footer { padding: 20px 25px; background: rgba(0,0,0,0.1); border-radius: 0 0 24px 24px; }
    .footer-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .meta-pair { display: flex; flex-direction: column; }
    .meta-pair span:first-child { font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700; margin-bottom: 2px; }
    .meta-pair span:last-child { font-size: 0.95rem; font-weight: 800; color: #fff; }
    .meta-pair .total-val { color: var(--gold); font-size: 1.1rem; }

    .btn-dismiss { 
        background: rgba(255,255,255,0.05); color: #64748b; border: 1px solid rgba(255,255,255,0.1);
        width: 100%; padding: 12px; border-radius: 12px; font-weight: 700; transition: all 0.2s;
    }
    .btn-dismiss:hover { background: #10b981; color: white; border-color: #10b981; }

    /* Custom Scrollbar for items */
    .m-items-wrapper { max-height: 250px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.1) transparent; }
    .m-items-wrapper::-webkit-scrollbar { width: 4px; }
    .m-items-wrapper::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
</style>

<script>
    let timerCount = 8;
    let isRefreshing = false;

    async function refreshData() {
        if (isRefreshing) return;
        isRefreshing = true;

        const btn = document.querySelector('button[onclick="refreshData()"]');
        const icon = btn?.querySelector('i');
        if (icon) icon.className = 'fas fa-sync fa-spin';

        try {
            const res = await fetch('<?= BASE_URL ?>/admin/realtime/data?t=' + Date.now());
            const data = await res.json();
            
            if (data.ok) {
                updateOverview(data);
                renderRealtimeCards(data.data);
            }
        } catch (err) {
            console.error('Lỗi đồng bộ Dashboard:', err);
        } finally {
            if (icon) icon.className = 'fas fa-sync-alt';
            isRefreshing = false;
            timerCount = 8;
        }
    }

    function updateOverview(data) {
        document.getElementById('statOccupied').textContent = data.counts.occupied;
        document.getElementById('statAvailable').textContent = data.counts.available;
        document.getElementById('statTotalOrders').textContent = data.data.length;
        
        let tempTotal = 0;
        data.data.forEach(o => {
            if (o.status === 'open') tempTotal += parseFloat(o.total || 0);
        });
        document.getElementById('statTempRevenue').textContent = new Intl.NumberFormat('vi-VN').format(tempTotal) + 'đ';
    }

    function renderRealtimeCards(orders) {
        const container = document.getElementById('realtimeListContainer');
        if (orders.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="empty-state-card p-5" style="background:rgba(30, 41, 59, 0.5); border-radius:30px;">
                        <i class="fas fa-mug-hot fa-4x mb-4 text-gold opacity-20"></i>
                        <h3 class="playfair">Không có hoạt động</h3>
                        <p class="text-muted">Nhà hàng hiện đang trống hoặc chưa có đơn hàng mới.</p>
                    </div>
                </div>
            `;
            return;
        }

        let html = '';
        orders.forEach(order => {
            const isClosed = (order.status === 'closed');
            const statusClass = isClosed ? 'closed' : 'open';
            const statusText = isClosed ? 'ĐÃ THANH TOÁN' : 'ĐANG PHỤC VỤ';
            
            let itemsRows = '';
            order.items.forEach(it => {
                itemsRows += `
                    <tr>
                        <td>
                            <div class="item-name-box">${it.item_name}</div>
                            ${it.note ? `<span class="item-sub"><i class="fas fa-comment-dots me-1"></i>${it.note}</span>` : ''}
                        </td>
                        <td class="text-center item-qty">x${it.quantity}</td>
                        <td class="item-price">${it.subtotal_fmt}</td>
                    </tr>
                `;
            });

            html += `
                <div class="col-xl-4 col-lg-6" id="order-card-${order.id}">
                    <div class="monitoring-card ${statusClass}">
                        <div class="m-card-header">
                            <div class="m-table-info">
                                <h3>${order.full_name}</h3>
                                <p><i class="fas fa-user-friends me-1"></i> ${order.guest_count} khách | <i class="fas fa-user-tie me-1"></i> ${order.waiter_name || 'N/A'}</p>
                            </div>
                            <div class="m-status-badge ${statusClass}">${statusText}</div>
                        </div>
                        
                        <div class="m-card-body">
                            <table class="m-items-table">
                                <thead>
                                    <tr>
                                        <th>Món ăn</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-right">T.Tiền</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="m-items-wrapper">
                                <table class="m-items-table">
                                    <tbody>${itemsRows}</tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="m-card-footer">
                            <div class="footer-meta">
                                <div class="meta-pair">
                                    <span>Bắt đầu lúc</span>
                                    <span>${order.opened_at_fmt}</span>
                                </div>
                                <div class="meta-pair text-end">
                                    <span>Tổng cộng</span>
                                    <span class="total-val">${order.total_fmt}</span>
                                </div>
                            </div>
                            <button onclick="dismissOrder(${order.id})" class="btn-dismiss">
                                <i class="fas fa-check-double me-2"></i> LƯU TRỮ VÀ ẨN
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }

    async function dismissOrder(id) {
        if (!confirm('Ẩn đơn hàng này khỏi danh sách giám sát trực tiếp?')) return;
        try {
            const fd = new FormData();
            fd.append('order_id', id);
            const res = await fetch('<?= BASE_URL ?>/admin/realtime/dismiss', { method: 'POST', body: fd });
            const data = await res.json();
            if (data.ok) {
                const card = document.getElementById(`order-card-${id}`);
                if (card) {
                    card.style.transform = 'scale(0.9) translateY(20px)';
                    card.style.opacity = '0';
                    setTimeout(() => refreshData(), 300);
                }
            }
        } catch (err) { console.error('Lỗi khi ẩn đơn:', err); }
    }

    setInterval(() => {
        timerCount--;
        if (timerCount <= 0) refreshData();
        const el = document.getElementById('reloadCount');
        if (el) el.textContent = timerCount;
    }, 1000);

    // Initial load
    refreshData();
</script>
