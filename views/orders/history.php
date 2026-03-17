<?php
// views/orders/history.php — Order History for Waiters/Admins
$page = $currentPage ?? 1;
$limit = 10;
$totalPages = $totalPages ?? 1;
$totalItems = $totalCount ?? 0;
$totalRevenueVal = $totalRevenue ?? 0;
$pagedOrders = $orders;

$filters = [
    'type' => $_GET['filter_type'] ?? 'date',
    'date' => $_GET['date'] ?? date('Y-m-d'),
    'week' => $_GET['week'] ?? date('W'),
    'month' => $_GET['month'] ?? date('n'),
    'year' => $_GET['year'] ?? date('Y'),
];
?>

<div class="history-page-wrapper">
    <div class="page-content-container">

        <!-- Header Section -->
        <div class="page-header-minimal mb-4">
            <div>
                <div class="header-breadcrumb mb-2">
                    <span class="text-muted">Quản lý</span>
                    <i class="fas fa-chevron-right mx-2 text-muted"></i>
                    <span class="text-gold fw-bold">Lịch sử</span>
                </div>
                <h1 class="playfair fw-900 mb-1">Lịch sử giao dịch</h1>
                <p class="text-muted mb-0"><i class="fas fa-layer-group me-1" style="color: var(--gold)"></i> Hệ thống quản lý hóa đơn</p>
            </div>
            <div class="header-stats">
                <div class="total-revenue-display">
                    <div class="revenue-amount"><?= formatPrice($totalRevenueVal) ?></div>
                    <div class="revenue-label">Tổng doanh thu</div>
                </div>
                <div class="results-counter">
                    <span class="count-badge"><?= number_format($totalItems) ?></span>
                    <span class="count-label">Hóa đơn</span>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <section class="filter-section mb-4">
            <div class="filter-glass-card">
                <form action="<?= BASE_URL ?>/orders/history" method="GET" id="historyFilterForm">
                    <div class="row g-3">
                        <div class="col-lg-3">
                            <div class="filter-type-group">
                                <button type="button" class="type-btn <?= $filters['type'] === 'date' ? 'active' : '' ?>" onclick="setFilter('date')">Ngày</button>
                                <button type="button" class="type-btn <?= $filters['type'] === 'week' ? 'active' : '' ?>" onclick="setFilter('week')">Tuần</button>
                                <button type="button" class="type-btn <?= $filters['type'] === 'month' ? 'active' : '' ?>" onclick="setFilter('month')">Tháng</button>
                            </div>
                            <input type="hidden" name="filter_type" id="filter_type" value="<?= e($filters['type']) ?>">
                        </div>

                        <div class="col-lg-9">
                            <div class="row g-2 align-items-end">
                                <?php if ($filters['type'] === 'date'): ?>
                                    <div class="col-sm-7">
                                        <div class="input-premium-wrapper">
                                            <i class="fas fa-calendar-alt icon"></i>
                                            <input type="date" name="date" class="input-premium" value="<?= e($filters['date']) ?>" onchange="this.form.submit()">
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <button type="submit" class="btn-premium-gold w-100">Cập nhật</button>
                                    </div>
                                <?php elseif ($filters['type'] === 'week'): ?>
                                    <div class="col-4">
                                        <input type="number" name="week" class="input-premium" min="1" max="53" value="<?= e($filters['week']) ?>" placeholder="Tuần">
                                    </div>
                                    <div class="col-4">
                                        <input type="number" name="year" class="input-premium" value="<?= e($filters['year']) ?>" placeholder="Năm">
                                    </div>
                                    <div class="col-5">
                                        <button type="submit" class="btn-premium-gold w-100">Lọc</button>
                                    </div>
                                <?php elseif ($filters['type'] === 'month'): ?>
                                    <div class="col-4">
                                        <select name="month" class="input-premium">
                                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                                <option value="<?= $m ?>" <?= (int) $filters['month'] === $m ? 'selected' : '' ?>>Tháng <?= $m ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <input type="number" name="year" class="input-premium" value="<?= e($filters['year']) ?>" placeholder="Năm">
                                    </div>
                                    <div class="col-3">
                                        <button type="submit" class="btn-premium-gold w-100">Lọc</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- Summary Section -->
        <section class="summary-section mb-4">
            <div class="summary-row-premium">
                <div class="summary-main-card">
                    <div class="summary-bg-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="summary-content">
                        <span class="summary-tag">DOANH THU TỔNG KỲ</span>
                        <h2 class="revenue-display"><?= formatPrice($totalRevenueVal) ?></h2>
                    </div>
                </div>
                <div class="summary-stats-group">
                    <div class="stat-mini-card">
                        <div class="stat-icon text-success"><i class="fas fa-receipt"></i></div>
                        <span class="val"><?= number_format($totalItems) ?></span>
                        <span class="lbl">Giao dịch</span>
                    </div>
                    <div class="stat-mini-card">
                        <div class="stat-icon text-gold"><i class="fas fa-file-invoice"></i></div>
                        <span class="val"><?= count($pagedOrders) ?></span>
                        <span class="lbl">Trang này</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Listing -->
        <section class="history-list-section">
            <div class="section-title-row mb-3">
                <h3 class="fw-800"><i class="fas fa-stream text-gold me-2"></i> Dòng thời gian</h3>
                <div class="pagination-info">Hiển thị <?= (($page - 1) * $limit) + 1 ?> - <?= min($page * $limit, $totalItems) ?></div>
            </div>

            <?php if (empty($pagedOrders)): ?>
                <div class="empty-state-box">
                    <div class="empty-icon-circle mb-3"><i class="fas fa-inbox"></i></div>
                    <h4 class="fw-bold">Không có dữ liệu</h4>
                    <p class="text-muted">Vui lòng điều chỉnh bộ lọc.</p>
                </div>
            <?php else: ?>
                <div class="history-premium-grid">
                    <?php foreach ($pagedOrders as $order): ?>
                        <div class="premium-order-card">
                            <div class="card-top-accent"></div>
                            <div class="card-inner">
                                <div class="order-header">
                                    <div class="table-avatar">
                                        <?= e(str_replace('Bàn ', '', $order['table_name'])) ?>
                                    </div>
                                    <div class="order-meta">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h5 class="m-0 fw-800"><?= e($order['table_name']) ?></h5>
                                            <span class="order-id"><?= sprintf('%06d', $order['id']) ?></span>
                                        </div>
                                        <div class="timestamp">
                                            <span class="timestamp-item"><i class="far fa-calendar-check me-1" style="color: var(--success)"></i> <?= date('d/m/Y', strtotime($order['closed_at'])) ?></span>
                                            <span class="separator"></span>
                                            <span class="timestamp-item"><i class="far fa-clock me-1" style="color: var(--gold)"></i> <?= date('H:i', strtotime($order['closed_at'])) ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="order-details-summary my-3">
                                    <div class="detail-row">
                                        <span class="detail-icon"><i class="fas fa-user-tie"></i></span>
                                        <div class="detail-value-group">
                                            <span class="label">Nhân viên</span>
                                            <span class="value"><?= e($order['waiter_name'] ?? 'Hệ thống') ?></span>
                                        </div>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-icon"><i class="fas fa-credit-card"></i></span>
                                        <div class="detail-value-group">
                                            <span class="label">Thanh toán</span>
                                            <span class="value-badge" style="text-transform: uppercase;"><?= strtoupper(e($order['payment_method'])) ?></span>
                                        </div>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-icon"><i class="fas fa-utensils"></i></span>
                                        <div class="detail-value-group">
                                            <span class="label">Sản phẩm</span>
                                            <span class="value"><?= $order['item_count'] ?> món</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="order-pricing border-top pt-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="pricing-label">TỔNG TIỀN</span>
                                        <span class="pricing-value"><?= formatPrice($order['total']) ?></span>
                                    </div>
                                </div>

                                <div class="order-actions gap-2">
                                    <button type="button" class="btn-action-view view-details-btn" 
                                        data-order-id="<?= $order['id'] ?>" 
                                        data-order-data="<?= htmlspecialchars(json_encode($order)) ?>">
                                        <i class="fas fa-eye me-1"></i> CHI TIẾT
                                    </button>
                                    <a href="<?= BASE_URL ?>/orders/print?order_id=<?= $order['id'] ?>" target="_blank" class="btn-action-print">
                                        <i class="fas fa-print me-1"></i> IN
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination-scroller mt-4">
                        <nav class="pagination-luxury">
                            <?php
                            $queryParams = $_GET;
                            unset($queryParams['page']);
                            $queryString = http_build_query($queryParams);
                            $baseUrl = BASE_URL . '/orders/history?' . ($queryString ? $queryString . '&' : '');
                            ?>
                            <a href="<?= $page <= 1 ? 'javascript:void(0)' : $baseUrl . 'page=' . ($page - 1) ?>" class="pag-btn <?= $page <= 1 ? 'disabled' : '' ?>">
                                <i class="fas fa-chevron-left" style="font-size: 0.8rem;"></i>
                            </a>
                            <div class="pag-numbers">
                                <?php
                                $startPage = max(1, $page - 2);
                                $endPage = min($totalPages, $page + 2);
                                if ($startPage > 1) {
                                    echo '<a href="'.$baseUrl.'page=1" class="pag-link" style="width: 36px; height: 36px; font-size: 0.8rem;">1</a>';
                                    if ($startPage > 2) echo '<span class="pag-dots" style="font-size: 0.7rem;">...</span>';
                                }
                                for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <a href="<?= $baseUrl ?>page=<?= $i ?>" class="pag-link <?= $i === $page ? 'active' : '' ?>" style="width: 36px; height: 36px; font-size: 0.8rem;"><?= $i ?></a>
                                <?php endfor; ?>
                                <?php
                                if ($endPage < $totalPages) {
                                    if ($endPage < $totalPages - 2) echo '<span class="pag-dots" style="font-size: 0.7rem;">...</span>';
                                    echo '<a href="'.$baseUrl.'page='.$totalPages.'" class="pag-link" style="width: 36px; height: 36px; font-size: 0.8rem;">'.$totalPages.'</a>';
                                }
                                ?>
                            </div>
                            <a href="<?= $page >= $totalPages ? 'javascript:void(0)' : $baseUrl . 'page=' . ($page + 1) ?>" class="pag-btn <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <i class="fas fa-chevron-right" style="font-size: 0.8rem;"></i>
                            </a>
                        </nav>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </div>
</div>

<!-- Modal -->
<div class="modal-backdrop" id="modalOrderDetails">
    <div class="modal modal-luxury-detail">
        <div class="modal-header-premium">
            <div class="header-icon"><i class="fas fa-file-invoice-dollar"></i></div>
            <div class="header-text">
                <h3>Chi tiết hóa đơn</h3>
                <p id="modalOrderSubtitle">Thông tin giao dịch</p>
            </div>
            <button class="close-btn-minimal" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body-premium" id="modalOrderBody">
            <div class="text-center py-4">
                <div class="spinner-premium" style="width: 40px; height: 40px; border-width: 3px;"></div>
                <p class="text-muted">Đang chuẩn bị dữ liệu...</p>
            </div>
        </div>
        <div class="modal-footer-premium px-2" style="padding: 1rem 0.5rem; gap: 0.5rem;">
            <button type="button" class="btn-modal-close" data-modal-close>Đóng</button>
            <a href="" target="_blank" class="btn-modal-print" id="btnPrintOrder">IN</a>
        </div>
    </div>
</div>

<!-- Link CSS and JS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/history.css">
<script src="<?= BASE_URL ?>/public/js/orders/history.js" defer></script>
