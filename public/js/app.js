console.log("%c AURORA POS SYSTEM %c Optimized by LongDev ", "background:#1e293b;color:#d4af37;padding:5px;border-radius:5px 0 0 5px;font-weight:bold", "background:#d4af37;color:#1e293b;padding:5px;border-radius:0 5px 5px 0;font-weight:bold");

/**
 * app.js — Global utilities
 * Aurora Restaurant
 */

'use strict';

(function () {

    // ── Admin sidebar toggle (mobile) ───────────────────────
    const sidebarToggle  = document.getElementById('sidebarToggle');
    const sidebar        = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar?.classList.add('is-open');
        sidebarOverlay?.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar?.classList.remove('is-open');
        sidebarOverlay?.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    sidebarToggle?.addEventListener('click', openSidebar);
    sidebarOverlay?.addEventListener('click', closeSidebar);

    // Close on Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeSidebar();
    });

    // ── Modal helpers ────────────────────────────────────────
    function openModal(id) {
        const el = document.getElementById(id);
        el?.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        el?.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    // Close modal on backdrop click
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', e => {
            if (e.target === backdrop) {
                backdrop.classList.remove('is-open');
                document.body.style.overflow = '';
            }
        });
    });

    // Close modal buttons
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modalId = btn.closest('.modal-backdrop')?.id;
            if (modalId) closeModal(modalId);
        });
    });

    // Open modal buttons
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => openModal(btn.dataset.modalOpen));
    });

    // ── Flash auto-dismiss ───────────────────────────────────
    document.querySelectorAll('.alert[data-autohide]').forEach(alert => {
        const delay = parseInt(alert.dataset.autohide) || 3000;
        setTimeout(() => {
            alert.style.transition = 'opacity 0.4s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        }, delay);
    });

    // ── Confirm delete helper ────────────────────────────────
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', e => {
            const msg = el.dataset.confirm || 'Bạn có chắc muốn xoá?';
            if (!confirm(msg)) e.preventDefault();
        });
    });

    // ── Expose globally for inline use ───────────────────────
    window.Aurora = { openModal, closeModal };

})();

// ── Real-time Notification System ────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Shared Elements
    const waiterBadge = document.getElementById('waiterNotiBadge');
    const adminBell = document.getElementById('notificationBell');
    const adminPanel = document.getElementById('notificationPanel');
    const adminCount = document.getElementById('notificationCount');
    const adminList = document.getElementById('notificationList');
    
    if (!waiterBadge && !adminBell && !document.getElementById('waiterFullNotiList') && !document.getElementById('notiList')) return;

    // Âm thanh thông báo chất lượng cao từ internet
    const notifSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
    notifSound.preload = 'auto';

    let lastNotifId = 0;
    let isInitialLoad = true;

    // Global Toast Container
    const toastContainer = document.createElement('div');
    toastContainer.id = 'global-toast-container';
    toastContainer.style.cssText = 'position:fixed; top:20px; right:20px; z-index:10001; display:flex; flex-direction:column; gap:10px; max-width:320px; width:calc(100% - 40px);';
    document.body.appendChild(toastContainer);

    function showGlobalToast(n) {
        const toast = document.createElement('div');
        toast.className = `global-toast-item type-${n.notification_type}`;
        toast.style.cssText = 'background:white; border-radius:15px; padding:15px; box-shadow:0 10px 25px rgba(0,0,0,0.15); border-left:5px solid var(--gold); animation:slideInRight 0.4s ease-out; position:relative; overflow:hidden;';
        
        let color = '#d4af37';
        let icon = 'fa-bell';
        if (n.notification_type === 'payment_request') { color = '#ef4444'; icon = 'fa-hand-holding-usd'; }
        if (n.notification_type === 'new_order') { color = '#10b981'; icon = 'fa-utensils'; }
        if (n.notification_type === 'support_request') { color = '#f59e0b'; icon = 'fa-concierge-bell'; }
        
        toast.style.borderLeftColor = color;

        toast.innerHTML = `
            <div style="display:flex; gap:12px; align-items:flex-start;">
                <div style="width:40px; height:40px; background:${color}15; color:${color}; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                    <i class="fas ${icon}"></i>
                </div>
                <div style="flex:1;">
                    <h5 style="margin:0 0 3px 0; font-size:0.95rem; font-weight:800; color:#1e293b;">${n.title}</h5>
                    <p style="margin:0 0 10px 0; font-size:0.8rem; color:#64748b; line-height:1.4;">${n.message}</p>
                    <button onclick="window.location.href='${BASE_URL}/orders?table_id=${n.table_id}'" 
                            style="background:${color}; color:white; border:none; padding:8px 12px; border-radius:8px; font-size:0.75rem; font-weight:700; cursor:pointer; width:100%; display:flex; align-items:center; justify-content:center; gap:5px;">
                        <i class="fas fa-external-link-alt"></i> ĐI TỚI BÀN ${n.table_name}
                    </button>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" style="background:none; border:none; color:#94a3b8; cursor:pointer; padding:0; font-size:0.9rem;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="toast-progress" style="position:absolute; bottom:0; left:0; height:3px; background:${color}; width:100%; animation:progress-out 5s linear forwards;"></div>
        `;

        toastContainer.appendChild(toast);
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.animation = 'slideOutRight 0.4s ease-in forwards';
                setTimeout(() => toast.remove(), 400);
            }
        }, 5000);
    }

    // Add Keyframe Animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes slideOutRight { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(50px); } }
        @keyframes progress-out { from { width: 100%; } to { width: 0%; } }
    `;
    document.head.appendChild(style);

    // Admin Specific Logic
    if (adminBell) {
        adminBell.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpening = !adminPanel.classList.contains('show');
            adminPanel.classList.toggle('show');
            if (isOpening && adminCount.classList.contains('show')) {
                markAsRead();
                adminCount.classList.remove('show');
                adminList.querySelectorAll('.notification-item').forEach(item => item.classList.remove('unread'));
            }
        });
        document.addEventListener('click', (e) => {
            if (adminPanel && !adminBell.parentElement.contains(e.target)) adminPanel.classList.remove('show');
        });
    }

    async function pollNotifications() {
        try {
            const response = await fetch(`${BASE_URL}/api/notifications/poll`);
            const data = await response.json();
            
            if (!data.ok) return;

            const unreadCount = data.stats.unread;
            const notifications = data.notifications;

            // New Notification Logic
            if (notifications && notifications.length > 0) {
                const newestId = Math.max(...notifications.map(n => parseInt(n.id)));
                
                // Hiển thị Toast: 
                // 1. Nếu là lần đầu load trang: hiện tối đa 3 thông báo chưa đọc gần nhất
                // 2. Nếu là các lần poll sau: hiện tất cả thông báo mới có ID > lastNotifId
                if (isInitialLoad) {
                    notifications.filter(n => !parseInt(n.is_read)).slice(0, 3).reverse().forEach(n => {
                        showGlobalToast(n);
                    });
                } else if (newestId > lastNotifId) {
                    const newUnread = notifications.filter(n => parseInt(n.id) > lastNotifId && !parseInt(n.is_read));
                    if (newUnread.length > 0) {
                        notifSound.play().catch(e => {});
                        newUnread.reverse().forEach(n => showGlobalToast(n));
                    }
                }
                lastNotifId = newestId;
            }
            
            isInitialLoad = false;

            // Update UI Badges
            if (waiterBadge) {
                waiterBadge.textContent = unreadCount;
                waiterBadge.style.display = unreadCount > 0 ? 'block' : 'none';
            }

            if (adminCount) {
                adminCount.textContent = unreadCount;
                adminCount.classList.toggle('show', unreadCount > 0);
            }

            // Nếu đang ở trang danh sách thông báo của Waiter, cập nhật stats (badges bộ lọc)
            if (document.getElementById('notiList')) {
                updateWaiterStats(data.stats);
            }

            // Render list if panel is visible
            if (adminPanel?.classList.contains('show')) {
                renderAdminList(notifications);
            }

        } catch (e) { console.error("Poll failed", e); }
    }

    function updateWaiterStats(stats) {
        const setVal = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
        };
        setVal('count-all', stats.unread);
        setVal('count-payment', stats.payment);
        setVal('count-order', stats.order);
        setVal('count-support', stats.support);
    }

    function renderAdminList(notifications) {
        if (!adminList) return;
        if (notifications.length === 0) {
            adminList.innerHTML = '<div class="notification-item empty">Chưa có thông báo mới.</div>';
            return;
        }
        
        let html = '';
        notifications.forEach(n => {
            const isUnread = !parseInt(n.is_read);
            html += `
                <div class="notification-item ${isUnread ? 'unread' : ''}" onclick="window.location.href='${BASE_URL}/admin/realtime?order_id=${n.order_id}'">
                    <div class="notification-item-icon"><i class="fas ${getIcon(n.notification_type)}"></i></div>
                    <div class="notification-item-content">
                        <h5>${n.title}</h5>
                        <p>${n.message}</p>
                        <div class="notification-item-time">${formatTimeAgo(new Date(n.created_at))}</div>
                    </div>
                </div>
            `;
        });
        adminList.innerHTML = html;
    }

    function getIcon(type) {
        switch(type) {
            case 'new_order': return 'fa-file-invoice-dollar';
            case 'scan_qr': return 'fa-qrcode';
            case 'support_request': return 'fa-life-ring';
            case 'payment_request': return 'fa-hand-holding-usd';
            default: return 'fa-bell';
        }
    }

    async function markAsRead(id = null) {
        try {
            const fd = new FormData();
            if (id) fd.append('id', id);
            const response = await fetch(`${BASE_URL}/notifications/mark-read`, { method: 'POST', body: fd });
            const data = await response.json();
            if (data.ok) pollNotifications();
        } catch (e) { console.error("Mark read failed", e); }
    }

    // Mark all as read button
    const markAllBtn = document.getElementById('markAllAsReadBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            markAsRead();
        });
    }

    function formatTimeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        if (seconds < 60) return "vừa xong";
        let interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + " giờ trước";
        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + " phút trước";
        return Math.floor(seconds) + " giây trước";
    }

    pollNotifications();
    setInterval(pollNotifications, 4000);
});
