<?php // views/notifications/waiter.php — Rebuilt interactive notification center ?>
<div class="noti-center-container">
    <div class="noti-header animate-fade-in-down">
        <div class="header-main">
            <h2 class="playfair">Trung tâm điều hành</h2>
            <p class="text-muted small">Cập nhật yêu cầu từ khách hàng thời gian thực</p>
        </div>
        <div class="header-actions">
            <button id="btnMarkAllRead" class="btn btn-ghost btn-sm">
                <i class="fas fa-check-double me-1"></i> Đã xử lý tất cả
            </button>
        </div>
    </div>

    <!-- Quick Stats & Filters -->
    <div class="noti-filters animate-fade-in-up">
        <div class="filter-pill active" data-type="all">Tất cả <span class="badge" id="count-all">0</span></div>
        <div class="filter-pill" data-type="payment_request">Thanh toán <span class="badge badge-danger" id="count-payment">0</span></div>
        <div class="filter-pill" data-type="new_order">Đơn mới <span class="badge badge-success" id="count-order">0</span></div>
        <div class="filter-pill" data-type="support_request">Cần hỗ trợ <span class="badge badge-warning" id="count-support">0</span></div>
    </div>

    <!-- Main List -->
    <div id="notiList" class="noti-list animate-fade-in-up">
        <div class="loading-state py-5 text-center">
            <div class="spinner"></div>
            <p class="text-muted mt-3">Đang kết nối trung tâm...</p>
        </div>
    </div>
</div>

<!-- Audio Alerts (Hidden) -->
<audio id="audioOrder" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>
<audio id="audioAlert" src="https://assets.mixkit.co/active_storage/sfx/2571/2571-preview.mp3" preload="auto"></audio>

<style>
    .noti-center-container { padding: 10px; max-width: 800px; margin: 0 auto; }
    .noti-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding: 0 5px; }
    .noti-header h2 { margin: 0; color: var(--text-dark); font-weight: 800; }
    
    .noti-filters { display: flex; gap: 8px; overflow-x: auto; padding: 5px 0 15px; margin-bottom: 10px; scrollbar-width: none; }
    .noti-filters::-webkit-scrollbar { display: none; }
    .filter-pill { 
        white-space: nowrap; padding: 8px 16px; border-radius: 50rem; 
        background: white; border: 1px solid var(--border); font-size: 0.85rem; 
        font-weight: 600; color: var(--text-light); cursor: pointer; transition: all 0.2s;
        display: flex; align-items: center; gap: 6px;
    }
    .filter-pill.active { background: var(--gold); color: white; border-color: var(--gold); box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3); }
    .filter-pill .badge { font-size: 0.7rem; background: rgba(0,0,0,0.1); padding: 2px 6px; border-radius: 10px; }

    .noti-list { display: flex; flex-direction: column; gap: 12px; padding-bottom: 80px; }
    
    .noti-card {
        background: white; border-radius: 18px; padding: 16px; 
        display: flex; gap: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid var(--border); position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer; animation: slideIn 0.4s ease-out forwards;
    }
    @keyframes slideIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .noti-card.unread { border-left: 5px solid var(--gold); background: #fffcf5; }
    .noti-card.unread.type-payment_request { border-left-color: #ef4444; background: #fff5f5; }
    .noti-card.unread.type-new_order { border-left-color: #10b981; background: #f0fff9; }
    .noti-card.unread.type-support_request { border-left-color: #f59e0b; background: #fffaf0; }
    
    .noti-card.read { opacity: 0.7; transform: scale(0.98); }

    .card-icon {
        width: 50px; height: 50px; min-width: 50px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center; font-size: 1.25rem;
        background: #f1f5f9; color: var(--text-light);
    }
    .unread .card-icon { background: white; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .type-payment_request .card-icon { color: #ef4444; }
    .type-new_order .card-icon { color: #10b981; }
    .type-support_request .card-icon { color: #f59e0b; }

    .card-content { flex: 1; min-width: 0; }
    .card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px; }
    .card-title { font-weight: 800; font-size: 1rem; color: var(--text-dark); margin: 0; }
    .card-time { font-size: 0.7rem; color: var(--text-dim); font-weight: 600; }
    .card-msg { font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.4; }
    
    .card-table-tag {
        display: inline-block; padding: 2px 8px; border-radius: 6px; 
        background: #e2e8f0; color: #475569; font-size: 0.7rem; 
        font-weight: 700; margin-top: 8px; text-transform: uppercase;
    }

    .card-actions { display: flex; gap: 8px; margin-top: 12px; }
    .btn-action { 
        flex: 1; padding: 8px; border-radius: 10px; border: none;
        font-size: 0.75rem; font-weight: 700; cursor: pointer;
        transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 5px;
    }
    .btn-action-primary { background: var(--gold); color: white; }
    .btn-action-primary:hover { background: var(--gold-dark); }
    .btn-action-secondary { background: #f1f5f9; color: var(--text-light); }
    
    .empty-state { padding: 100px 20px; color: var(--text-dim); }
    .empty-state i { font-size: 3rem; margin-bottom: 15px; opacity: 0.2; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const listEl = document.getElementById('notiList');
    const filterPills = document.querySelectorAll('.filter-pill');
    let currentFilter = 'all';
    let lastNotiId = 0;
    let isFirstLoad = true;

    async function fetchNotifications() {
        try {
            const response = await fetch(`${BASE_URL}/api/notifications/poll`);
            if (!response.ok) return;
            const data = await response.json();
            
            updateStats(data.notifications);
            renderList(data.notifications);
            checkNewNoti(data.notifications);
            
            isFirstLoad = false;
        } catch (e) {
            console.error("Poll error", e);
        }
    }

    function updateStats(notifs) {
        const counts = { all: notifs.length, payment_request: 0, new_order: 0, support_request: 0 };
        notifs.forEach(n => {
            if (!n.is_read && counts[n.notification_type] !== undefined) {
                counts[n.notification_type]++;
            }
        });
        document.getElementById('count-all').textContent = notifs.filter(n => !n.is_read).length;
        document.getElementById('count-payment').textContent = counts.payment_request;
        document.getElementById('count-order').textContent = counts.new_order;
        document.getElementById('count-support').textContent = counts.support_request;
    }

    function checkNewNoti(notifs) {
        if (notifs.length === 0) return;
        const newest = notifs[0];
        if (!isFirstLoad && newest.id > lastNotiId && !newest.is_read) {
            // Play sound based on type
            if (newest.notification_type === 'new_order' || newest.notification_type === 'payment_request') {
                document.getElementById('audioOrder').play().catch(e => {});
            } else {
                document.getElementById('audioAlert').play().catch(e => {});
            }
        }
        lastNotiId = Math.max(...notifs.map(n => n.id), lastNotiId);
    }

    function renderList(notifs) {
        const filtered = currentFilter === 'all' 
            ? notifs 
            : notifs.filter(n => n.notification_type === currentFilter);

        if (filtered.length === 0) {
            listEl.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-bell-slash"></i>
                    <p>Không có thông báo nào trong mục này.</p>
                </div>
            `;
            return;
        }

        const fragment = document.createDocumentFragment();
        filtered.forEach(n => {
            const isUnread = !parseInt(n.is_read);
            const card = document.createElement('div');
            card.className = `noti-card ${isUnread ? 'unread' : 'read'} type-${n.notification_type}`;
            
            let icon = 'fa-bell';
            let typeLabel = 'Thông báo';
            if (n.notification_type === 'payment_request') { icon = 'fa-file-invoice-dollar'; typeLabel = 'Tính tiền'; }
            if (n.notification_type === 'new_order') { icon = 'fa-utensils'; typeLabel = 'Món mới'; }
            if (n.notification_type === 'support_request') { icon = 'fa-concierge-bell'; typeLabel = 'Hỗ trợ'; }
            if (n.notification_type === 'scan_qr') { icon = 'fa-qrcode'; typeLabel = 'Quét QR'; }

            card.innerHTML = `
                <div class="card-icon"><i class="fas ${icon}"></i></div>
                <div class="card-content">
                    <div class="card-top">
                        <h3 class="card-title">${n.title}</h3>
                        <span class="card-time">${formatTimeAgo(new Date(n.created_at))}</span>
                    </div>
                    <p class="card-msg">${n.message}</p>
                    <div class="card-table-tag">${n.table_area} • ${n.table_name}</div>
                    
                    ${isUnread ? `
                    <div class="card-actions">
                        <button class="btn-action btn-action-primary" onclick="event.stopPropagation(); handleAction(${n.id}, ${n.table_id}, '${n.notification_type}')">
                            <i class="fas fa-check"></i> XÁC NHẬN XỬ LÝ
                        </button>
                        <button class="btn-action btn-action-secondary" onclick="event.stopPropagation(); goToTable(${n.table_id})">
                            <i class="fas fa-external-link-alt"></i> CHI TIẾT BÀN
                        </button>
                    </div>
                    ` : ''}
                </div>
            `;

            card.onclick = () => goToTable(n.table_id);
            fragment.appendChild(card);
        });

        listEl.innerHTML = '';
        listEl.appendChild(fragment);
    }

    window.handleAction = async (id, tableId, type) => {
        try {
            // 1. Mark notification as read
            const fd = new FormData();
            fd.append('id', id);
            await fetch(`${BASE_URL}/api/notifications/mark-read`, { method: 'POST', body: fd });

            // 2. Resolve support if applicable
            if (type === 'support_request' || type === 'payment_request') {
                const fd2 = new FormData();
                fd2.append('table_id', tableId);
                fd2.append('type', type === 'payment_request' ? 'payment' : 'support');
                await fetch(`${BASE_URL}/api/notifications/resolve-support`, { method: 'POST', body: fd2 });
            }

            fetchNotifications();
            if (window.showToast) showToast('Đã xác nhận xử lý xong');
        } catch (e) {
            alert('Lỗi khi thực hiện thao tác');
        }
    };

    window.goToTable = (tableId) => {
        window.location.href = `${BASE_URL}/orders?table_id=${tableId}`;
    };

    document.getElementById('btnMarkAllRead').onclick = async () => {
        await fetch(`${BASE_URL}/api/notifications/mark-read`, { method: 'POST' });
        fetchNotifications();
    };

    filterPills.forEach(pill => {
        pill.onclick = () => {
            filterPills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            currentFilter = pill.dataset.type;
            fetchNotifications();
        };
    });

    function formatTimeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        if (seconds < 60) return "vừa xong";
        let interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + " giờ trước";
        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + " phút trước";
        return Math.floor(seconds) + " giây trước";
    }

    fetchNotifications();
    setInterval(fetchNotifications, 5000); // Polling every 5s
});
</script>
