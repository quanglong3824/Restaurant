<?php
// views/reports/index.php — Modern Business Analytics Dashboard
?>

<div class="reports-container">
    <!-- Header & Filter -->
    <div class="reports-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="playfair text-dark mb-1">BÁO CÁO KINH DOANH</h1>
                <p class="text-muted small mb-0">Theo dõi doanh thu và hiệu suất bán hàng</p>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <form method="GET" action="<?= BASE_URL ?>/admin/reports" class="filter-glass-bar">
                    <div class="filter-inputs">
                        <div class="input-group-custom">
                            <label>TỪ NGÀY</label>
                            <input type="date" name="from" value="<?= e($from) ?>">
                        </div>
                        <div class="input-divider"></div>
                        <div class="input-group-custom">
                            <label>ĐẾN NGÀY</label>
                            <input type="date" name="to" value="<?= e($to) ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-sync-alt"></i> LỌC
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- KPI Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="kpi-card revenue">
                <div class="kpi-icon"><i class="fas fa-wallet"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">TỔNG DOANH THU</span>
                    <h2 class="kpi-value"><?= formatPrice($stats['revenue'] ?? 0) ?></h2>
                </div>
                <div class="kpi-trend positive">
                    <i class="fas fa-chart-line"></i> +12%
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card orders">
                <div class="kpi-icon"><i class="fas fa-receipt"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">TỔNG ĐƠN HÀNG</span>
                    <h2 class="kpi-value"><?= number_format($stats['total_orders'] ?? 0) ?></h2>
                </div>
                <div class="kpi-trend">
                    <i class="fas fa-shopping-bag"></i> Đơn
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card tables">
                <div class="kpi-icon"><i class="fas fa-chair"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">BÀN ĐÃ PHỤC VỤ</span>
                    <h2 class="kpi-value"><?= number_format($stats['tables_served'] ?? 0) ?></h2>
                </div>
                <div class="kpi-trend">
                    <i class="fas fa-users"></i> Lượt
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Top Products -->
        <div class="col-lg-5">
            <div class="report-card">
                <div class="card-header-clean">
                    <h3><i class="fas fa-fire text-orange me-2"></i> Món bán chạy nhất</h3>
                    <span class="small text-muted">Top 10 món</span>
                </div>
                <div class="card-body-clean">
                    <?php if (empty($topItems)): ?>
                        <div class="empty-state">Chưa có dữ liệu sản phẩm.</div>
                    <?php else: ?>
                        <div class="top-items-list">
                            <?php 
                            $maxQty = reset($topItems)['total_qty'] ?: 1;
                            foreach ($topItems as $i => $item): 
                                $percent = ($item['total_qty'] / $maxQty) * 100;
                            ?>
                                <div class="top-item-row-new">
                                    <div class="item-rank"><?= $i + 1 ?></div>
                                    <div class="item-info-flex">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="item-name-bold"><?= e($item['name']) ?></span>
                                            <span class="item-qty-count"><?= $item['total_qty'] ?> suất</span>
                                        </div>
                                        <div class="progress-bar-bg">
                                            <div class="progress-bar-fill" data-width="<?= $percent ?>"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-lg-7">
            <div class="report-card">
                <div class="card-header-clean d-flex justify-content-between">
                    <h3><i class="fas fa-history text-primary me-2"></i> Giao dịch hôm nay</h3>
                    <span class="badge-date"><?= date('d/m/Y') ?></span>
                </div>
                <div class="card-body-clean">
                    <?php if (empty($todayOrders)): ?>
                        <div class="empty-state">Hôm nay chưa có giao dịch nào hoàn tất.</div>
                    <?php else: ?>
                        <div class="table-responsive-custom">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>BÀN</th>
                                        <th>NHÂN VIÊN</th>
                                        <th>THỜI GIAN</th>
                                        <th class="text-end">THÀNH TIỀN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($todayOrders as $o): ?>
                                        <tr>
                                            <td class="fw-bold"><?= e($o['table_name']) ?></td>
                                            <td><span class="text-muted small"><?= e($o['waiter_name'] ?: 'System') ?></span></td>
                                            <td><?= date('H:i', strtotime($o['opened_at'])) ?></td>
                                            <td class="text-end text-success fw-bold"><?= formatPrice($o['total'] ?? 0) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Revenue -->
    <?php if (!empty($daily)): ?>
    <div class="report-card mt-4">
        <div class="card-header-clean">
            <h3><i class="fas fa-chart-area text-purple me-2"></i> Doanh thu theo ngày</h3>
        </div>
        <div class="card-body-clean">
            <div class="table-responsive-custom">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>NGÀY</th>
                            <th>SỐ ĐƠN</th>
                            <th class="text-end">DOANH THU NGÀY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daily as $d): ?>
                            <tr>
                                <td class="fw-bold"><?= date('d/m/Y', strtotime($d['day'])) ?></td>
                                <td><?= $d['orders'] ?> đơn</td>
                                <td class="text-end fw-bold text-dark"><?= formatPrice($d['revenue'] ?? 0) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/reports/index.css">
<script src="<?= BASE_URL ?>/public/js/reports/index.js"></script>
