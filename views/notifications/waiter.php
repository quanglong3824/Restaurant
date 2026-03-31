<?php // views/notifications/waiter.php — Rebuilt interactive notification center ?>
<div class="noti-center-container">
    <div class="noti-header animate-fade-in-down">
        <div class="header-main">
            <h2 class="playfair">Trung tâm điều hành</h2>
            <p class="text-muted small">Cập nhật yêu cầu từ khách hàng thời gian thực</p>
        </div>
        <div class="header-actions">
            <!-- Nút bật/tắt âm thanh -->
            <button id="btnToggleSound" class="btn btn-ghost btn-sm" title="Bật / Tắt âm thanh thông báo">
                <i class="fas fa-volume-up me-1" id="soundIcon"></i> <span id="soundLabel">Âm thanh</span>
            </button>
            <button id="btnMarkAllRead" class="btn btn-ghost btn-sm">
                <i class="fas fa-check-double me-1"></i> Đã xử lý tất cả
            </button>
        </div>
    </div>

    <!-- Quick Stats & Filters -->
    <div class="noti-filters animate-fade-in-up">
        <div class="filter-pill active" data-type="all">
            <i class="fas fa-layer-group"></i> Tất cả <span class="badge" id="count-all">0</span>
        </div>
        <div class="filter-pill" data-type="payment_request">
            <i class="fas fa-hand-holding-usd"></i> Thanh toán <span class="badge badge-danger" id="count-payment">0</span>
        </div>
        <div class="filter-pill" data-type="new_order">
            <i class="fas fa-utensils"></i> Đơn mới <span class="badge badge-success" id="count-order">0</span>
        </div>
        <div class="filter-pill" data-type="order_item">
            <i class="fas fa-plus-circle"></i> Thêm món <span class="badge badge-info" id="count-order-item">0</span>
        </div>
        <div class="filter-pill" data-type="support_request">
            <i class="fas fa-concierge-bell"></i> Hỗ trợ <span class="badge badge-warning" id="count-support">0</span>
        </div>
        <div class="filter-pill" data-type="scan_qr">
            <i class="fas fa-qrcode"></i> Quét QR <span class="badge badge-purple" id="count-scan">0</span>
        </div>
        <!-- Lọc theo trạng thái -->
        <div class="filter-divider"></div>
        <div class="filter-pill status-pill" data-status="unread">
            <i class="fas fa-envelope"></i> Chưa xử lý
        </div>
        <div class="filter-pill status-pill" data-status="read">
            <i class="fas fa-envelope-open"></i> Đã xử lý
        </div>
    </div>

    <!-- Main List -->
    <div id="notiList" class="noti-list animate-fade-in-up">
        <div class="loading-state py-5 text-center">
            <div class="spinner"></div>
            <p class="text-muted mt-3">Đang kết nối trung tâm...</p>
        </div>
    </div>

    <!-- Pagination Controls -->
    <div id="paginationControls" class="pagination-scroller mt-4" style="display: none; padding-bottom: 100px;">
        <nav class="pagination-luxury">
            <button id="btnPrevPage" class="pag-btn">
                <i class="fas fa-chevron-left" style="font-size: 0.8rem;"></i>
            </button>
            <div id="pageInfo" class="pag-numbers" style="padding: 0 15px; font-weight: 700; color: var(--gold-dark); font-size: 0.9rem;">
                Trang 1
            </div>
            <button id="btnNextPage" class="pag-btn">
                <i class="fas fa-chevron-right" style="font-size: 0.8rem;"></i>
            </button>
        </nav>
    </div>
</div>

<!-- Audio element thay vì tạo mới mỗi lần -->
<audio id="notiSoundDefault" preload="auto" src="<?= BASE_URL ?>/public/audio/nofi.mp3"></audio>

<style>
    .noti-center-container { padding: 10px; max-width: 800px; margin: 0 auto; }
    .noti-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding: 0 5px; flex-wrap: wrap; gap: 10px; }
    .header-main h2 { margin: 0 0 4px 0; color: var(--text-dark); font-weight: 800; font-size: 1.5rem; line-height: 1.2; }
    .header-main p { margin: 0; line-height: 1.4; opacity: 0.8; }
    .header-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    
    .noti-filters { display: flex; gap: 8px; overflow-x: auto; padding: 5px 0 15px; margin-bottom: 10px; scrollbar-width: none; align-items: center; }
    .noti-filters::-webkit-scrollbar { display: none; }
    .filter-pill { 
        white-space: nowrap; padding: 8px 16px; border-radius: 50rem; 
        background: white; border: 1px solid var(--border); font-size: 0.82rem; 
        font-weight: 600; color: var(--text-light); cursor: pointer; transition: all 0.25s;
        display: flex; align-items: center; gap: 6px;
        user-select: none;
    }
    .filter-pill:hover { border-color: var(--gold); color: var(--gold); transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,0.06); }
    .filter-pill.active { background: var(--gold); color: white; border-color: var(--gold); box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3); }
    .filter-pill .badge { font-size: 0.7rem; background: rgba(0,0,0,0.1); padding: 2px 6px; border-radius: 10px; min-width: 18px; text-align: center; }
    .filter-pill.active .badge { background: rgba(255,255,255,0.3); }
    
    /* Status filter pills */
    .filter-divider { width: 1px; height: 24px; background: var(--border); margin: 0 4px; flex-shrink: 0; }
    .status-pill { background: #f8fafc; border-style: dashed; }
    .status-pill.active { background: #1e293b; color: white; border-color: #1e293b; border-style: solid; box-shadow: 0 4px 12px rgba(30, 41, 59, 0.3); }

    /* Badge colors */
    .badge-danger { background: #fef2f2 !important; color: #ef4444 !important; }
    .filter-pill.active .badge-danger { background: rgba(255,255,255,0.3) !important; color: white !important; }
    .badge-success { background: #f0fdf4 !important; color: #10b981 !important; }
    .filter-pill.active .badge-success { background: rgba(255,255,255,0.3) !important; color: white !important; }
    .badge-warning { background: #fffbeb !important; color: #f59e0b !important; }
    .filter-pill.active .badge-warning { background: rgba(255,255,255,0.3) !important; color: white !important; }
    .badge-info { background: #eff6ff !important; color: #3b82f6 !important; }
    .filter-pill.active .badge-info { background: rgba(255,255,255,0.3) !important; color: white !important; }
    .badge-purple { background: #faf5ff !important; color: #8b5cf6 !important; }
    .filter-pill.active .badge-purple { background: rgba(255,255,255,0.3) !important; color: white !important; }

    .noti-list { display: flex; flex-direction: column; gap: 12px; padding-bottom: 20px; }
    
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
    .noti-card.unread.type-order_item { border-left-color: #3b82f6; background: #eff6ff; }
    .noti-card.unread.type-support_request { border-left-color: #f59e0b; background: #fffaf0; }
    .noti-card.unread.type-scan_qr { border-left-color: #8b5cf6; background: #faf5ff; }
    
    .noti-card.read { opacity: 0.65; transform: scale(0.98); }
    .noti-card.read:hover { opacity: 0.85; }

    .card-icon {
        width: 50px; height: 50px; min-width: 50px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center; font-size: 1.25rem;
        background: #f1f5f9; color: var(--text-light);
    }
    .unread .card-icon { background: white; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .type-payment_request .card-icon { color: #ef4444; }
    .type-new_order .card-icon { color: #10b981; }
    .type-order_item .card-icon { color: #3b82f6; }
    .type-support_request .card-icon { color: #f59e0b; }
    .type-scan_qr .card-icon { color: #8b5cf6; }

    .card-content { flex: 1; min-width: 0; }
    .card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px; }
    .card-title { font-weight: 800; font-size: 1rem; color: var(--text-dark); margin: 0; }
    .card-time { font-size: 0.7rem; color: var(--text-dim); font-weight: 600; white-space: nowrap; margin-left: 8px; }
    .card-msg { font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.4; }
    
    .card-meta { display: flex; align-items: center; gap: 8px; margin-top: 8px; flex-wrap: wrap; }
    .card-table-tag {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 8px; border-radius: 6px; 
        background: #e2e8f0; color: #475569; font-size: 0.7rem; 
        font-weight: 700; text-transform: uppercase;
    }
    .card-type-tag {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 700;
    }
    .card-type-tag.ct-payment { background: #fef2f2; color: #ef4444; }
    .card-type-tag.ct-order { background: #f0fdf4; color: #10b981; }
    .card-type-tag.ct-item { background: #eff6ff; color: #3b82f6; }
    .card-type-tag.ct-support { background: #fffbeb; color: #f59e0b; }
    .card-type-tag.ct-scan { background: #faf5ff; color: #8b5cf6; }
    .card-type-tag.ct-default { background: #f1f5f9; color: #64748b; }

    .card-actions { display: flex; gap: 8px; margin-top: 12px; }
    .btn-action { 
        flex: 1; padding: 10px 8px; border-radius: 12px; border: none;
        font-size: 0.75rem; font-weight: 700; cursor: pointer;
        transition: all 0.25s; display: flex; align-items: center; justify-content: center; gap: 5px;
    }
    .btn-action:active { transform: scale(0.97); }
    
    /* Payment action - Red/urgent */
    .btn-pay { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
    .btn-pay:hover { box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4); }
    
    /* Order action - Green */
    .btn-order { background: linear-gradient(135deg, #10b981, #059669); color: white; }
    .btn-order:hover { box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4); }
    
    /* Support action - Orange */
    .btn-support { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
    .btn-support:hover { box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4); }
    
    /* Item/QR action - Blue */
    .btn-info { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; }
    .btn-info:hover { box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); }
    
    /* Default action - Gold */
    .btn-action-primary { background: var(--gold); color: white; }
    .btn-action-primary:hover { background: var(--gold-dark); }
    
    /* Secondary */
    .btn-action-secondary { background: #f1f5f9; color: var(--text-light); }
    .btn-action-secondary:hover { background: #e2e8f0; }
    
    .empty-state { padding: 100px 20px; color: var(--text-dim); text-align: center; }
    .empty-state i { font-size: 3rem; margin-bottom: 15px; opacity: 0.2; display: block; }

    /* Sound toggle styles */
    #btnToggleSound { display: flex; align-items: center; gap: 4px; }
    #btnToggleSound.sound-off { opacity: 0.5; }
    #btnToggleSound.sound-off .fa-volume-up::before { content: "\f6a9"; } /* fa-volume-mute */

    /* Pulse animation for urgent items */
    @keyframes urgentPulse {
        0%, 100% { box-shadow: 0 4px 15px rgba(239, 68, 68, 0.1); }
        50% { box-shadow: 0 4px 20px rgba(239, 68, 68, 0.25); }
    }
    .noti-card.unread.type-payment_request { animation: slideIn 0.4s ease-out forwards, urgentPulse 2s ease-in-out infinite; }
    .noti-card.unread.type-support_request { animation: slideIn 0.4s ease-out forwards, urgentPulse 2s ease-in-out infinite; }

    /* Pagination Styles */
    .pagination-luxury { display: flex; align-items: center; justify-content: center; gap: 10px; }
    .pag-btn { 
        width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--border); 
        background: white; color: var(--text-muted); display: flex; 
        align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;
    }
    .pag-btn:hover:not(.disabled) { border-color: var(--gold); color: var(--gold); }
    .pag-btn.disabled { opacity: 0.4; cursor: not-allowed; }

    /* Confirmed/processed badge on read cards */
    .card-status-done {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 700;
        background: #f0fdf4; color: #16a34a; margin-top: 8px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const listEl = document.getElementById('notiList');
    const filterPills = document.querySelectorAll('.filter-pill:not(.status-pill)');
    const statusPills = document.querySelectorAll('.filter-pill.status-pill');
    const paginationControls = document.getElementById('paginationControls');
    const btnPrevPage = document.getElementById('btnPrevPage');
    const btnNextPage = document.getElementById('btnNextPage');
    const pageInfo = document.getElementById('pageInfo');

    let currentFilter = 'all';
    let currentStatus = ''; // '', 'unread', 'read'
    let currentPage = 1;
    let itemsPerPage = 15;
    let totalItems = 0;
    let isFirstLoad = true;

    // ── Hệ thống âm thanh cải tiến ──────────────────────────
    let soundEnabled = localStorage.getItem('noti_sound') !== 'off';
    let audioUnlocked = false;
    const audioEl = document.getElementById('notiSoundDefault');

    function updateSoundUI() {
        const btn = document.getElementById('btnToggleSound');
        const icon = document.getElementById('soundIcon');
        const label = document.getElementById('soundLabel');
        if (soundEnabled) {
            btn.classList.remove('sound-off');
            icon.className = 'fas fa-volume-up me-1';
            label.textContent = 'Âm thanh';
        } else {
            btn.classList.add('sound-off');
            icon.className = 'fas fa-volume-mute me-1';
            label.textContent = 'Tắt tiếng';
        }
    }
    updateSoundUI();

    document.getElementById('btnToggleSound').onclick = () => {
        soundEnabled = !soundEnabled;
        localStorage.setItem('noti_sound', soundEnabled ? 'on' : 'off');
        updateSoundUI();
        
        // Phát test sound khi bật lại để unlock audio context
        if (soundEnabled) {
            unlockAndPlay();
        }
    };

    // Unlock Audio Context bằng user interaction (click anywhere)
    function unlockAudio() {
        if (audioUnlocked) return;
        audioEl.volume = 0.01;
        audioEl.play().then(() => {
            audioEl.pause();
            audioEl.currentTime = 0;
            audioEl.volume = 1;
            audioUnlocked = true;
            console.log('🔔 Audio unlocked!');
        }).catch(() => {});
    }

    function unlockAndPlay() {
        audioEl.volume = 1;
        audioEl.currentTime = 0;
        audioEl.play().then(() => {
            audioUnlocked = true;
        }).catch(() => {});
    }

    // Unlock khi có bất kỳ interaction trên trang
    ['click', 'touchstart', 'keydown'].forEach(evt => {
        document.addEventListener(evt, unlockAudio, { once: false, passive: true });
    });

    // Phát âm thanh thông báo
    function playNotifSound(times = 1) {
        if (!soundEnabled || !audioUnlocked) return;
        
        let count = 0;
        function playOnce() {
            if (count >= times) return;
            const audio = audioEl.cloneNode();
            audio.volume = 1;
            audio.currentTime = 0;
            audio.play().catch(() => {});
            count++;
            if (count < times) {
                setTimeout(playOnce, 700);
            }
        }
        playOnce();
    }

    // Expose cho app.js toàn cục nếu cần
    window.__notiPlaySound = playNotifSound;

    // ── Fetch & Render ──────────────────────────────────────
    async function fetchNotifications(silent = false) {
        try {
            let url = `${BASE_URL}/api/notifications/poll?page=${currentPage}&limit=${itemsPerPage}&filter=${currentFilter}`;
            if (currentStatus) {
                url += `&status=${currentStatus}`;
            }
            const response = await fetch(url);
            if (!response.ok) return;
            const data = await response.json();
            
            if (data.ok) {
                totalItems = data.total_count;
                renderList(data.notifications);
                updatePagination();

                // Cập nhật count badges từ stats
                if (data.stats) {
                    updateFilterCounts(data.stats);
                }
            }
        } catch (e) {
            console.error("Fetch error", e);
        }
    }

    function updateFilterCounts(stats) {
        const setVal = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.textContent = val || 0;
        };
        setVal('count-all', stats.unread);
        setVal('count-payment', stats.payment);
        setVal('count-order', stats.order);
        setVal('count-order-item', stats.order_item || 0);
        setVal('count-support', stats.support);
        setVal('count-scan', stats.scan || 0);
    }

    function updatePagination() {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        if (totalPages <= 1) {
            paginationControls.style.display = 'none';
        } else {
            paginationControls.style.display = 'block';
            pageInfo.textContent = `Trang ${currentPage} / ${totalPages}`;
            
            btnPrevPage.classList.toggle('disabled', currentPage === 1);
            btnNextPage.classList.toggle('disabled', currentPage === totalPages);
        }
    }

    function getTypeConfig(type) {
        const configs = {
            'payment_request': {
                icon: 'fa-hand-holding-usd',
                label: 'Thanh toán',
                btnClass: 'btn-pay',
                btnIcon: 'fa-cash-register',
                btnText: 'XỬ LÝ THANH TOÁN',
                tagClass: 'ct-payment'
            },
            'new_order': {
                icon: 'fa-utensils',
                label: 'Đơn mới',
                btnClass: 'btn-order',
                btnIcon: 'fa-clipboard-check',
                btnText: 'XÁC NHẬN ĐƠN HÀNG',
                tagClass: 'ct-order'
            },
            'order_item': {
                icon: 'fa-plus-circle',
                label: 'Thêm món',
                btnClass: 'btn-info',
                btnIcon: 'fa-check-circle',
                btnText: 'XÁC NHẬN MÓN MỚI',
                tagClass: 'ct-item'
            },
            'support_request': {
                icon: 'fa-concierge-bell',
                label: 'Yêu cầu hỗ trợ',
                btnClass: 'btn-support',
                btnIcon: 'fa-hands-helping',
                btnText: 'ĐÃ HỖ TRỢ XONG',
                tagClass: 'ct-support'
            },
            'scan_qr': {
                icon: 'fa-qrcode',
                label: 'Quét QR',
                btnClass: 'btn-info',
                btnIcon: 'fa-check',
                btnText: 'XÁC NHẬN',
                tagClass: 'ct-scan'
            }
        };
        return configs[type] || {
            icon: 'fa-bell',
            label: 'Thông báo',
            btnClass: 'btn-action-primary',
            btnIcon: 'fa-check',
            btnText: 'XÁC NHẬN XỬ LÝ',
            tagClass: 'ct-default'
        };
    }

    function renderList(notifs) {
        if (notifs.length === 0) {
            let emptyMsg = 'Không có thông báo nào';
            if (currentFilter !== 'all') {
                const cfg = getTypeConfig(currentFilter);
                emptyMsg = `Không có thông báo "${cfg.label}" nào`;
            }
            if (currentStatus === 'unread') emptyMsg += ' chưa xử lý';
            if (currentStatus === 'read') emptyMsg += ' đã xử lý';
            
            listEl.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-bell-slash"></i>
                    <p>${emptyMsg}.</p>
                </div>
            `;
            return;
        }

        const fragment = document.createDocumentFragment();
        notifs.forEach((n, idx) => {
            const isUnread = !parseInt(n.is_read);
            const card = document.createElement('div');
            card.className = `noti-card ${isUnread ? 'unread' : 'read'} type-${n.notification_type}`;
            card.style.animationDelay = `${idx * 0.05}s`;
            
            const cfg = getTypeConfig(n.notification_type);

            // Build status badge for read items
            let statusBadge = '';
            if (!isUnread) {
                const readTime = n.read_at ? formatTimeAgo(new Date(n.read_at)) : '';
                statusBadge = `
                    <div class="card-status-done">
                        <i class="fas fa-check-circle"></i> Đã xử lý ${readTime ? '• ' + readTime : ''}
                    </div>
                `;
            }

            card.innerHTML = `
                <div class="card-icon"><i class="fas ${cfg.icon}"></i></div>
                <div class="card-content">
                    <div class="card-top">
                        <h3 class="card-title">${n.title}</h3>
                        <span class="card-time">${formatTimeAgo(new Date(n.created_at))}</span>
                    </div>
                    <p class="card-msg">${n.message}</p>
                    <div class="card-meta">
                        <span class="card-table-tag"><i class="fas fa-map-marker-alt"></i> ${n.table_area || ''} • ${n.table_name}</span>
                        <span class="card-type-tag ${cfg.tagClass}"><i class="fas ${cfg.icon}" style="font-size:0.6rem;"></i> ${cfg.label}</span>
                    </div>
                    
                    ${isUnread ? `
                    <div class="card-actions">
                        <button class="btn-action ${cfg.btnClass}" onclick="event.stopPropagation(); handleAction(${n.id}, ${n.table_id}, '${n.notification_type}', ${n.order_id || 0})">
                            <i class="fas ${cfg.btnIcon}"></i> ${cfg.btnText}
                        </button>
                        <button class="btn-action btn-action-secondary" onclick="event.stopPropagation(); goToTable(${n.table_id})">
                            <i class="fas fa-external-link-alt"></i> CHI TIẾT
                        </button>
                    </div>
                    ` : statusBadge}
                </div>
            `;

            card.onclick = () => goToTable(n.table_id);
            fragment.appendChild(card);
        });

        listEl.innerHTML = '';
        listEl.appendChild(fragment);
    }

    window.handleAction = async (id, tableId, type, orderId) => {
        try {
            const fd = new FormData();
            fd.append('id', id);
            
            // Đánh dấu đã đọc
            await fetch(`${BASE_URL}/api/notifications/mark-read`, { method: 'POST', body: fd });

            // Nếu là support_request, đồng thời resolve support 
            if (type === 'support_request') {
                const fd2 = new FormData();
                fd2.append('table_id', tableId);
                fd2.append('type', 'support');
                await fetch(`${BASE_URL}/api/notifications/resolve-support`, { method: 'POST', body: fd2 });
            }

            // Tuỳ type, chuyển hướng phù hợp
            if (type === 'payment_request') {
                // Chuyển thẳng tới trang order bàn, xử lý thanh toán
                if (window.showToast) showToast('Đang mở trang thanh toán...', 'info');
                window.location.href = `${BASE_URL}/orders?table_id=${tableId}`;
                return;
            }

            fetchNotifications(true);
            
            // Toast message tuỳ loại
            const msgs = {
                'new_order': 'Đã xác nhận đơn hàng mới!',
                'order_item': 'Đã xác nhận thêm món!',
                'support_request': 'Đã đánh dấu hỗ trợ xong!',
                'scan_qr': 'Đã xác nhận quét QR!',
            };
            if (window.showToast) showToast(msgs[type] || 'Đã xác nhận xử lý xong');
            
        } catch (e) {
            console.error(e);
            alert('Lỗi khi thực hiện thao tác: ' + e.message);
        }
    };

    window.goToTable = (tableId) => {
        window.location.href = `${BASE_URL}/orders?table_id=${tableId}`;
    };

    document.getElementById('btnMarkAllRead').onclick = async () => {
        if (!confirm('Xác nhận đánh dấu tất cả thông báo là đã xử lý?')) return;
        await fetch(`${BASE_URL}/api/notifications/mark-read`, { method: 'POST' });
        fetchNotifications(true);
        if (window.showToast) showToast('Đã đánh dấu tất cả là đã xử lý');
    };

    // Type filters
    filterPills.forEach(pill => {
        pill.onclick = () => {
            filterPills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            currentFilter = pill.dataset.type;
            currentPage = 1;
            fetchNotifications();
        };
    });

    // Status filters
    statusPills.forEach(pill => {
        pill.onclick = () => {
            const wasActive = pill.classList.contains('active');
            statusPills.forEach(p => p.classList.remove('active'));
            if (!wasActive) {
                pill.classList.add('active');
                currentStatus = pill.dataset.status;
            } else {
                currentStatus = '';
            }
            currentPage = 1;
            fetchNotifications();
        };
    });

    btnPrevPage.onclick = () => {
        if (currentPage > 1) {
            currentPage--;
            fetchNotifications();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    };

    btnNextPage.onclick = () => {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            fetchNotifications();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    };

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
    // Poll every 5 seconds when on page 1
    setInterval(() => {
        if (currentPage === 1) {
            fetchNotifications(true);
        }
    }, 5000); 
});
</script>
