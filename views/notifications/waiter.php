<?php // views/notifications/waiter.php — Waiter Notification Center ?>
<div class="noti-center-container">
    <div class="noti-header animate-fade-in-down">
        <div class="header-main">
            <h2 class="playfair">Trung tâm điều hành</h2>
            <p class="text-muted small">Cập nhật yêu cầu từ khách hàng thời gian thực</p>
        </div>
        <div class="header-actions">
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
        <div class="filter-divider"></div>
        <div class="filter-pill status-pill" data-status="unread">
            <i class="fas fa-envelope"></i> Chưa xử lý
        </div>
        <div class="filter-pill status-pill" data-status="read">
            <i class="fas fa-envelope-open"></i> Đã xử lý
        </div>
    </div>

    <!-- Main List -->
    <div id="notificationListContainer" class="noti-list animate-fade-in-up">
        <div class="loading-state py-5 text-center">
            <div class="spinner"></div>
            <p class="text-muted mt-3">Đang kết nối trung tâm...</p>
        </div>
    </div>

    <!-- Pagination Controls -->
    <div id="paginationControls" class="pagination-scroller mt-4" style="display: none; padding-bottom: 100px;">
        <nav class="pagination-luxury">
            <button id="btnPrevPage" class="pag-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div id="pageInfo" class="pag-numbers">Trang 1</div>
            <button id="btnNextPage" class="pag-btn">
                <i class="fas fa-chevron-right"></i>
            </button>
        </nav>
    </div>
</div>

<!-- Audio element -->
<audio id="notiSoundDefault" preload="auto" src="<?= BASE_URL ?>/public/audio/nofi.mp3"></audio>