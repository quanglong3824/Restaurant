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
                                            <div class="progress-bar-fill" style="width: <?= $percent ?>%"></div>
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

<style>
    :root {
        --rp-bg: #f1f5f9;
        --rp-card: #ffffff;
        --rp-border: #e2e8f0;
        --rp-text: #1e293b;
        --rp-accent: #3b82f6;
    }

    .reports-container { color: var(--rp-text); }

    /* ── Filter Bar ────────────────────────────────────────── */
    .filter-glass-bar {
        background: #fff; border: 1px solid var(--rp-border);
        border-radius: 12px; padding: 8px; display: flex; align-items: center;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .filter-inputs { display: flex; flex: 1; align-items: center; }
    .input-group-custom { flex: 1; padding: 0 15px; }
    .input-group-custom label { display: block; font-size: 0.6rem; font-weight: 800; color: #94a3b8; margin-bottom: 2px; }
    .input-group-custom input { border: none; font-size: 0.85rem; font-weight: 700; color: var(--rp-text); width: 100%; outline: none; }
    .input-divider { width: 1px; height: 30px; background: var(--rp-border); }
    .btn-filter {
        background: var(--rp-text); color: #fff; border: none; padding: 10px 20px;
        border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;
        transition: all 0.2s;
    }
    .btn-filter:hover { background: #000; }

    /* ── KPI Cards ────────────────────────────────────────── */
    .kpi-card {
        background: #fff; border-radius: 16px; padding: 25px;
        border: 1px solid var(--rp-border); display: flex; align-items: center;
        gap: 20px; position: relative; overflow: hidden;
    }
    .kpi-icon {
        width: 50px; height: 50px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
    }
    .kpi-card.revenue .kpi-icon { background: #eff6ff; color: #3b82f6; }
    .kpi-card.orders .kpi-icon { background: #f0fdf4; color: #10b981; }
    .kpi-card.tables .kpi-icon { background: #faf5ff; color: #a855f7; }
    
    .kpi-label { font-size: 0.65rem; font-weight: 800; color: #64748b; letter-spacing: 0.5px; }
    .kpi-value { font-size: 1.5rem; font-weight: 900; margin: 0; color: var(--rp-text); }
    .kpi-trend { position: absolute; top: 15px; right: 20px; font-size: 0.7rem; font-weight: 700; }
    .kpi-trend.positive { color: #10b981; }

    /* ── Report Cards ────────────────────────────────────────── */
    .report-card {
        background: #fff; border-radius: 16px; border: 1px solid var(--rp-border);
        height: 100%; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .card-header-clean { padding: 20px 25px; border-bottom: 1px solid var(--rp-border); }
    .card-header-clean h3 { font-size: 1rem; font-weight: 800; margin: 0; color: var(--rp-text); }
    .card-body-clean { padding: 20px 25px; }

    /* ── Top Items Progress ──────────────────────────────────── */
    .top-item-row-new { display: flex; align-items: center; gap: 15px; margin-bottom: 18px; }
    .item-rank { 
        width: 28px; height: 28px; background: #f1f5f9; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 800; color: #64748b;
    }
    .item-info-flex { flex: 1; }
    .item-name-bold { font-size: 0.85rem; font-weight: 700; color: var(--rp-text); }
    .item-qty-count { font-size: 0.8rem; font-weight: 800; color: #3b82f6; }
    .progress-bar-bg { height: 6px; background: #f1f5f9; border-radius: 10px; margin-top: 6px; overflow: hidden; }
    .progress-bar-fill { height: 100%; background: linear-gradient(90deg, #3b82f6, #60a5fa); border-radius: 10px; }

    /* ── Modern Table ────────────────────────────────────────── */
    .table-modern { width: 100%; border-collapse: collapse; }
    .table-modern th { 
        text-align: left; padding: 12px 0; font-size: 0.65rem; color: #94a3b8; 
        text-transform: uppercase; border-bottom: 2px solid #f1f5f9;
    }
    .table-modern td { padding: 15px 0; border-bottom: 1px solid #f8fafc; font-size: 0.9rem; }
    
    .badge-date { background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; }
    .empty-state { padding: 40px 0; text-align: center; color: #94a3b8; font-style: italic; font-size: 0.9rem; }

    @media (max-width: 768px) {
        .filter-glass-bar { flex-direction: column; gap: 10px; }
        .input-divider { display: none; }
        .btn-filter { width: 100%; }
    }
</style>
