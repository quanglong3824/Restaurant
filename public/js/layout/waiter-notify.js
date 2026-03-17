/**
 * waiter-notify.js — Notification Polling & Bottom Nav Functions
 * Extracted from waiter.php inline script
 * Aurora Restaurant
 */

/**
 * Fetch pending notifications from server
 */
function fetchNotifications() {
    fetch('<?= BASE_URL ?>/support/pending')
        .then(res => res.json())
        .then(res => {
            if (res.ok) {
                renderNotifications(res.data);
            }
        })
        .catch(err => console.error('Lỗi lấy thông báo:', err));
}

/**
 * Render notifications in the dropdown
 * @param {Array} data - Array of notification objects
 */
function renderNotifications(data) {
    const badge = document.getElementById('notiBadge');
    const list = document.getElementById('notiList');

    if (data.length > 0) {
        badge.style.display = 'inline-block';
        badge.textContent = data.length;
        let html = '';
        data.forEach(item => {
            let icon = '';
            let label = '';
            let btnLabel = 'Xong';
            
            if (item.type === 'payment') {
                icon = '<i class="fas fa-file-invoice-dollar" style="color:var(--danger)"></i>';
                label = 'Yêu cầu tính tiền';
            } else if (item.type === 'new_order') {
                icon = '<i class="fas fa-shopping-basket" style="color:var(--success)"></i>';
                label = 'Đơn hàng mới';
                btnLabel = 'Xác nhận';
            } else if (item.type === 'scan_qr') {
                icon = '<i class="fas fa-qrcode" style="color:var(--info)"></i>';
                label = 'Khách vừa quét mã';
                btnLabel = 'Đã biết';
            } else {
                icon = '<i class="fas fa-concierge-bell" style="color:var(--gold)"></i>';
                label = 'Gọi phục vụ';
            }

            html += `
                <div class="noti-item">
                    <div class="noti-item-info">
                        <strong>Bàn ${item.table_name}</strong> (Khu ${item.area})<br>
                        ${icon} ${label}
                    </div>
                    <button class="noti-btn" onclick="resolveNotification(${item.id})">${btnLabel}</button>
                </div>
            `;
        });
        list.innerHTML = html;
    } else {
        badge.style.display = 'none';
        list.innerHTML = '<div style="padding:15px; text-align:center; color:var(--text-dim); font-size:0.85rem;">Không có yêu cầu nào.</div>';
    }
}

/**
 * Mark a notification as resolved
 * @param {number} id - Notification ID to resolve
 */
function resolveNotification(id) {
    const data = new FormData();
    data.append('id', id);

    fetch('<?= BASE_URL ?>/support/resolve', {
        method: 'POST',
        body: data
    })
        .then(res => res.json())
        .then(res => {
            if (res.ok) fetchNotifications();
        });
}

/**
 * Close notification dropdown when clicking outside
 */
window.addEventListener('click', function (e) {
    if (!document.querySelector('.topbar-noti').contains(e.target)) {
        document.getElementById('notiDropdown').classList.remove('show');
    }
});

/**
 * Start notification polling (every 10 seconds)
 */
function startNotificationPolling() {
    setInterval(fetchNotifications, 10000);
    fetchNotifications(); // Initial fetch
}

/**
 * Manage bottom navigation active state
 * Set display of liquid-ring based on current active state
 */
function manageBottomNavActiveState() {
    const currentUrl = window.location.pathname;
    const navItems = document.querySelectorAll('.bottomnav-item');

    navItems.forEach(item => {
        const link = item.getAttribute('href');
        const isActive = item.classList.contains('active');

        // Show/hide liquid-ring based on active class from PHP
        const liquidRing = item.querySelector('.liquid-ring');
        if (liquidRing) {
            if (isActive) {
                liquidRing.style.display = 'block';
            } else {
                liquidRing.style.display = 'none';
            }
        }
    });
}

// Initialize onDOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    startNotificationPolling();
    manageBottomNavActiveState();
});
