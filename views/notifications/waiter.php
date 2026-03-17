<?php // views/notifications/waiter.php — Full Page Notifications for Waiters ?>
<div class="notifications-page">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="animate-fade-in-down"><i class="fas fa-bell me-2" style="color:var(--gold)"></i> Thông báo mới</h2>
        <button id="waiterMarkAllRead" class="btn btn-ghost btn-sm animate-fade-in-down">
            <i class="fas fa-check-double me-1"></i> Đánh dấu đã đọc
        </button>
    </div>

    <div id="waiterFullNotiList" class="waiter-noti-list animate-fade-in-up">
        <div class="loading-state py-5 text-center">
            <div class="spinner"></div>
            <p class="text-muted mt-2">Đang tải thông báo...</p>
        </div>
    </div>
</div>

<style>
    .notifications-page { padding-bottom: 20px; }
    .waiter-noti-list { display: flex; flex-direction: column; gap: 12px; }
    
    .waiter-noti-item {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 1.25rem;
        display: flex;
        gap: 1rem;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.32, 0.72, 0, 1);
        position: relative;
        overflow: hidden;
    }

    .waiter-noti-item:active { transform: scale(0.98); }

    .waiter-noti-item.unread {
        border-left: 4px solid var(--gold);
        background: var(--gold-light);
    }

    .waiter-noti-item.read {
        opacity: 0.6;
    }

    .noti-icon-box {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        background: var(--bg);
        color: var(--gold-dark);
    }

    .unread .noti-icon-box {
        background: white;
        box-shadow: 0 4px 10px rgba(184, 155, 94, 0.2);
    }

    .noti-content { flex: 1; }
    .noti-title { margin: 0 0 4px; font-size: 1rem; font-weight: 800; color: var(--text); }
    .noti-msg { margin: 0; font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; }
    .noti-time { font-size: 0.7rem; color: var(--text-dim); margin-top: 8px; font-weight: 600; display: block; }

    .empty-state { text-align: center; padding: 100px 20px; color: var(--text-dim); }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.3; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const listEl = document.getElementById('waiterFullNotiList');
    const markAllBtn = document.getElementById('waiterMarkAllRead');

    async function fetchNotifications() {
        console.log("Calling API:", `${BASE_URL}/api/notifications/poll`);
        try {
            const response = await fetch(`${BASE_URL}/api/notifications/poll`);
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            console.log("Data received:", data);
            renderList(data.notifications || []);

            // Update badge globally if function exists
            if (window.updateNotiBadge) {
                window.updateNotiBadge(data.count || 0);
            }
        } catch (e) {
            console.error("Fetch error:", e);
            listEl.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                    <p>Lỗi kết nối máy chủ. Đang thử lại...</p>
                </div>
            `;
        }
    }

    function renderList(notifications) {
        console.log("Rendering notifications list, count:", notifications.length);
        if (!notifications || notifications.length === 0) {
            listEl.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-bell-slash"></i>
                    <p>Hiện không có thông báo nào.</p>
                </div>
            `;
            return;
        }
        const fragment = document.createDocumentFragment();
        notifications.forEach(n => {
            const isUnread = n.is_read == 0 || n.is_read === false || n.is_read === null;
            const item = document.createElement('div');
            item.className = `waiter-noti-item ${isUnread ? 'unread' : 'read'}`;
    ...
            const icon = n.notification_type === 'scan_qr' ? 'fa-qrcode' : 
                         n.notification_type === 'payment_request' ? 'fa-file-invoice-dollar' : 'fa-concierge-bell';

            item.innerHTML = `
                <div class="noti-icon-box"><i class="fas ${icon}"></i></div>
                <div class="noti-content">
                    <h3 class="noti-title">${n.title}</h3>
                    <p class="noti-msg">${n.message}</p>
                    <span class="noti-time">${formatTimeAgo(new Date(n.created_at))}</span>
                </div>
            `;

            item.onclick = async () => {
                if (isUnread) {
                    const fd = new FormData();
                    if (n.id) fd.append('id', n.id);
                    await fetch(`${BASE_URL}/api/notifications/mark-read`, { method: 'POST', body: fd });
                }
                // Redirect to the table view
                window.location.href = `${BASE_URL}/orders?table_id=${n.table_id}`;
            };

            fragment.appendChild(item);
        });
        
        listEl.innerHTML = '';
        listEl.appendChild(fragment);
    }

    markAllBtn.onclick = async () => {
        await fetch(`${BASE_URL}/api/notifications/mark-read`, { method: 'POST' });
        fetchNotifications();
    };

    function formatTimeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + " giờ trước";
        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + " phút trước";
        return Math.floor(seconds) + " giây trước";
    }

    fetchNotifications();
    setInterval(fetchNotifications, 5000);
});
</script>
