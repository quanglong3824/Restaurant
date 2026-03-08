<?php // views/orders/history.php — Order History for Waiters/Admins
// Use pagination data from controller
$page = $currentPage ?? 1;
$limit = 10; // Items per page
$totalPages = $totalPages ?? 1;
$totalItems = $totalCount ?? 0;
$totalRevenueVal = $totalRevenue ?? 0;
$pagedOrders = $orders;
?>

<div class="history-page-wrapper">
    <div class="page-content-container">

        <!-- Header Section -->
        <div class="page-header-minimal mb-4">
            <div class="d-flex justify-content-between align-items-end">
                <div>
                    <div class="header-breadcrumb mb-2">
                        <span class="text-muted">Quản lý</span>
                        <i class="fas fa-chevron-right mx-2 text-muted"></i>
                        <span class="text-gold fw-bold">Lịch sử</span>
                    </div>
                    <h1 class="playfair fw-900 mb-1" style="font-size: 2rem; letter-spacing: -0.5px;">Lịch sử giao dịch</h1>
                    <p class="text-muted-gold mb-0" style="font-size: 0.85rem; font-weight: 500;"><i class="fas fa-layer-group me-1" style="color: var(--gold)"></i> Hệ thống quản lý hóa đơn</p>
                </div>
                <div class="header-stats">
                    <div class="total-revenue-display">
                        <div class="revenue-amount" style="font-size: 1.5rem; font-weight: 800;"><?= formatPrice($totalRevenueVal) ?></div>
                        <div class="revenue-label" style="font-size: 0.7rem; font-weight: 600;">Tổng doanh thu</div>
                    </div>
                    <div class="results-counter">
                        <span class="count-badge" style="padding: 0.25rem 0.8rem; font-size: 1.1rem;"><?= number_format($totalItems) ?></span>
                        <span class="count-label" style="font-size: 0.65rem;">Hóa đơn</span>
                    </div>
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
                                    <div class="col-3">
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

        <!-- Summary Row -->
        <section class="summary-section mb-4">
            <div class="summary-row-premium">
                <div class="summary-main-card">
                    <div class="summary-bg-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="summary-content">
                        <span class="summary-tag">DOANH THU TỔNG KỲ</span>
                        <h2 class="revenue-display" style="font-size: 2.5rem;"><?= formatPrice($totalRevenueVal) ?></h2>
                    </div>
                </div>
                <div class="summary-stats-group">
                    <div class="stat-mini-card">
                        <div class="stat-icon"><i class="fas fa-receipt"></i></div>
                        <span class="val" style="font-size: 1.75rem;"><?= number_format($totalItems) ?></span>
                        <span class="lbl">Giao dịch</span>
                    </div>
                    <div class="stat-mini-card">
                        <div class="stat-icon"><i class="fas fa-file-invoice"></i></div>
                        <span class="val" style="font-size: 1.75rem;"><?= count($pagedOrders) ?></span>
                        <span class="lbl">Trang này</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Listing -->
        <section class="history-list-section">
            <div class="section-title-row mb-3">
                <h3 class="playfair fw-800" style="font-size: 1.3rem;"><i class="fas fa-stream text-gold me-2"></i> Dòng thời gian</h3>
                <div class="pagination-info" style="font-size: 0.8rem; font-weight: 500;">Hiển thị <?= (($page - 1) * $limit) + 1 ?> - <?= min($page * $limit, $totalItems) ?></div>
            </div>

            <?php if (empty($pagedOrders)): ?>
                <div class="empty-state-box">
                    <div class="empty-icon-circle mb-3">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h4 class="fw-bold" style="font-size: 1rem; margin-bottom: 0.5rem;">Không có dữ liệu</h4>
                    <p class="text-muted" style="font-size: 0.85rem;">Vui lòng điều chỉnh bộ lọc.</p>
                </div>
            <?php else: ?>
                <div class="history-premium-grid" style="grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <?php foreach ($pagedOrders as $order): ?>
                        <div class="premium-order-card" style="border-radius: 18px;">
                            <div class="card-top-accent"></div>
                            <div class="card-inner" style="padding: 1.25rem;">
                                <div class="order-header">
                                    <div class="table-avatar" style="width: 56px; height: 56px; font-size: 1.25rem;">
                                        <span><?= e(str_replace('Bàn ', '', $order['table_name'])) ?></span>
                                    </div>
                                    <div class="order-meta" style="flex: 1;">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h5 class="m-0 fw-800 text-dark" style="font-size: 1rem;"><?= e($order['table_name']) ?></h5>
                                            <span class="order-id" style="font-size: 0.7rem; padding: 0.2rem 0.6rem;"><?= sprintf('%06d', $order['id']) ?></span>
                                        </div>
                                        <div class="timestamp" style="font-size: 0.75rem; margin-top: 0.3rem;">
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
                                            <span class="label" style="font-size: 0.7rem; color: var(--text-muted);">Nhân viên</span>
                                            <span class="value" style="font-size: 0.85rem; font-weight: 600;"><?= e($order['waiter_name'] ?? 'Hệ thống') ?></span>
                                        </div>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-icon"><i class="fas fa-credit-card"></i></span>
                                        <div class="detail-value-group">
                                            <span class="label" style="font-size: 0.7rem; color: var(--text-muted);">Thanh toán</span>
                                            <span class="value-badge" style="font-size: 0.7rem; padding: 0.2rem 0.6rem; text-transform: uppercase;"><?= strtoupper(e($order['payment_method'])) ?></span>
                                        </div>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-icon"><i class="fas fa-utensils"></i></span>
                                        <div class="detail-value-group">
                                            <span class="label" style="font-size: 0.7rem; color: var(--text-muted);">Sản phẩm</span>
                                            <span class="value" style="font-size: 0.85rem; font-weight: 600;"><?= $order['item_count'] ?> món</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="order-pricing border-top pt-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="pricing-label" style="font-size: 0.65rem; letter-spacing: 1px;">TỔNG TIỀN</span>
                                        <span class="pricing-value" style="font-size: 1.3rem; color: var(--gold); font-weight: 900;"><?= formatPrice($order['total']) ?></span>
                                    </div>
                                </div>

                                <div class="order-actions gap-2">
                                    <button type="button" class="btn-action-view view-details-btn" style="font-size: 0.75rem; padding: 0.6rem;" data-order-id="<?= $order['id'] ?>" data-order-data="<?= htmlspecialchars(json_encode($order)) ?>">
                                        <i class="fas fa-eye me-1"></i> CHI TIẾT
                                    </button>
                                    <a href="<?= BASE_URL ?>/orders/print?order_id=<?= $order['id'] ?>" target="_blank" class="btn-action-print" style="font-size: 0.75rem; padding: 0.6rem;">
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
                    <nav class="pagination-luxury" style="gap: 0.5rem;">
                        <?php
                        $queryParams = $_GET;
                        unset($queryParams['page']);
                        $queryString = http_build_query($queryParams);
                        $baseUrl = BASE_URL . '/orders/history?' . ($queryString ? $queryString . '&' : '');
                        ?>

                        <a href="<?= $page <= 1 ? 'javascript:void(0)' : $baseUrl . 'page=' . ($page - 1) ?>" class="pag-btn <?= $page <= 1 ? 'disabled' : '' ?>" style="width: 44px; height: 44px;">
                            <i class="fas fa-chevron-left" style="font-size: 0.8rem;"></i>
                        </a>

                        <div class="pag-numbers" style="padding: 0.3rem; border-radius: 16px;">
                            <?php
                            $startPage = max(1, $page - 1);
                            $endPage = min($totalPages, $page + 1);

                            if ($startPage > 1) {
                                echo '<a href="'.$baseUrl.'page=1" class="pag-link" style="width: 36px; height: 36px; font-size: 0.8rem;">1</a>';
                                if ($startPage > 2) echo '<span class="pag-dots" style="font-size: 0.7rem;">...</span>';
                            }

                            for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <a href="<?= $baseUrl ?>page=<?= $i ?>" class="pag-link <?= $i === $page ? 'active' : '' ?>" style="width: 36px; height: 36px; font-size: 0.8rem;"><?= $i ?></a>
                            <?php endfor; ?>

                            <?php
                            if ($endPage < $totalPages) {
                                if ($endPage < $totalPages - 1) echo '<span class="pag-dots" style="font-size: 0.7rem;">...</span>';
                                echo '<a href="'.$baseUrl.'page='.$totalPages.'" class="pag-link" style="width: 36px; height: 36px; font-size: 0.8rem;">'.$totalPages.'</a>';
                            }
                            ?>
                        </div>

                        <a href="<?= $page >= $totalPages ? 'javascript:void(0)' : $baseUrl . 'page=' . ($page + 1) ?>" class="pag-btn <?= $page >= $totalPages ? 'disabled' : '' ?>" style="width: 44px; height: 44px;">
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
    <div class="modal modal-luxury-detail" style="border-radius: 28px;">
        <div class="modal-header-premium">
            <div class="header-icon" style="width: 56px; height: 56px;">
                <i class="fas fa-file-invoice-dollar" style="font-size: 1.5rem;"></i>
            </div>
            <div class="header-text">
                <h3 style="font-size: 1.3rem; margin: 0;">Chi tiết hóa đơn</h3>
                <p id="modalOrderSubtitle" style="font-size: 0.8rem; color: var(--text-muted); margin: 0.3rem 0 0;">Thông tin giao dịch</p>
            </div>
            <button class="close-btn-minimal" data-modal-close type="button" style="width: 36px; height: 36px;">
                <i class="fas fa-times" style="font-size: 0.9rem;"></i>
            </button>
        </div>
        <div class="modal-body-premium" id="modalOrderBody">
            <div class="text-center py-4">
                <div class="spinner-premium" style="width: 40px; height: 40px; border-width: 3px;"></div>
                <p class="text-muted" style="font-size: 0.8rem; margin-top: 0.5rem;">Đang chuẩn bị dữ liệu...</p>
            </div>
        </div>
        <div class="modal-footer-premium" style="padding: 1rem 1.5rem; gap: 0.5rem;">
            <button type="button" class="btn-modal-close" data-modal-close style="font-size: 0.8rem; padding: 0.6rem;">Đóng</button>
            <a href="" target="_blank" class="btn-modal-print" id="btnPrintOrder" style="font-size: 0.8rem; padding: 0.6rem;">
                <i class="fas fa-print me-1"></i> IN
            </a>
        </div>
    </div>
</div>

<style>
    :root {
        --history-bg: #f8fafc;
        --card-bg: #ffffff;
        --gold-primary: #d4af37;
        --gold-secondary: #b89b5e;
        --gold-light-alpha: rgba(212, 175, 55, 0.08);
        --text-dark: #0f172a;
        --text-slate: #64748b;
        --text-muted: #94a3b8;
        --success: #10b981;
        --radius-premium: 20px;
        --shadow-premium: 0 8px 24px -8px rgba(0, 0, 0, 0.06);
    }

    .history-page-wrapper {
        background-color: var(--history-bg);
        min-height: 100vh;
        padding: 2rem 1.5rem 7rem;
    }

    .page-content-container { max-width: 900px; margin: 0 auto; }

    .page-header-minimal { display: flex; justify-content: space-between; align-items: flex-end; }

    .page-header-minimal .playfair { font-size: 2rem; letter-spacing: -0.5px; }
    .text-muted-gold { color: var(--text-muted); font-weight: 500; font-size: 0.85rem; }
    .header-breadcrumb { font-size: 0.75rem; font-weight: 600; }

    .header-stats { display: flex; gap: 1.5rem; align-items: flex-end; }

    .total-revenue-display { text-align: right; }
    .revenue-amount { font-size: 1.5rem; font-weight: 800; color: var(--gold-primary); }
    .revenue-label { font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

    .results-counter { display: flex; flex-direction: column; align-items: flex-end; }
    .count-badge { background: var(--gold-primary); color: white; padding: 0.25rem 0.8rem; border-radius: 10px; font-weight: 800; font-size: 1.1rem; }
    .count-label { font-size: 0.65rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-top: 4px; }

    .filter-glass-card {
        background: var(--card-bg);
        border: 1px solid rgba(212, 175, 55, 0.12);
        border-radius: var(--radius-premium);
        padding: 1.5rem;
        box-shadow: var(--shadow-premium);
    }

    .filter-type-group { display: flex; gap: 0.3rem; }
    .type-btn {
        flex: 1; border: 2px solid #e2e8f0; background: white; padding: 0.7rem 0.5rem;
        border-radius: 12px; font-weight: 700; color: var(--text-slate);
        transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); cursor: pointer; font-size: 0.8rem;
    }
    .type-btn.active { background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-secondary) 100%); color: white; border-color: var(--gold-primary); box-shadow: 0 8px 15px rgba(212, 175, 55, 0.3); }

    .input-premium-wrapper { position: relative; }
    .input-premium-wrapper .icon { position: absolute; left: 1rem; color: var(--gold-secondary); font-size: 1rem; }
    .input-premium {
        width: 100%; height: 48px; background: #f8fafc; border: 1px solid #e2e8f0;
        border-radius: 12px; padding: 0 1rem; padding-left: 2.5rem;
        font-weight: 700; color: var(--text-dark); transition: all 0.25s;
    }
    .input-premium:focus { border-color: var(--gold-primary); background: white; box-shadow: 0 0 0 4px var(--gold-light-alpha); outline: none; }

    .btn-premium-gold {
        height: 48px; background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-secondary) 100%);
        color: white; border: none; border-radius: 12px; font-weight: 800; font-size: 0.75rem;
        box-shadow: 0 6px 15px rgba(212, 175, 55, 0.3); transition: all 0.25s;
    }
    .btn-premium-gold:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.4); }

    .summary-row-premium { display: grid; grid-template-columns: 1fr 300px; gap: 1.25rem; }
    @media (max-width: 768px) { .summary-row-premium { grid-template-columns: 1fr; } }

    .summary-main-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: var(--radius-premium); padding: 2rem; position: relative; color: white;
    }
    .summary-bg-icon { position: absolute; right: -5%; top: -8%; font-size: 6rem; color: rgba(255,255,255,0.04); transform: rotate(-15deg); }
    .summary-tag { color: var(--gold-primary); font-weight: 800; letter-spacing: 1.5px; font-size: 0.7rem; display: block; margin-bottom: 0.5rem; }
    .revenue-display { font-size: 2.5rem; font-weight: 900; margin: 0; font-family: 'Outfit'; letter-spacing: -1px; }

    .summary-stats-group { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .stat-mini-card {
        background: var(--card-bg); border-radius: 18px; padding: 1.25rem 1rem;
        display: flex; flex-direction: column; align-items: center; border: 1px solid rgba(212, 175, 55, 0.1);
    }
    .stat-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 14px; margin-bottom: 0.75rem; }
    .stat-icon i { font-size: 1.2rem; }
    .stat-icon.text-success { background: #ecfdf5; color: #059669; }
    .stat-icon.text-gold { background: var(--gold-light-alpha); color: var(--gold-primary); }
    .stat-mini-card .val { font-size: 1.75rem; font-weight: 900; color: var(--text-dark); font-family: 'Outfit'; }
    .stat-mini-card .lbl { font-size: 0.65rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; }

    .history-premium-grid { display: grid; gap: 1rem; }
    @media (max-width: 768px) { .history-premium-grid { grid-template-columns: 1fr; } }

    .premium-order-card {
        background: var(--card-bg); border-radius: 18px; position: relative; overflow: hidden;
        border: 1px solid #edf2f7; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }
    .premium-order-card:hover { transform: translateY(-6px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); border-color: var(--gold-primary); }
    .card-top-accent { height: 5px; background: linear-gradient(90deg, var(--gold-primary), var(--gold-secondary)); opacity: 0; transition: opacity 0.3s; }
    .premium-order-card:hover .card-top-accent { opacity: 1; }

    .card-inner { padding: 1.25rem; }

    .order-header { display: flex; gap: 1rem; align-items: center; }
    .table-avatar {
        width: 56px; height: 56px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 18px; display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; font-weight: 900; color: var(--gold-primary);
        border: 1.5px solid rgba(212, 175, 55, 0.2);
    }
    .premium-order-card:hover .table-avatar { transform: scale(1.05); border-color: var(--gold-primary); }
    .order-meta { flex: 1; }
    .order-id { font-size: 0.7rem; font-weight: 800; color: var(--text-muted); background: #f8fafc; padding: 0.2rem 0.6rem; border-radius: 8px; border: 1px solid #e2e8f0; }
    .timestamp { font-size: 0.75rem; color: var(--text-slate); margin-top: 0.3rem; display: flex; align-items: center; gap: 6px; }
    .separator { width: 3px; height: 3px; background: #cbd5e1; border-radius: 50%; }

    .order-details-summary { margin: 1.5rem 0; }
    .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid #f1f5f9; }
    .detail-row:last-child { border-bottom: none; }
    .detail-icon { font-size: 1rem; margin-right: 0.75rem; color: var(--text-muted); transition: color 0.3s; }
    .premium-order-card:hover .detail-icon { color: var(--gold-primary); }
    .detail-value-group { flex: 1; display: flex; flex-direction: column; }
    .detail-row .label { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; }
    .detail-row .value { font-size: 0.85rem; font-weight: 600; color: var(--text-dark); }
    .value-badge { background: #f8fafc; padding: 0.2rem 0.6rem; border-radius: 8px; font-weight: 700; font-size: 0.7rem; color: var(--text-dark); border: 1px solid #e2e8f0; }

    .order-pricing { margin-top: 1.5rem; border-top: 1px solid #f1f5f9; padding-top: 1rem; }
    .pricing-label { font-size: 0.65rem; font-weight: 800; color: var(--text-muted); letter-spacing: 1px; text-transform: uppercase; }
    .pricing-value { font-size: 1.3rem; font-weight: 900; color: var(--gold-primary); font-family: 'Outfit'; }

    .order-actions { display: flex; gap: 0.5rem; margin-top: 1.5rem; }
    .btn-action-view, .btn-action-print {
        flex: 1; border: 1px solid #e2e8f0; border-radius: 12px; font-weight: 700; font-size: 0.75rem;
        display: flex; align-items: center; justify-content: center; transition: all 0.2s;
        background: white; text-decoration: none !important; padding: 0.6rem;
    }
    .btn-action-view { background: #f8fafc; }
    .btn-action-view:hover { background: white; border-color: var(--text-dark); transform: translateY(-2px); }
    .btn-action-print { color: var(--gold-primary); border-color: rgba(212, 175, 55, 0.2); }
    .btn-action-print:hover { background: var(--gold-light-alpha); border-color: var(--gold-primary); transform: translateY(-2px); }

    .pagination-luxury { display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
    .pag-btn {
        width: 44px; height: 44px; background: white; border: 1px solid #e2e8f0; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; color: var(--text-dark); transition: all 0.2s;
    }
    .pag-btn:hover:not(.disabled) { background: var(--text-dark); color: white; border-color: var(--text-dark); transform: translateY(-2px); }
    .pag-btn.disabled { opacity: 0.3; cursor: not-allowed; }
    .pag-numbers { display: flex; align-items: center; background: white; padding: 0.3rem; border-radius: 14px; border: 1px solid #e2e8f0; }
    .pag-link {
        width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
        border-radius: 10px; font-weight: 700; color: var(--text-slate); transition: all 0.2s;
        text-decoration: none !important; font-size: 0.8rem;
    }
    .pag-link.active { background: var(--gold-primary); color: white; box-shadow: 0 6px 12px rgba(212, 175, 55, 0.3); }
    .pag-link:hover:not(.active) { color: var(--gold-primary); background: var(--gold-light-alpha); }
    .pag-dots { padding: 0 0.4rem; color: #cbd5e1; font-size: 0.7rem; }

    .modal-luxury-detail { background: white; border-radius: 28px; box-shadow: 0 30px 80px rgba(0,0,0,0.2); max-width: 500px; width: 95%; margin: 2rem auto; padding: 1rem; }
    .modal-header-premium { display: flex; align-items: center; padding: 0 1rem 1rem; }
    .header-icon { width: 56px; height: 56px; background: var(--gold-light-alpha); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-right: 1rem; }
    .header-text h3 { margin: 0; font-weight: 900; font-size: 1.3rem; color: var(--text-dark); }
    .close-btn-minimal { position: absolute; top: 1rem; right: 1rem; border: none; background: #f1f5f9; width: 36px; height: 36px; border-radius: 50%; color: var(--text-slate); transition: all 0.2s; display: flex; align-items: center; justify-content: center; }
    .close-btn-minimal:hover { background: #e2e8f0; color: var(--text-dark); transform: rotate(90deg); }
    .modal-body-premium { padding: 1.5rem 1rem; }
    .modal-footer-premium { display: flex; gap: 0.5rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; }
    .btn-modal-close { flex: 1; border: 1px solid #e2e8f0; border-radius: 12px; background: white; padding: 0.6rem; font-weight: 700; transition: all 0.2s; }
    .btn-modal-close:hover { background: #f8fafc; border-color: var(--text-dark); }
    .btn-modal-print { flex: 1; background: var(--text-dark); color: white; border: none; border-radius: 12px; padding: 0.6rem; font-weight: 700; text-decoration: none !important; transition: all 0.2s; }
    .btn-modal-print:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }

    .spinner-premium { width: 40px; height: 40px; border: 3px solid #f1f5f9; border-top: 3px solid var(--gold-primary); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    .empty-state-box { background: var(--card-bg); border-radius: 24px; text-align: center; border: 2px dashed #e2e8f0; padding: 2.5rem 1.5rem; }
    .empty-icon-circle { width: 80px; height: 80px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2.5rem; color: #cbd5e1; }
    .empty-state-box h4 { font-weight: 700; color: var(--text-dark); margin-bottom: 0.5rem; font-size: 1rem; }
    .empty-state-box .text-muted { color: var(--text-slate); font-size: 0.85rem; }

    .modal-table-premium { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
    .modal-table-premium th { text-align: left; padding: 0.75rem 0.5rem; color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; font-weight: 800; }
    .modal-table-premium td { padding: 0.75rem 0.5rem; border-bottom: 1px solid #f1f5f9; }
    .modal-table-premium td:last-child { text-align: right; }
    .modal-table-premium tbody tr:last-child td { border-bottom: none; }

    .section-title-row { display: flex; justify-content: space-between; align-items: flex-end; }
    .section-title-row h3 { font-size: 1.3rem; letter-spacing: -0.5px; }
    .pagination-info { color: var(--text-muted); font-size: 0.8rem; font-weight: 500; }

    @media (max-width: 600px) {
        .history-page-wrapper { padding: 1.5rem 1rem 6.5rem; }
        .revenue-amount { font-size: 1.3rem; }
        .revenue-display { font-size: 2rem; }
        .summary-main-card { padding: 1.5rem; }
        .summary-stats-group { grid-template-columns: 1fr; }
        .header-stats { flex-direction: column; gap: 1rem; }
        .history-premium-grid { grid-template-columns: 1fr; }
        .modal { width: 96%; margin: 2rem auto; }
    }
</style>

<script>
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const orderData = JSON.parse(this.getAttribute('data-order-data'));

            const modalBody = document.getElementById('modalOrderBody');
            const printBtn = document.getElementById('btnPrintOrder');
            const subtitle = document.getElementById('modalOrderSubtitle');

            printBtn.href = '<?= BASE_URL ?>/orders/print?order_id=' + orderId;
            subtitle.innerHTML = `<i class="fas fa-tag me-1" style="color: var(--gold)"></i> Bàn: <strong>${orderData.table_name}</strong> | <i class="far fa-clock ms-2" style="color: var(--text-muted)"></i> ${new Date(orderData.closed_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}`;

            let html = `
                <div class="modal-pricing-header" style="background: #f8fafc; border-radius: 16px; padding: 1rem; margin-bottom: 1.5rem; border: 1px solid rgba(212, 175, 55, 0.1);">
                    <div>
                        <span class="d-block text-muted small fw-800" style="font-size: 0.65rem; letter-spacing: 1px;">TỔNG THANH TOÁN</span>
                        <h2 class="m-0 fw-900 text-gold" style="font-size: 1.8rem; font-family: 'Outfit'; color: var(--gold-primary);">${formatPrice(orderData.total || 0)}</h2>
                    </div>
                    <div class="text-end">
                        <span class="badge" style="background: white; color: var(--text-dark); border: 1px solid #e2e8f0; padding: 0.3rem 0.8rem; border-radius: 10px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">${orderData.payment_method.toUpperCase()}</span>
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
                        <tr><td colspan="3" class="text-center py-4"><div class="spinner-premium" style="width: 30px; height: 30px; border-width: 2px;"></div></td></tr>
                    </tbody>
                </table>

                <div class="modal-summary-footer mt-3" style="background: white; border: 1px solid #f1f5f9; border-radius: 16px; padding: 1rem;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fw-bold" style="font-size: 0.75rem;">Tổng số lượng</span>
                        <span class="fw-bold text-dark" style="font-size: 0.9rem;"><?= $orderData['item_count'] ?> món</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted fw-bold" style="font-size: 0.75rem;">Nhân viên</span>
                        <span class="fw-bold text-dark" style="font-size: 0.9rem;"><?= e($orderData['waiter_name'] ?: 'Hệ thống') ?></span>
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
                            itemsHtml +=`
                                <tr>
                                    <td class="fw-bold text-dark" style="font-size: 1rem; color: var(--gold-primary);">x${item.quantity}</td>
                                    <td>
                                        <div class="fw-bold text-dark" style="font-size: 0.9rem;"><?= e($item['item_name']) ?></div>
                                        <div class="text-muted small" style="font-size: 0.8rem;"><?= formatPrice($item['price']) ?></div>
                                    </td>
                                    <td class="text-end fw-bold text-gold" style="font-size: 1rem; color: var(--gold-primary);"><?= formatPrice($item['subtotal']) ?></td>
                                </tr>
                            `;
                        });
                        document.querySelector('.modal-table-premium tbody').innerHTML = itemsHtml;
                    }
                })
                .catch(err => console.error('Error:', err));

            Aurora.openModal('modalOrderDetails');
        });
    });

    function setFilter(type) {
        document.getElementById('filter_type').value = type;
        document.getElementById('historyFilterForm').submit();
    }

    document.getElementById('modalOrderDetails').addEventListener('click', function(e) {
        if (e.target === this) Aurora.closeModal('modalOrderDetails');
    });
</script>
