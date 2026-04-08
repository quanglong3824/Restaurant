/**
 * Realtime Monitoring JavaScript - Aurora Restaurant
 * Professional POS-style monitoring interface
 */

(function() {
    'use strict';

    let timerCount = 8;
    let isRefreshing = false;

    /**
     * Refresh data from server
     */
    async function refreshData() {
        if (isRefreshing) return;
        isRefreshing = true;

        const btn = document.querySelector('.refresh-circle-btn');
        if (btn) btn.innerHTML = '<i class="fas fa-sync fa-spin"></i>';

        try {
            const res = await fetch(BASE_URL + '/admin/realtime/data?t=' + Date.now());
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

    /**
     * Update statistics display
     */
    function updateStats(data) {
        const statOccupied = document.getElementById('statOccupied');
        const statAvailable = document.getElementById('statAvailable');
        const statTempRevenue = document.getElementById('statTempRevenue');

        if (statOccupied) statOccupied.textContent = data.counts.occupied;
        if (statAvailable) statAvailable.textContent = data.counts.available;
        
        if (statTempRevenue) {
            let tempTotal = 0;
            data.data.forEach(o => { 
                if (o.status === 'open') tempTotal += parseFloat(o.total || 0); 
            });
            statTempRevenue.textContent = new Intl.NumberFormat('vi-VN').format(tempTotal) + 'đ';
        }
    }

    /**
     * Render POS grid with orders
     */
    function renderPOSGrid(orders) {
        const container = document.getElementById('realtimeListContainer');
        if (!container) return;

        if (orders.length === 0) {
            container.innerHTML = `
                <div class="pos-loader">
                    <i class="fas fa-utensils fa-3x mb-3 opacity-10"></i>
                    <h3 style="font-weight:800; color:var(--pos-text);">KHÔNG CÓ DỮ LIỆU</h3>
                    <p class="small text-muted">Hệ thống đang chờ đơn hàng mới từ khách hàng...</p>
                </div>
            `;
            return;
        }

        let html = '';
        orders.forEach(order => {
            const isClosed = (order.status === 'closed');
            const statusTag = isClosed ? 'closed' : 'open';
            const statusText = isClosed ? 'Đã thanh toán' : (order.is_idle ? 'Đang chờ gọi món' : 'Đang ăn');
            
            let idleBadge = '';
            if (order.is_idle && !isClosed) {
                const remaining = Math.max(0, 300 - order.idle_seconds);
                const min = Math.floor(remaining / 60);
                const sec = remaining % 60;
                const color = remaining < 60 ? 'var(--pos-danger)' : 'var(--pos-warning)';
                idleBadge = `<div class="idle-timer" style="color:${color}; font-weight:800; font-size:0.75rem;">
                    <i class="fas fa-clock"></i> HUỶ SAU: ${min}:${sec < 10 ? '0'+sec : sec}
                </div>`;
            }

            let rows = '';
            order.items.forEach(it => {
                rows += `
                    <tr>
                        <td>
                            <span class="item-title">${escapeHtml(it.item_name)}</span>
                            ${it.note ? `<span class="item-note"><i class="fas fa-exclamation-circle"></i> ${escapeHtml(it.note)}</span>` : ''}
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
                            <h2>${escapeHtml(order.full_name)}</h2>
                            <div class="table-sub-info">
                                <span><i class="fas fa-user-friends me-1"></i> ${order.guest_count} khách</span>
                                <span><i class="fas fa-user-tie me-1"></i> ${escapeHtml(order.waiter_name || 'Khách QR')}</span>
                            </div>
                            ${idleBadge}
                        </div>
                        <div class="status-tag ${statusTag}">${statusText}</div>
                    </div>
                    
                    <div class="card-body-pos">
                        <table class="pos-table">
                            <thead>
                                <tr>
                                    <th>Món gọi</th>
                                    <th>SL</th>
                                    <th style="text-align:right">Tạm tính</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer-pos">
                        <div class="total-summary">
                            <div class="summary-left">
                                <span>GIỜ MỞ BÀN</span>
                                <span>${order.opened_at_fmt}</span>
                            </div>
                            <div class="summary-right">
                                <span class="total-label">TỔNG CỘNG</span>
                                <span class="total-value">${order.total_fmt}</span>
                            </div>
                        </div>
                        <div class="action-row">
                            <button onclick="dismissOrder(${order.id})" class="btn-pos btn-pos-primary">
                                <i class="fas fa-check-circle"></i> HOÀN TẤT & LƯU TRỮ
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }

    /**
     * Dismiss an order from realtime
     */
    async function dismissOrder(id) {
        try {
            const fd = new FormData();
            fd.append('order_id', id);
            const res = await fetch(BASE_URL + '/admin/realtime/dismiss', { 
                method: 'POST', 
                body: fd 
            });
            if ((await res.json()).ok) {
                const card = document.getElementById(`card-${id}`);
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px) scale(0.95)';
                    setTimeout(() => refreshData(), 300);
                }
            }
        } catch (err) { 
            console.error(err); 
        }
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&',
            '<': '<',
            '>': '>',
            '"': '"',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Initialize auto-refresh timer
    setInterval(() => {
        timerCount--;
        if (timerCount <= 0) refreshData();
        const el = document.getElementById('reloadCount');
        if (el) el.textContent = timerCount;
    }, 1000);

    // Initial data load
    refreshData();

    // Expose functions globally
    window.refreshData = refreshData;
    window.dismissOrder = dismissOrder;

})();