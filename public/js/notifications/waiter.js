/**
 * Notifications Waiter JavaScript - Aurora Restaurant
 * Waiter notification panel functionality
 */

(function() {
    'use strict';

    let currentPage = 1;
    let totalPages = 1;

    /**
     * Initialize notification panel
     */
    function init() {
        loadNotifications(currentPage);
        setupEventListeners();
        startPolling();
    }

    /**
     * Setup event listeners
     */
    function setupEventListeners() {
        // Pagination controls
        document.addEventListener('click', function(e) {
            if (e.target.closest('#btnPrevPage')) {
                e.preventDefault();
                if (currentPage > 1) loadNotifications(currentPage - 1);
            }
            if (e.target.closest('#btnNextPage')) {
                e.preventDefault();
                if (currentPage < totalPages) loadNotifications(currentPage + 1);
            }
        });

        // Mark as read button
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-mark-read');
            if (btn) {
                e.preventDefault();
                const id = btn.dataset.id;
                markAsRead(id);
            }
        });

        // Dismiss button
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-dismiss');
            if (btn) {
                e.preventDefault();
                const id = btn.dataset.id;
                dismissNotification(id);
            }
        });
    }

    /**
     * Load notifications via AJAX
     */
    async function loadNotifications(page) {
        try {
            const res = await fetch(BASE_URL + '/notifications/waiter/data?page=' + page);
            const data = await res.json();
            
            if (data.ok) {
                currentPage = data.pagination.page;
                totalPages = data.pagination.totalPages;
                renderNotifications(data.notifications);
                renderPagination(data.pagination);
                updateBadge(data.unreadCount);
            }
        } catch (err) {
            console.error('Lỗi tải thông báo:', err);
        }
    }

    /**
     * Render notifications list
     */
    function renderNotifications(notifications) {
        const container = document.getElementById('notificationListContainer');
        if (!container) return;

        if (notifications.length === 0) {
            container.innerHTML = `
                <div class="notification-empty">
                    <i class="fas fa-bell-slash"></i>
                    <p>Không có thông báo nào</p>
                </div>
            `;
            return;
        }

        let html = '';
        notifications.forEach(n => {
            const cfg = getNotificationConfig(n.type);
            const isUnread = !n.is_read;
            
            html += `
                <div class="notification-card ${isUnread ? 'unread' : 'read'}" id="notif-${n.id}">
                    <div class="notification-card-header">
                        <span class="card-type-tag ${cfg.tagClass}">
                            <i class="fas ${cfg.icon}"></i> ${cfg.label}
                        </span>
                        <span class="card-table-tag">
                            <i class="fas fa-map-marker-alt"></i> ${n.table_area || ''} • ${n.table_name}
                        </span>
                    </div>
                    <div class="notification-card-body">
                        ${escapeHtml(n.message)}
                        <span class="notification-timestamp">${formatTime(n.created_at)}</span>
                    </div>
                    ${isUnread ? `
                        <div class="notification-card-actions">
                            <button class="btn-notification btn-notification-primary btn-mark-read" data-id="${n.id}">
                                <i class="fas fa-check"></i> Đánh dấu đã đọc
                            </button>
                            <button class="btn-notification btn-notification-secondary btn-dismiss" data-id="${n.id}">
                                <i class="fas fa-times"></i> Bỏ qua
                            </button>
                        </div>
                    ` : ''}
                </div>
            `;
        });
        
        container.innerHTML = html;
    }

    /**
     * Render pagination controls
     */
    function renderPagination(pagination) {
        const container = document.getElementById('paginationControls');
        if (!container) return;

        if (pagination.totalPages <= 1) {
            container.style.display = 'none';
            return;
        }

        container.style.display = 'block';
        document.getElementById('pageInfo').textContent = `Trang ${pagination.page} / ${pagination.totalPages}`;
        
        const btnPrev = document.getElementById('btnPrevPage');
        const btnNext = document.getElementById('btnNextPage');
        
        if (btnPrev) btnPrev.disabled = pagination.page <= 1;
        if (btnNext) btnNext.disabled = pagination.page >= pagination.totalPages;
    }

    /**
     * Update notification badge
     */
    function updateBadge(count) {
        const badge = document.getElementById('waiterNotiBadge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    /**
     * Mark notification as read
     */
    async function markAsRead(id) {
        try {
            const fd = new FormData();
            fd.append('id', id);
            const res = await fetch(BASE_URL + '/notifications/waiter/mark-read', {
                method: 'POST',
                body: fd
            });
            if ((await res.json()).ok) {
                loadNotifications(currentPage);
            }
        } catch (err) {
            console.error(err);
        }
    }

    /**
     * Dismiss notification
     */
    async function dismissNotification(id) {
        try {
            const fd = new FormData();
            fd.append('id', id);
            const res = await fetch(BASE_URL + '/notifications/waiter/dismiss', {
                method: 'POST',
                body: fd
            });
            if ((await res.json()).ok) {
                const card = document.getElementById(`notif-${id}`);
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'translateX(20px)';
                    setTimeout(() => loadNotifications(currentPage), 300);
                }
            }
        } catch (err) {
            console.error(err);
        }
    }

    /**
     * Start polling for new notifications
     */
    function startPolling() {
        setInterval(() => {
            loadNotifications(currentPage);
        }, 10000); // Poll every 10 seconds
    }

    /**
     * Get notification type config
     */
    function getNotificationConfig(type) {
        const configs = {
            'new_order': { label: 'Món mới', icon: 'fa-utensils', tagClass: 'new-order' },
            'call_waiter': { label: 'Gọi phục vụ', icon: 'fa-bell', tagClass: 'call-waiter' },
            'payment': { label: 'Thanh toán', icon: 'fa-money-bill', tagClass: 'payment' },
            'merge_table': { label: 'Ghép bàn', icon: 'fa-object-group', tagClass: 'merge-table' }
        };
        return configs[type] || { label: type, icon: 'fa-info-circle', tagClass: '' };
    }

    /**
     * Format time display
     */
    function formatTime(dateStr) {
        const date = new Date(dateStr);
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        
        if (minutes < 1) return 'Vừa xong';
        if (minutes < 60) return minutes + ' phút trước';
        
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return hours + ' giờ trước';
        
        return date.toLocaleDateString('vi-VN');
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

    /**
     * Show toast notification
     */
    function showToast(message, type = 'success') {
        const existing = document.querySelector('.toast-notification');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose functions globally
    window.showToast = showToast;

})();