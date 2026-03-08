<?php // views/orders/history.php — Order History for Waiters/Admins
// Use pagination data from controller
$page = $currentPage ?? 1;
$limit = 8; // Items per page
$totalPages = $totalPages ?? 1;
$totalItems = $totalCount ?? 0;
$totalRevenueVal = $totalRevenue ?? 0;
$pagedOrders = $orders;
?>

<div class="history-page-wrapper animate-fade-in">
    <div class="page-content-container">

        <!-- Header Section -->
        <div class="page-header-minimal mb-6">
            <div class="d-flex justify-content-between align-items-end">
                <div>
                    <div class="header-breadcrumb mb-2">
                        <span class="text-muted">Quản lý</span>
                        <i class="fas fa-chevron-right mx-2 text-muted"></i>
                        <span class="text-gold fw-bold">Lịch sử</span>
                    </div>
                    <h1 class="playfair fw-900 mb-2" style="font-size: 2.8rem; letter-spacing: -1px;">Lịch sử giao dịch</h1>
                    <p class="text-muted-gold mb-0"><i class="fas fa-layer-group me-2" style="color: var(--gold)"></i> Hệ thống quản lý hóa đơn Aurora Hotel Plaza</p>
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
        </div>

        <!-- Filter Section - Refined Glassmorphism -->
        <section class="filter-section mb-6">
            <div class="filter-glass-card animate-fade-in-up">
                <form action="<?= BASE_URL ?>/orders/history" method="GET" id="historyFilterForm">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="filter-type-group">
                                <button type="button" class="type-btn <?= $filters['type'] === 'date' ? 'active' : '' ?>" onclick="setFilter('date')">
                                    <div class="btn-icon"><i class="fas fa-calendar-day"></i></div>
                                    <div class="btn-label">Ngày</div>
                                </button>
                                <button type="button" class="type-btn <?= $filters['type'] === 'week' ? 'active' : '' ?>" onclick="setFilter('week')">
                                    <div class="btn-icon"><i class="fas fa-calendar-week"></i></div>
                                    <div class="btn-label">Tuần</div>
                                </button>
                                <button type="button" class="type-btn <?= $filters['type'] === 'month' ? 'active' : '' ?>" onclick="setFilter('month')">
                                    <div class="btn-icon"><i class="fas fa-calendar-alt"></i></div>
                                    <div class="btn-label">Tháng</div>
                                </button>
                            </div>
                            <input type="hidden" name="filter_type" id="filter_type" value="<?= e($filters['type']) ?>">
                        </div>

                        <div class="col-lg-8">
                            <div class="row g-3 align-items-end">
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
                                    <div class="col-4">
                                        <button type="submit" class="btn-premium-gold w-100">Lọc</button>
                                    </div>
                                <?php elseif ($filters['type'] === 'month'): ?>
                                    <div class="col-5">
                                        <select name="month" class="input-premium">
                                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                                <option value="<?= $m ?>" <?= (int) $filters['month'] === $m ? 'selected' : '' ?>>Tháng <?= $m ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
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

        <!-- Insights / Summary Row -->
        <section class="summary-section mb-6">
            <div class="summary-row-premium animate-scale-up">
                <div class="summary-main-card">
                    <div class="summary-bg-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="summary-content">
                        <span class="summary-tag">DOANH THU TỔNG KỲ</span>
                        <h2 class="revenue-display"><?= formatPrice($totalRevenueVal) ?></h2>
                    </div>
                </div>
                <div class="summary-stats-group">
                    <div class="stat-mini-card">
                        <div class="stat-icon"><i class="fas fa-receipt text-success"></i></div>
                        <span class="val"><?= number_format($totalItems) ?></span>
                        <span class="lbl">Giao dịch</span>
                    </div>
                    <div class="stat-mini-card">
                        <div class="stat-icon"><i class="fas fa-file-invoice text-gold"></i></div>
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
                <div class="pagination-info">Hiển thị từ <?= (($page - 1) * $limit) + 1 ?> đến <?= min($page * $limit, $totalItems) ?></div>
            </div>

            <?php if (empty($pagedOrders)): ?>
                <div class="empty-state-box animate-fade-in-up">
                    <div class="empty-icon-circle mb-4">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h4 class="fw-bold">Không có dữ liệu hóa đơn</h4>
                    <p class="text-muted mt-2">Vui lòng điều chỉnh bộ lọc hoặc chờ thêm dữ liệu mới.</p>
                </div>
            <?php else: ?>
                <div class="history-premium-grid">
                    <?php foreach ($pagedOrders as $order): ?>
                        <div class="premium-order-card animate-fade-in-up" style="animation-delay: <?= $loop->index * 0.05 ?>s">
                            <div class="card-top-accent"></div>
                            <div class="card-inner">
                                <div class="order-header">
                                    <div class="table-avatar">
                                        <span class="num"><?= e(str_replace('Bàn ', '', $order['table_name'])) ?></span>
                                    </div>
                                    <div class="order-meta">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h5 class="m-0 fw-800 text-dark"><?= e($order['table_name']) ?></h5>
                                            <span class="order-id">#<?= sprintf('%06d', $order['id']) ?></span>
                                        </div>
                                        <div class="timestamp">
                                            <span class="timestamp-item">
                                                <i class="far fa-calendar-check me-1 text-success"></i> <?= date('d/m/Y', strtotime($order['closed_at'])) ?>
                                            </span>
                                            <span class="separator"></span>
                                            <span class="timestamp-item">
                                                <i class="far fa-clock me-1 text-gold"></i> <?= date('H:i', strtotime($order['closed_at'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="order-details-summary my-4">
                                    <div class="detail-row">
                                        <span class="detail-icon"><i class="fas fa-user-tie text-muted"></i></span>
                                        <div class="detail-value-group">
                                            <span class="label">Nhân viên</span>
                                            <span class="value"><?= e($order['waiter_name'] ?? 'Hệ thống') ?></span>
                                        </div>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-icon"><i class="fas fa-credit-card text-muted"></i></span>
                                        <div class="detail-value-group">
                                            <span class="label">Thanh toán</span>
                                            <span class="value-badge"><?= strtoupper(e($order['payment_method'])) ?></span>
                                        </div>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-icon"><i class="fas fa-utensils text-muted"></i></span>
                                        <div class="detail-value-group">
                                            <span class="label">Sản phẩm</span>
                                            <span class="value"><?= $order['item_count'] ?> món ăn</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="order-pricing border-top pt-4 mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="pricing-label">TỔNG TIỀN THANH TOÁN</span>
                                        <span class="pricing-value"><?= formatPrice($order['total']) ?></span>
                                    </div>
                                </div>

                                <div class="order-actions gap-2">
                                    <button type="button" class="btn-action-view view-details-btn"
                                        data-order-id="<?= $order['id'] ?>"
                                        data-order-data="<?= htmlspecialchars(json_encode($order)) ?>">
                                        <i class="fas fa-eye me-2"></i> Xem chi tiết
                                    </button>
                                    <a href="<?= BASE_URL ?>/orders/print?order_id=<?= $order['id'] ?>" target="_blank" class="btn-action-print">
                                        <i class="fas fa-print me-2"></i> In hóa đơn
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination UI -->
                <?php if ($totalPages > 1): ?>
                <div class="pagination-scroller mt-6 animate-fade-in">
                    <nav class="pagination-luxury">
                        <?php
                        $queryParams = $_GET;
                        unset($queryParams['page']);
                        $queryString = http_build_query($queryParams);
                        $baseUrl = BASE_URL . '/orders/history?' . ($queryString ? $queryString . '&' : '');
                        ?>

                        <a href="<?= $page <= 1 ? 'javascript:void(0)' : $baseUrl . 'page=' . ($page - 1) ?>" class="pag-btn <?= $page <= 1 ? 'disabled' : '' ?>" aria-label="Trang trước">
                            <i class="fas fa-chevron-left"></i>
                        </a>

                        <div class="pag-numbers">
                            <?php
                            $startPage = max(1, $page - 2);
                            $endPage = min($totalPages, $page + 2);

                            if ($startPage > 1) {
                                echo '<a href="'.$baseUrl.'page=1" class="pag-link">1</a>';
                                if ($startPage > 2) echo '<span class="pag-dots">...</span>';
                            }

                            for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <a href="<?= $baseUrl ?>page=<?= $i ?>" class="pag-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <?php
                            if ($endPage < $totalPages) {
                                if ($endPage < $totalPages - 2) echo '<span class="pag-dots">...</span>';
                                echo '<a href="'.$baseUrl.'page='.$totalPages.'" class="pag-link">'.$totalPages.'</a>';
                            }
                            ?>
                        </div>

                        <a href="<?= $page >= $totalPages ? 'javascript:void(0)' : $baseUrl . 'page=' . ($page + 1) ?>" class="pag-btn <?= $page >= $totalPages ? 'disabled' : '' ?>" aria-label="Trang sau">
                            <i class="fas fa-chevron-right"></i>
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
            <button class="close-btn-minimal" data-modal-close type="button" aria-label="Đóng">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body-premium" id="modalOrderBody">
            <!-- Loading state -->
            <div class="text-center py-5">
                <div class="spinner-premium mb-3"></div>
                <p class="text-muted fw-600">Đang chuẩn bị dữ liệu...</p>
            </div>
        </div>
        <div class="modal-footer-premium">
            <button type="button" class="btn-modal-close" data-modal-close>Đóng lại</button>
            <a href="" target="_blank" class="btn-modal-print" id="btnPrintOrder">
                <i class="fas fa-print me-2"></i> In hóa đơn
            </a>
        </div>
    </div>
</div>

<style>
    /* Premium Architecture for History View */
    :root {
        --history-bg: #f8fafc;
        --card-bg: #ffffff;
        --gold-primary: #d4af37;
        --gold-secondary: #b89b5e;
        --gold-light-alpha: rgba(212, 175, 55, 0.1);
        --gold-text: #8e7037;
        --text-dark: #0f172a;
        --text-slate: #64748b;
        --text-muted: #94a3b8;
        --radius-premium: 24px;
        --radius-lg: 30px;
        --shadow-premium: 0 10px 40px -10px rgba(0, 0, 0, 0.06);
        --shadow-card-hover: 0 25px 60px -12px rgba(0, 0, 0, 0.1);
        --transition-smooth: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .history-page-wrapper {
        background-color: var(--history-bg);
        min-height: 100vh;
        padding: 3rem 1.5rem 8rem;
    }

    .page-content-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Page Header */
    .page-header-minimal {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    
    .page-header-minimal .playfair { 
        font-size: 2.8rem; 
        letter-spacing: -1px;
        background: linear-gradient(135deg, var(--text-dark) 0%, var(--gold-text) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .text-muted-gold { color: var(--text-muted); font-weight: 600; font-size: 0.95rem; }
    .header-breadcrumb { font-size: 0.85rem; font-weight: 600; }

    .header-stats { display: flex; gap: 2rem; align-items: flex-end; }
    
    .total-revenue-display { text-align: right; }
    .revenue-amount { 
        font-size: 2rem; 
        font-weight: 900; 
        color: var(--gold-primary); 
        font-family: 'Outfit', sans-serif;
        letter-spacing: -0.5px;
    }
    .revenue-label { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }

    .results-counter {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .count-badge {
        background: var(--gold-primary);
        color: white;
        padding: 0.4rem 1.2rem;
        border-radius: 12px;
        font-weight: 800;
        font-size: 1.3rem;
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
    }
    .count-label { font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-top: 4px; letter-spacing: 1px;}

    /* Filter Card */
    .filter-glass-card {
        background: var(--card-bg);
        border: 1px solid rgba(212, 175, 55, 0.15);
        border-radius: var(--radius-lg);
        padding: 2rem;
        box-shadow: var(--shadow-premium);
        transition: box-shadow 0.4s ease;
    }
    .filter-glass-card:hover {
        box-shadow: 0 15px 50px -10px rgba(0, 0, 0, 0.08);
    }

    .filter-type-group {
        display: flex;
        gap: 0.5rem;
    }

    .type-btn {
        flex: 1;
        border: 2px solid #e2e8f0;
        background: white;
        padding: 1rem 0.5rem;
        border-radius: 18px;
        font-weight: 700;
        color: var(--text-slate);
        transition: var(--transition-smooth);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .type-btn .btn-icon {
        font-size: 1.3rem;
        margin-bottom: 4px;
        transition: color 0.3s ease;
    }

    .type-btn .btn-label {
        font-size: 0.85rem;
        display: block;
    }

    .type-btn.active {
        background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-secondary) 100%);
        color: white;
        border-color: var(--gold-primary);
        box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
    }

    .type-btn.active .btn-icon { color: white; }

    .input-premium-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-premium-wrapper .icon {
        position: absolute;
        left: 1.25rem;
        color: var(--gold-secondary);
        font-size: 1.1rem;
        transition: color 0.3s ease;
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
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        font-size: 0.95rem;
    }
    .input-premium:focus {
        border-color: var(--gold-primary);
        background: white;
        box-shadow: 0 0 0 6px var(--gold-light-alpha);
        outline: none;
    }
    .input-premium-wrapper:hover .icon { color: var(--gold-primary); }

    .btn-premium-gold {
        height: 56px;
        background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-secondary) 100%);
        color: white;
        border: none;
        border-radius: 16px;
        font-weight: 800;
        letter-spacing: 1px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 10px 20px -5px rgba(212, 175, 55, 0.4);
    }
    .btn-premium-gold:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 35px -5px rgba(212, 175, 55, 0.5);
    }

    /* Summary Section */
    .summary-row-premium {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 2rem;
    }
    @media (max-width: 991px) { .summary-row-premium { grid-template-columns: 1fr; } }

    .summary-main-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: var(--radius-lg);
        padding: 3.5rem 2.5rem;
        position: relative;
        overflow: hidden;
        color: white;
        display: flex;
        align-items: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    .summary-bg-icon {
        position: absolute;
        right: -8%;
        top: -12%;
        font-size: 10rem;
        color: rgba(255,255,255,0.05);
        transform: rotate(-15deg);
        transition: transform 0.5s ease;
    }
    .summary-main-card:hover .summary-bg-icon {
        transform: rotate(-15deg) scale(1.1);
    }
    .summary-tag { color: var(--gold-primary); font-weight: 800; letter-spacing: 2px; font-size: 0.85rem; display: block; margin-bottom: 0.75rem;}
    .revenue-display { font-size: 4rem; font-weight: 900; margin: 0; font-family: 'Outfit', sans-serif; letter-spacing: -2px; line-height: 1; }

    .summary-stats-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }
    .stat-mini-card {
        background: var(--card-bg);
        border-radius: 24px;
        padding: 1.75rem 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border: 1px solid rgba(212, 175, 55, 0.15);
        box-shadow: 0 5px 20px rgba(0,0,0,0.04);
        transition: var(--transition-smooth);
        position: relative;
        overflow: hidden;
    }
    .stat-mini-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, var(--gold-light-alpha), transparent);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .stat-mini-card:hover { transform: translateY(-5px); }
    .stat-mini-card:hover::before { opacity: 1; }
    .stat-icon { width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; border-radius: 16px; margin-bottom: 1rem; }
    .stat-icon i { font-size: 1.5rem; }
    .stat-icon.text-success { background: #ecfdf5; color: #059669; }
    .stat-icon.text-gold { background: var(--gold-light-alpha); color: var(--gold-primary); }
    .stat-mini-card .val { font-size: 2.25rem; font-weight: 900; color: var(--text-dark); font-family: 'Outfit'; }
    .stat-mini-card .lbl { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }

    /* History Grid */
    .history-premium-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2.25rem;
    }
    @media (max-width: 991px) { .history-premium-grid { grid-template-columns: 1fr; } }

    .premium-order-card {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        position: relative;
        overflow: hidden;
        border: 1px solid #edf2f7;
        transition: var(--transition-smooth);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        cursor: pointer;
    }
    .premium-order-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-card-hover);
        border-color: var(--gold-primary);
    }
    .card-top-accent {
        height: 6px;
        background: linear-gradient(90deg, var(--gold-primary), var(--gold-secondary), #ffd700);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .premium-order-card:hover .card-top-accent { opacity: 1; }

    .card-inner { padding: 2.5rem; }

    .order-header { display: flex; gap: 1.5rem; align-items: center; }
    .table-avatar {
        width: 80px; height: 80px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 24px;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 900; color: var(--gold-primary);
        border: 2px solid rgba(212, 175, 55, 0.2);
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.1);
    }
    .premium-order-card:hover .table-avatar {
        transform: scale(1.05);
        border-color: var(--gold-primary);
        box-shadow: 0 12px 30px rgba(212, 175, 55, 0.2);
    }
    .order-meta { flex: 1; }
    .order-id { 
        font-size: 0.85rem; 
        font-weight: 800; 
        color: var(--text-muted); 
        background: #f8fafc; 
        padding: 0.35rem 1rem; 
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    .timestamp { font-size: 0.9rem; color: var(--text-slate); font-weight: 600; margin-top: 0.75rem; display: flex; align-items: center; flex-wrap: wrap; gap: 0.5rem; }
    .timestamp-item { display: flex; align-items: center; gap: 4px; transition: color 0.3s ease; }
    .premium-order-card:hover .timestamp-item { color: var(--gold-text); }
    .separator { width: 4px; height: 4px; background: #cbd5e1; border-radius: 50%; }

    .order-details-summary { margin: 2rem 0; }
    .detail-row {
        display: flex; justify-content: space-between; align-items: center; padding: 0.9rem 0;
        border-bottom: 1px solid #f1f5f9;
        transition: padding 0.3s ease;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-icon { font-size: 1.2rem; margin-right: 1rem; transition: color 0.3s ease; }
    .premium-order-card:hover .detail-icon { color: var(--gold-primary); }
    .detail-value-group { flex: 1; display: flex; flex-direction: column; }
    .detail-row .label { color: var(--text-muted); font-weight: 700; font-size: 0.85rem; letter-spacing: 0.5px; }
    .detail-row .value { font-weight: 700; color: var(--text-dark); font-size: 0.95rem; }
    .value-badge { 
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); 
        padding: 0.35rem 1rem; 
        border-radius: 12px; 
        font-weight: 800; 
        font-size: 0.75rem; 
        color: var(--text-dark);
        border: 1px solid #e2e8f0;
    }

    .order-pricing { margin-top: 2.5rem; }
    .pricing-label { font-weight: 800; color: var(--text-muted); font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase; }
    .pricing-value { font-size: 2rem; font-weight: 900; color: var(--gold-primary); font-family: 'Outfit'; letter-spacing: -1px; }

    .order-actions { display: flex; gap: 1rem; margin-top: 2.5rem; }
    .btn-action-view, .btn-action-print {
        flex: 1; height: 56px; border: 1px solid #e2e8f0; border-radius: 18px;
        font-weight: 800; font-size: 0.9rem; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: flex; align-items: center; justify-content: center;
        background: white; text-decoration: none !important;
        color: var(--text-dark);
    }
    .btn-action-view {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        color: var(--text-dark);
    }
    .btn-action-view:hover { 
        background: white; 
        border-color: var(--text-dark); 
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .btn-action-print { 
        color: var(--gold-primary);
        border-color: rgba(212, 175, 55, 0.2);
    }
    .btn-action-print:hover { 
        background: var(--gold-light-alpha);
        border-color: var(--gold-primary);
        transform: translateY(-3px);
        color: var(--gold-text);
    }

    /* Premium Pagination */
    .pagination-luxury { display: flex; align-items: center; justify-content: center; gap: 1rem; }
    .pag-btn {
        width: 56px; height: 56px; background: white; border: 1px solid #e2e8f0; border-radius: 18px;
        display: flex; align-items: center; justify-content: center; color: var(--text-dark);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); text-decoration: none !important;
        font-size: 1.1rem;
    }
    .pag-btn:hover:not(.disabled) { 
        background: var(--text-dark); 
        color: white; 
        border-color: var(--text-dark); 
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.2);
    }
    .pag-btn.disabled { opacity: 0.3; cursor: not-allowed; }

    .pag-numbers { display: flex; align-items: center; background: white; padding: 0.5rem; border-radius: 20px; border: 1px solid #e2e8f0; }
    .pag-link {
        width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;
        border-radius: 14px; font-weight: 800; color: var(--text-slate); transition: all 0.3s;
        text-decoration: none !important;
    }
    .pag-link.active { 
        background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-secondary) 100%); 
        color: white; 
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
        transform: scale(1.1);
    }
    .pag-link:hover:not(.active) { color: var(--gold-primary); background: var(--gold-light-alpha); }
    .pag-dots { padding: 0 0.5rem; color: #cbd5e1; font-weight: 800; }

    /* Modal - Ultra Luxury */
    .modal-luxury-detail {
        background: white; border-radius: 36px; overflow: hidden;
        box-shadow: 0 60px 120px -20px rgba(0, 0, 0, 0.3);
        max-width: 700px; width: 95%; margin: 2rem auto;
        animation: modalSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes modalSlideIn {
        from { opacity: 0; transform: translateY(50px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-header-premium {
        position: relative;
        padding: 2.5rem 3rem;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border-bottom: 1px solid rgba(212, 175, 55, 0.15);
        display: flex; align-items: center; position: relative;
    }
    .header-icon {
        width: 72px; height: 72px; background: linear-gradient(135deg, var(--gold-light-alpha) 0%, rgba(212, 175, 55, 0.1) 100%); border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; color: var(--gold-primary); margin-right: 2rem;
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.2);
    }
    .header-text h3 { margin: 0; font-weight: 900; font-size: 1.8rem; color: var(--text-dark); font-family: 'Playfair Display'; }
    .header-text p { margin: 0.5rem 0 0; color: var(--text-slate); font-weight: 600; font-size: 0.95rem; }
    .close-btn-minimal {
        position: absolute; top: 2rem; right: 2rem; border: none; background: #f1f5f9;
        width: 48px; height: 48px; border-radius: 50%; color: var(--text-slate); transition: all 0.3s;
        display: flex; align-items: center; justify-content: center;
    }
    .close-btn-minimal:hover { background: #e2e8f0; color: var(--text-dark); transform: rotate(90deg); }

    .modal-body-premium { padding: 3rem; }

    .modal-footer-premium {
        padding: 2rem 3rem; display: flex; gap: 1rem; border-top: 1px solid #f1f5f9;
    }
    .btn-modal-close {
        flex: 1; height: 56px; border: 1px solid #e2e8f0; border-radius: 18px;
        font-weight: 800; color: var(--text-slate); background: white; transition: all 0.3s;
        display: flex; align-items: center; justify-content: center;
    }
    .btn-modal-close:hover { background: #f8fafc; color: var(--text-dark); border-color: var(--text-dark); transform: translateY(-2px); }
    .btn-modal-print {
        flex: 1.5; height: 56px; background: var(--text-dark); color: white; border: none;
        border-radius: 18px; font-weight: 800; display: flex; align-items: center; justify-content: center;
        text-decoration: none !important; transition: all 0.3s;
    }
    .btn-modal-print:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(15, 23, 42, 0.3); }

    .spinner-premium {
        width: 60px; height: 60px; border: 5px solid #f1f5f9; border-top: 5px solid var(--gold-primary);
        border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;
        box-shadow: 0 0 30px rgba(212, 175, 55, 0.3);
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    /* Empty State */
    .empty-state-box {
        background: var(--card-bg); border-radius: 36px; text-align: center; border: 2px dashed #e2e8f0;
        padding: 4rem 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        transition: all 0.4s ease;
    }
    .empty-state-box:hover { border-color: var(--gold-primary); box-shadow: 0 15px 50px rgba(212, 175, 55, 0.1); }
    .empty-icon-circle {
        width: 120px; height: 120px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 50%;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;
        font-size: 3.5rem; color: #cbd5e1;
    }
    .empty-state-box h4 { font-weight: 800; color: var(--text-dark); margin-bottom: 1rem; }
    .empty-state-box .text-muted { color: var(--text-slate); font-size: 1.05rem; }

    /* Additional polish for tables in modal */
    .modal-table-premium { width: 100%; border-collapse: collapse; margin-top: 2rem; }
    .modal-table-premium th { text-align: left; padding: 1.25rem; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; font-weight: 800; letter-spacing: 1px; background: #f8fafc; }
    .modal-table-premium td { padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; }
    .modal-table-premium tbody tr:hover td { background: rgba(212, 175, 55, 0.08); }
    .modal-table-premium tbody tr:last-child td { border-bottom: none; }

    .section-title-row { display: flex; justify-content: space-between; align-items: flex-end; }
    .section-title-row h3 { font-size: 1.8rem; letter-spacing: -0.5px; }
    .pagination-info { color: var(--text-muted); font-weight: 600; font-size: 0.9rem; }

    @media (max-width: 768px) {
        .history-page-wrapper { padding: 2rem 1rem 7rem; }
        .page-header-minimal .playfair { font-size: 2rem; }
        .revenue-amount { font-size: 1.5rem; }
        .revenue-display { font-size: 2.8rem; }
        .summary-row-premium { grid-template-columns: 1fr; }
        .summary-stats-group { grid-template-columns: 1fr; }
        .header-stats { flex-direction: column; gap: 1.5rem; }
        .history-premium-grid { grid-template-columns: 1fr; }
        .premium-order-card:hover .table-avatar { transform: scale(1); }
        .stat-mini-card { padding: 1.5rem; }
        .modal-header-premium { padding: 2rem; }
        .modal-footer-premium { padding: 1.5rem 2rem; flex-direction: column; }
        .btn-modal-close, .btn-modal-print { width: 100%; }
    }
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
            subtitle.innerHTML = `<i class="fas fa-tag me-1" style="color: var(--gold)"></i> Bàn: <strong>${orderData.table_name}</strong> | <i class="far fa-clock ms-2 me-1" style="color: var(--text-muted)"></i> ${new Date(orderData.closed_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}`;

            let html = `
                <div class="modal-pricing-header d-flex justify-content-between align-items-center mb-5 p-4" style="background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); border-radius: 24px; border: 1px solid rgba(212, 175, 55, 0.15);">
                    <div>
                        <span class="d-block text-muted small fw-800" style="letter-spacing: 1px; text-transform: uppercase;">TỔNG THANH TOÁN</span>
                        <h2 class="m-0 fw-900 text-gold" style="font-size: 2.5rem; font-family: 'Outfit'; letter-spacing: -1px;">${formatPrice(orderData.total || 0)}</h2>
                    </div>
                    <div class="text-end">
                        <span class="badge" style="background: white; color: var(--text-dark); border: 2px solid #e2e8f0; padding: 0.6rem 1.5rem; border-radius: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">${orderData.payment_method.toUpperCase()}</span>
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
                                <div class="spinner-premium" style="width: 40px; height: 40px; border-width: 3px;"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="modal-summary-footer mt-5 p-4" style="background: white; border: 1px solid #f1f5f9; border-radius: 24px;">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted fw-bold" style="letter-spacing: 0.5px;">Tổng số lượng</span>
                        <span class="fw-bold text-dark" style="font-size: 1.1rem;"><?= $orderData['item_count'] ?> món</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted fw-bold" style="letter-spacing: 0.5px;">Nhân viên phục vụ</span>
                        <span class="fw-bold text-dark" style="font-size: 1.1rem;"><?= e($orderData['waiter_name'] ?: 'Hệ thống') ?></span>
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
                                    <td class="fw-bold text-dark" style="font-size: 1.1rem; color: var(--gold-primary);">x${item.quantity}</td>
                                    <td>
                                        <div class="fw-bold text-dark" style="font-size: 1rem;"><?= e($item['item_name']) ?></div>
                                        <div class="text-muted small" style="font-size: 0.85rem;"><?= formatPrice($item['price']) ?></div>
                                    </td>
                                    <td class="text-end fw-bold text-gold" style="font-size: 1.1rem; color: var(--gold-primary);"><?= formatPrice($item['subtotal']) ?></td>
                                </tr>
                            `;
                        });
                        document.querySelector('.modal-table-premium tbody').innerHTML = itemsHtml;
                    }
                })
                .catch(err => console.error('Lỗi tải chi tiết hóa đơn:', err));

            Aurora.openModal('modalOrderDetails');
        });
    });

    function setFilter(type) {
        document.getElementById('filter_type').value = type;
        const form = document.getElementById('historyFilterForm');
        form.submit();
    }

    // Close modal on outside click
    document.getElementById('modalOrderDetails').addEventListener('click', function(e) {
        if (e.target === this) {
            Aurora.closeModal('modalOrderDetails');
        }
    });
</script>
