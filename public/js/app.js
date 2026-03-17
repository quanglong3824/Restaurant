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
    const notificationArea = document.getElementById('notificationArea');
    if (!notificationArea) return; // Only run on pages with the notification area

    const bell = document.getElementById('notificationBell');
    const panel = document.getElementById('notificationPanel');
    const countEl = document.getElementById('notificationCount');
    const listEl = document.getElementById('notificationList');
    const markAllBtn = document.getElementById('markAllAsReadBtn');

    const notifSound = new Audio('https://raw.githubusercontent.com/shashankmehta/notification-sounds/master/notification.mp3');
    notifSound.preload = 'auto';

    let lastNotifTimestamp = 0;

    // Toggle panel
    bell.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpening = !panel.classList.contains('show');
        panel.classList.toggle('show');
        
        // Mark all as read when opening if there are unread items
        if (isOpening && countEl.classList.contains('show')) {
            markAsRead(); // Mark all read on server
            // Optimistically clear UI count
            countEl.classList.remove('show');
            // Optimistically mark all in current list as read (grey out)
            listEl.querySelectorAll('.notification-item').forEach(item => item.classList.remove('unread'));
        }
    });

    // Close panel when clicking outside
    document.addEventListener('click', (e) => {
        if (!notificationArea.contains(e.target)) {
            panel.classList.remove('show');
        }
    });

    // Mark one as read
    listEl.addEventListener('click', (e) => {
        const item = e.target.closest('.notification-item');
        if (item && item.dataset.id && item.classList.contains('unread')) {
            markAsRead(item.dataset.id);
            item.classList.remove('unread');
            // We don't call updateCount here as we'll wait for next poll or just let it be
        }
    });

    // Mark all as read
    markAllBtn.addEventListener('click', () => {
        markAsRead(); // No ID = all
        listEl.querySelectorAll('.notification-item').forEach(item => item.classList.remove('unread'));
        countEl.classList.remove('show');
    });

    function getIcon(type) {
        switch(type) {
            case 'new_order': return 'fa-file-invoice-dollar';
            case 'scan_qr': return 'fa-qrcode';
            case 'support_request': return 'fa-life-ring';
            default: return 'fa-bell';
        }
    }

    function renderNotifications(notifications, unreadCount) {
        if (notifications.length === 0) {
            listEl.innerHTML = '<div class="notification-item empty">Chưa có thông báo mới.</div>';
            countEl.classList.remove('show');
            return;
        }

        // Only play sound for notifications newer than the last one we saw
        // and if they are unread
        const unreadItems = notifications.filter(n => !parseInt(n.is_read));
        if (unreadItems.length > 0) {
            const newestTimestamp = Math.max(...unreadItems.map(n => new Date(n.created_at).getTime()));
            if (newestTimestamp > lastNotifTimestamp && lastNotifTimestamp !== 0) {
                 notifSound.play().catch(err => console.error("Audio play failed:", err));
            }
            lastNotifTimestamp = newestTimestamp;
        } else if (notifications.length > 0 && lastNotifTimestamp === 0) {
            // Initialize timestamp on first load
            lastNotifTimestamp = Math.max(...notifications.map(n => new Date(n.created_at).getTime()));
        }

        listEl.innerHTML = ''; // Clear
        notifications.forEach(n => {
            const isUnread = !parseInt(n.is_read);
            const timeAgo = formatTimeAgo(new Date(n.created_at));

            const item = document.createElement('div');
            item.className = `notification-item ${isUnread ? 'unread' : ''}`;
            item.dataset.id = n.id;

            item.innerHTML = `
                <div class="notification-item-icon"><i class="fas ${getIcon(n.notification_type)}"></i></div>
                <div class="notification-item-content">
                    <h5>${n.title}</h5>
                    <p>${n.message}</p>
                    <div class="notification-item-time">${timeAgo}</div>
                </div>
            `;
            listEl.appendChild(item);
        });

        updateCount(unreadCount);
    }
    
    function updateCount(count) {
        if (count > 0) {
            countEl.textContent = count;
            countEl.classList.add('show');
        } else {
            countEl.classList.remove('show');
        }
    }

    async function pollNotifications() {
        try {
            const response = await fetch(`${BASE_URL}/notifications/poll`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            renderNotifications(data.notifications, data.count);
        } catch (error) {
            console.error("Failed to poll notifications:", error);
        }
    }
    
    async function markAsRead(notificationId = null) {
        try {
            const formData = new FormData();
            if (notificationId) {
                formData.append('id', notificationId);
            }
            await fetch(`${BASE_URL}/notifications/mark-read`, {
                method: 'POST',
                body: formData
            });
        } catch (error) {
            console.error("Failed to mark notification as read:", error);
        }
    }

    function formatTimeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = seconds / 31536000;
        if (interval > 1) return Math.floor(interval) + " năm trước";
        interval = seconds / 2592000;
        if (interval > 1) return Math.floor(interval) + " tháng trước";
        interval = seconds / 86400;
        if (interval > 1) return Math.floor(interval) + " ngày trước";
        interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + " giờ trước";
        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + " phút trước";
        return Math.floor(seconds) + " giây trước";
    }

    // Initial poll and then start interval
    pollNotifications();
    setInterval(pollNotifications, 5000); // Poll every 5 seconds
});
