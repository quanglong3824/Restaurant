<?php // views/orders/history.php — Order History for Waiters/Admins
// Use pagination data from controller
$page = $currentPage ?? 1;
$limit = 6; // Items per page
$totalPages = $totalPages ?? 1;
$totalItems = $totalCount ?? 0;
$totalRevenueVal = $totalRevenue ?? 0;
$pagedOrders = $orders;
?>

<div class="history-page-wrapper">
    <div class="page-content-container">

        <!-- Header Section -->
        <div class="page-header-minimal mb-5">
            <div class="d-flex justify-content-between align-items-end">
                <div>
                    <h1 class="playfair fw-900 mb-2">Lịch sử giao dịch</h1>
                    <p class="text-muted-gold mb-0"><i class="fas fa-layer-group me-2"></i> Hệ thống quản lý hóa đơn Aurora Hotel Plaza</p>
                </div>
                <div class="results-counter">
                    <span class="count-badge"><?= number_format($totalItems) ?></span>
                    <span class="count-label">Hóa đơn</span>
                </div>
            </div>
        </div>

        <!-- Filter Section - Refined Glassmorphism -->
        <section class="filter-section mb-5">
            <div class="filter-glass-card">
                <form action="<?= BASE_URL ?>/orders/history" method="GET" id="historyFilterForm">
                    <div class="row g-4">
                        <div class="col-lg-5">
                            <label class="filter-label">Chế độ xem</label>
                            <div class="filter-type-group">
                                <button type="button" class="type-btn <?= $filters['type'] === 'date' ? 'active' : '' ?>" onclick="setFilter('date')">Ngày</button>
                                <button type="button" class="type-btn <?= $filters['type'] === 'week' ? 'active' : '' ?>" onclick="setFilter('week')">Tuần</button>
                                <button type="button" class="type-btn <?= $filters['type'] === 'month' ? 'active' : '' ?>" onclick="setFilter('month')">Tháng</button>
                            </div>
                            <input type="hidden" name="filter_type" id="filter_type" value="<?= e($filters['type']) ?>">
                        </div>

                        <div class="col-lg-7">
                            <div class="row g-3 align-items-end">
                                <?php if ($filters['type'] === 'date'): ?>
                                    <div class="col-sm-8">
                                        <label class="filter-label">Chọn ngày cụ thể</label>
                                        <div class="input-premium-wrapper">
                                            <i class="fas fa-calendar-alt icon"></i>
                                            <input type="date" name="date" class="input-premium" value="<?= e($filters['date']) ?>" onchange="this.form.submit()">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn-premium-gold w-100">CẬP NHẬT</button>
                                    </div>
                                <?php elseif ($filters['type'] === 'week'): ?>
                                    <div class="col-sm-4">
                                        <label class="filter-label">Số tuần</label>
                                        <input type="number" name="week" class="input-premium" min="1" max="53" value="<?= e($filters['week']) ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="filter-label">Năm</label>
                                        <input type="number" name="year" class="input-premium" value="<?= e($filters['year']) ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn-premium-gold w-100">LỌC TUẦN</button>
                                    </div>
                                <?php elseif ($filters['type'] === 'month'): ?>
                                    <div class="col-sm-4">
                                        <label class="filter-label">Chọn tháng</label>
                                        <select name="month" class="input-premium">
                                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                                <option value="<?= $m ?>" <?= (int) $filters['month'] === $m ? 'selected' : '' ?>>Tháng <?= $m ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="filter-label">Năm</label>
                                        <input type="number" name="year" class="input-premium" value="<?= e($filters['year']) ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn-premium-gold w-100">LỌC THÁNG</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- Insights / Summary Row -->
        <section class="summary-section mb-5">
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
                        <span class="val"><?= number_format($totalItems) ?></span>
                        <span class="lbl">Giao dịch</span>
                    </div>
                    <div class="stat-mini-card">
                        <span class="val"><?= count($pagedOrders) ?></span>
                        <span class="lbl">Trang này</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Listing -->
        <section class="history-list-section">
            <div class="section-title-row mb-4">
                <h3 class="playfair fw-800"><i class="fas fa-stream text-gold me-2"></i> Dòng thời gian giao dịch</h3>
                <div class="pagination-info">Hiển thị hóa đơn từ <?= (($page - 1) * $limit) + 1 ?> đến <?= min($page * $limit, $totalItems) ?></div>
            </div>

            <?php if (empty($pagedOrders)): ?>
                <div class="empty-state-box py-5">
                    <div class="empty-icon-circle mb-4">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4 class="fw-bold">Không có dữ liệu hóa đơn</h4>
                    <p class="text-muted">Vui lòng điều chỉnh bộ lọc để tìm kiếm các giao dịch khác.</p>
                </div>
            <?php else: ?>
                <div class="history-premium-grid">
                    <?php foreach ($pagedOrders as $order): ?>
                        <div class="premium-order-card">
                            <div class="card-top-accent"></div>
                            <div class="card-inner">
                                <div class="order-header">
                                    <div class="table-avatar">
                                        <span class="num"><?= e(str_replace('Bàn ', '', $order['table_name'])) ?></span>
                                    </div>
                                    <div class="order-meta">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="m-0 fw-800 text-dark"><?= e($order['table_name']) ?></h5>
                                            <span class="order-id">#<?= $order['id'] ?></span>
                                        </div>
                                        <div class="timestamp">
                                            <i class="far fa-calendar-check me-1"></i> <?= date('d/m/Y', strtotime($order['closed_at'])) ?>
                                            <span class="separator"></span>
                                            <i class="far fa-clock me-1"></i> <?= date('H:i', strtotime($order['closed_at'])) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="order-details-summary my-4">
                                    <div class="detail-row">
                                        <span class="label">Nhân viên</span>
                                        <span class="value"><?= e($order['waiter_name'] ?? 'Hệ thống') ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Thanh toán</span>
                                        <span class="value-badge"><?= strtoupper(e($order['payment_method'])) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Sản phẩm</span>
                                        <span class="value"><?= $order['item_count'] ?> món ăn</span>
                                    </div>
                                </div>

                                <div class="order-pricing border-top pt-4 mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="pricing-label">TỔNG TIỀN</span>
                                        <span class="pricing-value"><?= formatPrice($order['total']) ?></span>
                                    </div>
                                </div>

                                <div class="order-actions gap-2">
                                    <button type="button" class="btn-action-view view-details-btn" 
                                        data-order-id="<?= $order['id'] ?>" 
                                        data-order-data="<?= htmlspecialchars(json_encode($order)) ?>">
                                        <i class="fas fa-expand-arrows-alt me-2"></i> CHI TIẾT
                                    </button>
                                    <a href="<?= BASE_URL ?>/orders/print?order_id=<?= $order['id'] ?>" target="_blank" class="btn-action-print">
                                        <i class="fas fa-print me-2"></i> IN LẠI
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination UI -->
                <?php if ($totalPages > 1): ?>
                <div class="pagination-scroller mt-5">
                    <nav class="pagination-luxury">
                        <?php 
                        $queryParams = $_GET;
                        unset($queryParams['page']);
                        $queryString = http_build_query($queryParams);
                        $baseUrl = BASE_URL . '/orders/history?' . ($queryString ? $queryString . '&' : '');
                        ?>
                        
                        <a href="<?= $page <= 1 ? 'javascript:void(0)' : $baseUrl . 'page=' . ($page - 1) ?>" class="pag-btn <?= $page <= 1 ? 'disabled' : '' ?>">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        
                        <div class="pag-numbers">
                            <?php 
                            $startPage = max(1, $page - 1);
                            $endPage = min($totalPages, $page + 1);
                            
                            if ($startPage > 1) {
                                echo '<a href="'.$baseUrl.'page=1" class="pag-link">1</a>';
                                if ($startPage > 2) echo '<span class="pag-dots">...</span>';
                            }
                            
                            for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <a href="<?= $baseUrl ?>page=<?= $i ?>" class="pag-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>
                            
                            <?php 
                            if ($endPage < $totalPages) {
                                if ($endPage < $totalPages - 1) echo '<span class="pag-dots">...</span>';
                                echo '<a href="'.$baseUrl.'page='.$totalPages.'" class="pag-link">'.$totalPages.'</a>';
                            }
                            ?>
                        </div>
                        
                        <a href="<?= $page >= $totalPages ? 'javascript:void(0)' : $baseUrl . 'page=' . ($page + 1) ?>" class="pag-btn <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </nav>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </div>
</div>

<!-- Modal: Premium Detail -->
<div class="modal-backdrop" id="modalOrderDetails">
    <div class="modal modal-luxury-detail">
        <div class="modal-header-premium">
            <div class="header-icon"><i class="fas fa-file-invoice-dollar"></i></div>
            <div class="header-text">
                <h3>Chi tiết hóa đơn</h3>
                <p id="modalOrderSubtitle">Thông tin giao dịch khách hàng</p>
            </div>
            <button class="close-btn-minimal" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body-premium" id="modalOrderBody">
            <!-- Loading state -->
            <div class="text-center py-5">
                <div class="spinner-premium mb-3"></div>
                <p class="text-muted fw-600">Đang chuẩn bị dữ liệu...</p>
            </div>
        </div>
        <div class="modal-footer-premium">
            <button type="button" class="btn-modal-close" data-modal-close>ĐÓNG LẠI</button>
            <a href="" target="_blank" class="btn-modal-print" id="btnPrintOrder">
                <i class="fas fa-print me-2"></i> IN HÓA ĐƠN
            </a>
        </div>
    </div>
</div>

<style>
    /* Premium Architecture for History View */
    :root {
        --history-bg: #f8fafc;
        --card-bg: #ffffff;
        --gold-primary: #b8860b;
        --gold-light-alpha: rgba(184, 134, 11, 0.08);
        --text-dark: #0f172a;
        --text-slate: #64748b;
        --radius-premium: 24px;
        --shadow-premium: 0 10px 40px -10px rgba(0, 0, 0, 0.08);
        --shadow-card-hover: 0 20px 50px -12px rgba(0, 0, 0, 0.15);
    }

    .history-page-wrapper {
        background-color: var(--history-bg);
        min-height: 100vh;
        padding: 2.5rem 1.5rem 6rem; /* Top, Sides, Bottom spacing */
    }

    .page-content-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Page Header */
    .page-header-minimal .playfair { font-size: 2.5rem; letter-spacing: -1px; }
    .text-muted-gold { color: #94a3b8; font-weight: 600; font-size: 0.95rem; }
    
    .results-counter {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .count-badge {
        background: var(--gold-primary);
        color: white;
        padding: 0.2rem 1rem;
        border-radius: 12px;
        font-weight: 800;
        font-size: 1.5rem;
    }
    .count-label { font-size: 0.7rem; font-weight: 800; color: var(--text-slate); text-uppercase; margin-top: 4px; letter-spacing: 1px;}

    /* Filter Card - The "Breathable" Design */
    .filter-glass-card {
        background: var(--card-bg);
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-premium);
        padding: 2.5rem;
        box-shadow: var(--shadow-premium);
    }

    .filter-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--text-slate);
        text-uppercase;
        margin-bottom: 0.75rem;
        display: block;
        letter-spacing: 1px;
    }

    .filter-type-group {
        display: flex;
        background: #f1f5f9;
        padding: 0.5rem;
        border-radius: 100px;
        gap: 0.25rem;
    }

    .type-btn {
        flex: 1;
        border: none;
        background: transparent;
        padding: 0.75rem 1rem;
        border-radius: 100px;
        font-weight: 700;
        color: var(--text-slate);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.9rem;
    }

    .type-btn.active {
        background: white;
        color: var(--gold-primary);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .input-premium-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-premium-wrapper .icon {
        position: absolute;
        left: 1.25rem;
        color: var(--gold-primary);
        font-size: 1.1rem;
    }
    .input-premium {
        width: 100%;
        height: 56px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 0 1.25rem;
        padding-left: 3rem;
        font-weight: 700;
        color: var(--text-dark);
        transition: all 0.3s;
    }
    select.input-premium { padding-left: 1.25rem; appearance: none; }
    .input-premium:focus {
        border-color: var(--gold-primary);
        background: white;
        box-shadow: 0 0 0 4px var(--gold-light-alpha);
        outline: none;
    }

    .btn-premium-gold {
        height: 56px;
        background: linear-gradient(135deg, #d4af37 0%, #b8860b 100%);
        color: white;
        border: none;
        border-radius: 16px;
        font-weight: 800;
        letter-spacing: 1px;
        transition: all 0.3s;
        box-shadow: 0 10px 20px -5px rgba(184, 134, 11, 0.4);
    }
    .btn-premium-gold:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px -5px rgba(184, 134, 11, 0.5);
    }

    /* Summary Section */
    .summary-row-premium {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 1.5rem;
    }
    @media (max-width: 991px) { .summary-row-premium { grid-template-columns: 1fr; } }

    .summary-main-card {
        background: var(--text-dark);
        border-radius: var(--radius-premium);
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        color: white;
        display: flex;
        align-items: center;
    }
    .summary-bg-icon {
        position: absolute;
        right: -5%;
        top: -10%;
        font-size: 8rem;
        color: rgba(255,255,255,0.03);
        transform: rotate(-15deg);
    }
    .summary-tag { color: var(--gold-primary); font-weight: 800; letter-spacing: 2px; font-size: 0.75rem; display: block; margin-bottom: 0.5rem;}
    .revenue-display { font-size: 3.5rem; font-weight: 800; margin: 0; font-family: 'Outfit', sans-serif; letter-spacing: -1.5px; }

    .summary-stats-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .stat-mini-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border: 1px solid #e2e8f0;
    }
    .stat-mini-card .val { font-size: 2rem; font-weight: 800; color: var(--text-dark); }
    .stat-mini-card .lbl { font-size: 0.75rem; font-weight: 700; color: var(--text-slate); text-uppercase; }

    /* History Grid - 2 Columns with "Breath" */
    .history-premium-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2.5rem; /* Increased spacing */
    }
    @media (max-width: 991px) { .history-premium-grid { grid-template-columns: 1fr; } }

    .premium-order-card {
        background: var(--card-bg);
        border-radius: 30px;
        position: relative;
        overflow: hidden;
        border: 1px solid #edf2f7;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    .premium-order-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-card-hover);
        border-color: var(--gold-primary);
    }
    .card-top-accent {
        height: 6px;
        background: linear-gradient(90deg, var(--gold-primary), #ffd700);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .premium-order-card:hover .card-top-accent { opacity: 1; }

    .card-inner { padding: 2.25rem; }

    .order-header { display: flex; gap: 1.5rem; align-items: center; }
    .table-avatar {
        width: 72px; height: 72px;
        background: #f1f5f9;
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem; font-weight: 900; color: var(--gold-primary);
        border: 2px solid #e2e8f0;
    }
    .order-meta { flex: 1; }
    .order-id { font-size: 0.8rem; font-weight: 800; color: #94a3b8; background: #f8fafc; padding: 0.25rem 0.75rem; border-radius: 8px; }
    .timestamp { font-size: 0.85rem; color: var(--text-slate); font-weight: 600; margin-top: 0.4rem; display: flex; align-items: center; }
    .separator { width: 4px; height: 4px; background: #cbd5e1; border-radius: 50%; margin: 0 10px; }

    .order-details-summary .detail-row {
        display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0;
    }
    .detail-row .label { color: #94a3b8; font-weight: 700; font-size: 0.85rem; }
    .detail-row .value { font-weight: 700; color: var(--text-dark); }
    .value-badge { background: #f1f5f9; padding: 0.2rem 0.8rem; border-radius: 6px; font-weight: 800; font-size: 0.75rem; color: var(--text-dark); }

    .pricing-label { font-weight: 800; color: #94a3b8; font-size: 0.75rem; letter-spacing: 1px; }
    .pricing-value { font-size: 1.75rem; font-weight: 900; color: var(--gold-primary); font-family: 'Outfit'; }

    .order-actions { display: flex; }
    .btn-action-view, .btn-action-print {
        flex: 1; height: 52px; border: 1px solid #e2e8f0; border-radius: 14px;
        font-weight: 800; font-size: 0.85rem; transition: all 0.3s;
        display: flex; align-items: center; justify-content: center;
        background: white; text-decoration: none !important;
    }
    .btn-action-view { color: var(--text-dark); }
    .btn-action-view:hover { background: #f8fafc; border-color: var(--text-dark); transform: scale(1.02); }
    .btn-action-print { color: var(--gold-primary); }
    .btn-action-print:hover { background: var(--gold-light-alpha); border-color: var(--gold-primary); transform: scale(1.02); }

    /* Luxury Pagination */
    .pagination-luxury { display: flex; align-items: center; justify-content: center; gap: 1rem; }
    .pag-btn {
        width: 56px; height: 56px; background: white; border: 1px solid #e2e8f0; border-radius: 18px;
        display: flex; align-items: center; justify-content: center; color: var(--text-dark);
        transition: all 0.3s; text-decoration: none !important;
    }
    .pag-btn:hover:not(.disabled) { background: var(--text-dark); color: white; border-color: var(--text-dark); transform: translateY(-3px); }
    .pag-btn.disabled { opacity: 0.3; cursor: not-allowed; }
    
    .pag-numbers { display: flex; align-items: center; background: white; padding: 0.4rem; border-radius: 20px; border: 1px solid #e2e8f0; }
    .pag-link {
        width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;
        border-radius: 14px; font-weight: 800; color: var(--text-slate); transition: all 0.3s;
        text-decoration: none !important;
    }
    .pag-link.active { background: var(--gold-primary); color: white; box-shadow: 0 8px 15px rgba(184, 134, 11, 0.3); }
    .pag-link:hover:not(.active) { color: var(--gold-primary); background: var(--gold-light-alpha); }
    .pag-dots { padding: 0 0.5rem; color: #cbd5e1; font-weight: 800; }

    /* Modal - Ultra Luxury */
    .modal-luxury-detail {
        background: white; border-radius: 32px; overflow: hidden;
        box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.3);
        max-width: 650px; width: 95%; margin: 2rem auto;
    }
    .modal-header-premium {
        padding: 2.5rem; background: #f8fafc; border-bottom: 1px solid #edf2f7;
        display: flex; align-items: center; position: relative;
    }
    .header-icon {
        width: 64px; height: 64px; background: var(--gold-light-alpha); border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem; color: var(--gold-primary); margin-right: 1.5rem;
    }
    .header-text h3 { margin: 0; font-weight: 900; font-size: 1.75rem; color: var(--text-dark); font-family: 'Playfair Display'; }
    .header-text p { margin: 0; color: var(--text-slate); font-weight: 600; font-size: 0.9rem; }
    .close-btn-minimal {
        position: absolute; top: 2rem; right: 2rem; border: none; background: #f1f5f9;
        width: 40px; height: 40px; border-radius: 50%; color: var(--text-slate); transition: all 0.2s;
    }
    .close-btn-minimal:hover { background: #e2e8f0; color: var(--text-dark); transform: rotate(90deg); }

    .modal-body-premium { padding: 2.5rem; }

    .modal-footer-premium {
        padding: 1.5rem 2.5rem 2.5rem; display: flex; gap: 1rem;
    }
    .btn-modal-close {
        flex: 1; height: 56px; border: 1px solid #e2e8f0; border-radius: 16px;
        font-weight: 800; color: var(--text-slate); background: white; transition: all 0.3s;
    }
    .btn-modal-close:hover { background: #f8fafc; color: var(--text-dark); border-color: var(--text-dark); }
    .btn-modal-print {
        flex: 1.5; height: 56px; background: var(--text-dark); color: white; border: none;
        border-radius: 16px; font-weight: 800; display: flex; align-items: center; justify-content: center;
        text-decoration: none !important; transition: all 0.3s;
    }
    .btn-modal-print:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }

    .spinner-premium {
        width: 50px; height: 50px; border: 4px solid #f1f5f9; border-top: 4px solid var(--gold-primary);
        border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    /* Empty State */
    .empty-state-box {
        background: white; border-radius: 30px; text-align: center; border: 2px dashed #e2e8f0;
    }
    .empty-icon-circle {
        width: 100px; height: 100px; background: #f8fafc; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; margin: 0 auto;
        font-size: 3rem; color: #cbd5e1;
    }

    /* Additional polish for tables in modal */
    .modal-table-premium { width: 100%; border-collapse: collapse; }
    .modal-table-premium th { text-align: left; padding: 1rem; color: #94a3b8; font-size: 0.75rem; text-uppercase; font-weight: 800; letter-spacing: 1px; }
    .modal-table-premium td { padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; }
</style>

<script>
    // Load order details when modal opens
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const orderData = JSON.parse(this.getAttribute('data-order-data'));
            
            const modalBody = document.getElementById('modalOrderBody');
            const printBtn = document.getElementById('btnPrintOrder');
            const subtitle = document.getElementById('modalOrderSubtitle');
            
            printBtn.href = '<?= BASE_URL ?>/orders/print?order_id=' + orderId;
            subtitle.innerHTML = `<i class="fas fa-tag me-1"></i> Bàn: <strong>${orderData.table_name}</strong> | <i class="far fa-clock ms-2 me-1"></i> ${new Date(orderData.closed_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}`;

            let html = `
                <div class="modal-pricing-header d-flex justify-content-between align-items-center mb-4 p-4" style="background: #f8fafc; border-radius: 20px;">
                    <div>
                        <span class="d-block text-muted small fw-800">TỔNG THANH TOÁN</span>
                        <h2 class="m-0 fw-900 text-gold" style="font-size: 2.25rem;">${formatPrice(orderData.total || 0)}</h2>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-white text-dark border px-3 py-2 fw-800" style="border-radius: 10px;">${orderData.payment_method.toUpperCase()}</span>
                    </div>
                </div>
                
                <table class="modal-table-premium w-100">
                    <thead>
                        <tr>
                            <th width="15%">SL</th>
                            <th width="55%">MÓN ĂN</th>
                            <th width="30%" class="text-end">TỔNG</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="spinner-premium" style="width: 30px; height: 30px;"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="modal-summary-footer mt-4 p-4" style="background: #fff; border: 1px solid #f1f5f9; border-radius: 20px;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fw-bold">Tổng số lượng</span>
                        <span class="fw-bold text-dark">${orderData.item_count} món</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted fw-bold">Nhân viên phục vụ</span>
                        <span class="fw-bold text-dark">${orderData.waiter_name || 'Hệ thống'}</span>
                    </div>
                </div>
            `;
            modalBody.innerHTML = html;
            
            fetch('<?= BASE_URL ?>/orders/get-detail/' + orderId)
                .then(res => res.json())
                .then(data => {
                    if (data.ok && data.items) {
                        let itemsHtml = '';
                        data.items.forEach(item => {
                            itemsHtml += `
                                <tr>
                                    <td class="fw-bold text-dark" style="font-size: 1.1rem;">x${item.quantity}</td>
                                    <td>
                                        <div class="fw-bold text-dark">${item.item_name}</div>
                                        <div class="text-muted small">${item.price_fmt}</div>
                                    </td>
                                    <td class="text-end fw-bold text-gold" style="font-size: 1.1rem;">${item.subtotal_fmt}</td>
                                </tr>
                            `;
                        });
                        document.querySelector('.modal-table-premium tbody').innerHTML = itemsHtml;
                    }
                })
                .catch(err => console.error('Error loading order details:', err));
            
            Aurora.openModal('modalOrderDetails');
        });
    });

    function setFilter(type) {
        document.getElementById('filter_type').value = type;
        const form = document.getElementById('historyFilterForm');
        form.submit();
    }
</script>
