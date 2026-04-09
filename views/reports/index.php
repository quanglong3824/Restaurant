<?php
// views/reports/index.php — Modern Business Analytics Dashboard
// Aurora Restaurant POS System
?>

<div class="reports-dashboard" id="reportsDashboard">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="title-section">
                <h1 class="dashboard-title">
                    <i class="fas fa-chart-line"></i>
                    <span>BÁO CÁO THỐNG KÊ</span>
                </h1>
                <p class="dashboard-subtitle">AURORA RESTAURANT - Phân tích hiệu suất kinh doanh</p>
            </div>
            
            <!-- Filter Form -->
            <form class="date-filter-form" method="GET" action="<?= BASE_URL ?>/admin/reports">
                <div class="filter-group">
                    <div class="filter-item">
                        <label>
                            <i class="fas fa-calendar-alt"></i>
                            Từ ngày
                        </label>
                        <input type="date" name="from" value="<?= e($from) ?>" class="date-input">
                    </div>
                    <div class="filter-divider"></div>
                    <div class="filter-item">
                        <label>
                            <i class="fas fa-calendar-check"></i>
                            Đến ngày
                        </label>
                        <input type="date" name="to" value="<?= e($to) ?>" class="date-input">
                    </div>
                </div>
                <button type="submit" class="btn-apply-filter">
                    <i class="fas fa-filter"></i>
                    <span>Áp dụng</span>
                </button>
            </form>
        </div>
    </div>

    <!-- KPI Cards Grid -->
    <div class="kpi-grid">
        <!-- Revenue Card -->
        <div class="kpi-card kpi-revenue">
            <div class="kpi-card-inner">
                <div class="kpi-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">TỔNG DOANH THU</span>
                    <div class="kpi-value"><?= formatPrice($stats['revenue'] ?? 0) ?></div>
                    <div class="kpi-subtitle">trong khoảng đã chọn</div>
                </div>
                <div class="kpi-trend <?= ($stats['revenue'] ?? 0) > 0 ? 'positive' : '' ?>">
                    <i class="fas fa-arrow-up"></i>
                    <span>100%</span>
                </div>
            </div>
            <div class="kpi-card-bg">
                <i class="fas fa-coins"></i>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="kpi-card kpi-orders">
            <div class="kpi-card-inner">
                <div class="kpi-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">TỔNG ĐƠN HÀNG</span>
                    <div class="kpi-value"><?= number_format($stats['total_orders'] ?? 0) ?></div>
                    <div class="kpi-subtitle">đơn đã hoàn thành</div>
                </div>
                <div class="kpi-trend">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Đơn</span>
                </div>
            </div>
            <div class="kpi-card-bg">
                <i class="fas fa-receipt"></i>
            </div>
        </div>

        <!-- Tables Served Card -->
        <div class="kpi-card kpi-tables">
            <div class="kpi-card-inner">
                <div class="kpi-icon">
                    <i class="fas fa-chair"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">BÀN ĐÃ PHỤC VỤ</span>
                    <div class="kpi-value"><?= number_format($stats['tables_served'] ?? 0) ?></div>
                    <div class="kpi-subtitle">lượt khách ghé thăm</div>
                </div>
                <div class="kpi-trend">
                    <i class="fas fa-users"></i>
                    <span>Lượt</span>
                </div>
            </div>
            <div class="kpi-card-bg">
                <i class="fas fa-chair"></i>
            </div>
        </div>

        <!-- Average Order Value Card -->
        <div class="kpi-card kpi-average">
            <div class="kpi-card-inner">
                <div class="kpi-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">GIÁ TRỊ ĐƠN TB</span>
                    <div class="kpi-value">
                        <?= ($stats['total_orders'] ?? 1) > 0 ? formatPrice(($stats['revenue'] ?? 0) / ($stats['total_orders'] ?? 1)) : '0₫' ?>
                    </div>
                    <div class="kpi-subtitle">trung bình mỗi đơn</div>
                </div>
                <div class="kpi-trend">
                    <i class="fas fa-chart-bar"></i>
                    <span>VNĐ</span>
                </div>
            </div>
            <div class="kpi-card-bg">
                <i class="fas fa-calculator"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="dashboard-grid">
        <!-- Top Selling Items -->
        <div class="dashboard-card card-top-items">
            <div class="card-header">
                <div class="header-left">
                    <div class="header-icon icon-fire">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="header-title">
                        <h3>Món Bán Chạy Nhất</h3>
                        <span>Top 10 sản phẩm được yêu thích</span>
                    </div>
                </div>
                <div class="header-badge">
                    <i class="fas fa-trophy"></i>
                    <span>Bán chạy</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($topItems)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Chưa có dữ liệu sản phẩm trong khoảng thời gian này</p>
                    </div>
                <?php else: ?>
                    <div class="top-items-list">
                        <?php 
                        $maxQty = max(array_column($topItems, 'total_qty')) ?: 1;
                        $rankIcons = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣', '6️⃣', '7️⃣', '8️⃣', '9️⃣', '🔟'];
                        foreach ($topItems as $i => $item): 
                            $percent = min(100, ($item['total_qty'] / $maxQty) * 100);
                            $rank = $rankIcons[$i] ?? ($i + 1);
                        ?>
                            <div class="top-item">
                                <div class="item-rank"><?= $rank ?></div>
                                <div class="item-details">
                                    <div class="item-name"><?= e($item['name']) ?></div>
                                    <div class="item-meta">
                                        <span class="item-qty"><?= $item['total_qty'] ?> suất</span>
                                        <?php if (!empty($item['revenue'])): ?>
                                            <span class="item-revenue"><?= formatPrice($item['revenue']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="item-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?= $percent ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Today's Orders -->
        <div class="dashboard-card card-orders">
            <div class="card-header">
                <div class="header-left">
                    <div class="header-icon icon-clock">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="header-title">
                        <h3>Giao Dịch Hôm Nay</h3>
                        <span><?= date('d/m/Y') ?></span>
                    </div>
                </div>
                <div class="header-actions">
                    <span class="count-badge"><?= count($todayOrders) ?> đơn</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($todayOrders)): ?>
                    <div class="empty-state">
                        <i class="fas fa-moon"></i>
                        <p>Hôm nay chưa có giao dịch nào hoàn tất</p>
                        <small>Dữ liệu sẽ cập nhật khi có đơn hàng</small>
                    </div>
                <?php else: ?>
                    <div class="orders-table-wrapper">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-chair"></i> Bàn</th>
                                    <th><i class="fas fa-user"></i> Nhân viên</th>
                                    <th><i class="fas fa-clock"></i> Giờ</th>
                                    <th class="text-right"><i class="fas fa-money-bill"></i> Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($todayOrders as $o): ?>
                                    <tr>
                                        <td class="cell-table">
                                            <span class="table-badge"><?= e($o['table_name']) ?></span>
                                        </td>
                                        <td class="cell-waiter">
                                            <div class="waiter-avatar">
                                                <?= strtoupper(substr($o['waiter_name'] ?: 'S', 0, 1)) ?>
                                            </div>
                                            <span><?= e($o['waiter_name'] ?: 'System') ?></span>
                                        </td>
                                        <td class="cell-time">
                                            <i class="far fa-clock"></i>
                                            <?= date('H:i', strtotime($o['opened_at'])) ?>
                                        </td>
                                        <td class="cell-amount text-right">
                                            <span class="amount-badge"><?= formatPrice($o['total'] ?? 0) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Daily Revenue Chart Section -->
    <?php if (!empty($daily)): ?>
    <div class="dashboard-card card-full-width">
        <div class="card-header">
            <div class="header-left">
                <div class="header-icon icon-chart">
                    <i class="fas fa-chart-area"></i>
                </div>
                <div class="header-title">
                    <h3>Biểu Đồ Doanh Thu</h3>
                    <span>Từ <?= date('d/m', strtotime($from)) ?> đến <?= date('d/m', strtotime($to)) ?></span>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn-chart-toggle" onclick="toggleChartView()">
                    <i class="fas fa-bars"></i>
                    <i class="fas fa-chart-bar"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Chart View -->
            <div class="chart-container" id="chartView">
                <div class="chart-bars">
                    <?php 
                    $maxRevenue = max(array_column($daily, 'revenue')) ?: 1;
                    foreach ($daily as $d): 
                        $height = ($d['revenue'] / $maxRevenue) * 100;
                    ?>
                        <div class="chart-bar-item">
                            <div class="bar-wrapper">
                                <div class="bar" style="height: <?= $height ?>%">
                                    <span class="bar-value"><?= number_format($d['revenue'] ?? 0) ?>₫</span>
                                </div>
                            </div>
                            <div class="bar-label">
                                <span class="day"><?= date('d', strtotime($d['day'])) ?></span>
                                <span class="month"><?= date('m', strtotime($d['day'])) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Table View -->
            <div class="table-view" id="tableView" style="display: none;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar"></i> Ngày</th>
                            <th><i class="fas fa-receipt"></i> Số đơn</th>
                            <th class="text-right"><i class="fas fa-coins"></i> Doanh thu</th>
                            <th class="text-right"><i class="fas fa-calculator"></i> Trung bình</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daily as $d): 
                            $avgPerOrder = $d['orders'] > 0 ? ($d['revenue'] ?? 0) / $d['orders'] : 0;
                        ?>
                            <tr>
                                <td class="cell-date">
                                    <span class="date-badge"><?= date('d/m/Y', strtotime($d['day'])) ?></span>
                                </td>
                                <td class="cell-orders">
                                    <span class="orders-count"><?= $d['orders'] ?> đơn</span>
                                </td>
                                <td class="cell-revenue text-right">
                                    <span class="revenue-amount"><?= formatPrice($d['revenue'] ?? 0) ?></span>
                                </td>
                                <td class="cell-average text-right">
                                    <span class="average-amount"><?= formatPrice($avgPerOrder) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- CSS Styles -->
<style>
/* ════════════════════════════════════════════════════════════
   CSS Variables & Reset
   ════════════════════════════════════════════════════════════ */
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --secondary: #f59e0b;
    --success: #10b981;
    --danger: #ef4444;
    --info: #3b82f6;
    --purple: #8b5cf6;
    
    --bg-page: #f1f5f9;
    --bg-card: #ffffff;
    --bg-hover: #f8fafc;
    
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;
    
    --border: #e2e8f0;
    --border-light: #f1f5f9;
    
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
    --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1);
    
    --radius: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
}

/* ════════════════════════════════════════════════════════════
   Dashboard Container
   ════════════════════════════════════════════════════════════ */
.reports-dashboard {
    padding: 24px;
    background: var(--bg-page);
    min-height: 100vh;
}

/* ════════════════════════════════════════════════════════════
   Header Section
   ════════════════════════════════════════════════════════════ */
.dashboard-header {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 24px;
    flex-wrap: wrap;
}

.title-section {
    flex: 1;
    min-width: 280px;
}

.dashboard-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 8px 0;
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text-primary);
}

.dashboard-title i {
    color: var(--primary);
    font-size: 1.8rem;
}

.dashboard-subtitle {
    margin: 0;
    font-size: 0.875rem;
    color: var(--text-secondary);
    font-weight: 500;
}

/* Filter Form */
.date-filter-form {
    display: flex;
    align-items: flex-end;
    gap: 16px;
    background: var(--bg-hover);
    padding: 16px;
    border-radius: var(--radius);
    border: 1px solid var(--border);
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.filter-item label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-item label i {
    font-size: 0.75rem;
    color: var(--primary);
}

.date-input {
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    background: var(--bg-card);
    cursor: pointer;
    transition: all 0.2s;
}

.date-input:hover,
.date-input:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.filter-divider {
    width: 1px;
    height: 40px;
    background: var(--border);
    margin: 0 8px;
}

.btn-apply-filter {
    display: flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: var(--radius);
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.btn-apply-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
}

/* ════════════════════════════════════════════════════════════
   KPI Cards Grid
   ════════════════════════════════════════════════════════════ */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.kpi-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    padding: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: all 0.3s;
    border: 1px solid var(--border);
}

.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.kpi-card-inner {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    position: relative;
    z-index: 1;
}

.kpi-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.kpi-revenue .kpi-icon {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #d97706;
}

.kpi-orders .kpi-icon {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #059669;
}

.kpi-tables .kpi-icon {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #2563eb;
}

.kpi-average .kpi-icon {
    background: linear-gradient(135deg, #e9d5ff, #d8b4fe);
    color: #7c3aed;
}

.kpi-content {
    flex: 1;
    min-width: 0;
}

.kpi-label {
    display: block;
    font-size: 0.65rem;
    font-weight: 800;
    color: var(--text-muted);
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.kpi-value {
    font-size: 1.5rem;
    font-weight: 900;
    color: var(--text-primary);
    line-height: 1.2;
    word-break: break-word;
}

.kpi-subtitle {
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-top: 2px;
}

.kpi-trend {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--text-muted);
}

.kpi-trend.positive {
    color: var(--success);
}

.kpi-card-bg {
    position: absolute;
    bottom: -20px;
    right: -20px;
    font-size: 8rem;
    color: rgba(0, 0, 0, 0.02);
    transform: rotate(-15deg);
    transition: all 0.3s;
}

.kpi-card:hover .kpi-card-bg {
    transform: rotate(-10deg) scale(1.1);
}

/* ════════════════════════════════════════════════════════════
   Dashboard Grid
   ════════════════════════════════════════════════════════════ */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.dashboard-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
}

.card-full-width {
    grid-column: 1 / -1;
}

/* Card Header */
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-light);
    background: var(--bg-hover);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.icon-fire {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #dc2626;
}

.icon-clock {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #2563eb;
}

.icon-chart {
    background: linear-gradient(135deg, #e9d5ff, #d8b4fe);
    color: #7c3aed;
}

.icon-category {
    background: linear-gradient(135deg, #ccfbf1, #99f6e4);
    color: #0d9488;
}

.header-title h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 800;
    color: var(--text-primary);
}

.header-title span {
    font-size: 0.75rem;
    color: var(--text-muted);
    display: block;
    margin-top: 2px;
}

.header-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
}

.count-badge {
    background: var(--primary);
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
}

.btn-chart-toggle {
    background: var(--bg-card);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    width: 36px;
    height: 36px;
    border-radius: var(--radius);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-chart-toggle:hover {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

/* Card Body */
.card-body {
    padding: 24px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 48px 24px;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 3rem;
    opacity: 0.3;
    display: block;
    margin-bottom: 16px;
}

.empty-state p {
    font-size: 0.95rem;
    margin: 0 0 4px 0;
}

.empty-state small {
    font-size: 0.8rem;
    color: var(--text-muted);
}

/* ════════════════════════════════════════════════════════════
   Top Items List
   ════════════════════════════════════════════════════════════ */
.top-items-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.top-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.item-rank {
    width: 36px;
    height: 36px;
    min-width: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 800;
    color: var(--text-secondary);
}

.top-item:nth-child(1) .item-rank {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #fff;
}

.top-item:nth-child(2) .item-rank {
    background: linear-gradient(135deg, #9ca3af, #6b7280);
    color: #fff;
}

.top-item:nth-child(3) .item-rank {
    background: linear-gradient(135deg, #b45309, #92400e);
    color: #fff;
}

.item-details {
    flex: 1;
    min-width: 0;
}

.item-name {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.item-meta {
    display: flex;
    gap: 12px;
    margin-top: 4px;
}

.item-qty {
    font-size: 0.75rem;
    color: var(--text-secondary);
    font-weight: 600;
}

.item-revenue {
    font-size: 0.75rem;
    color: var(--success);
    font-weight: 700;
}

.item-progress {
    width: 100px;
    min-width: 100px;
}

.progress-bar {
    height: 8px;
    background: var(--border-light);
    border-radius: 10px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), var(--purple));
    border-radius: 10px;
    transition: width 0.5s ease;
}

/* ════════════════════════════════════════════════════════════
   Orders Table
   ════════════════════════════════════════════════════════════ */
.orders-table-wrapper {
    overflow-x: auto;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
}

.orders-table th {
    text-align: left;
    padding: 12px 16px;
    font-size: 0.7rem;
    font-weight: 800;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--border-light);
}

.orders-table th i {
    margin-right: 6px;
    color: var(--primary);
}

.orders-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-light);
    font-size: 0.875rem;
}

.orders-table tbody tr:hover {
    background: var(--bg-hover);
}

.cell-table .table-badge {
    display: inline-block;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.8rem;
}

.cell-waiter {
    display: flex;
    align-items: center;
    gap: 10px;
}

.waiter-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--purple));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 800;
}

.cell-time {
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 6px;
}

.cell-time i {
    color: var(--text-muted);
}

.cell-amount .amount-badge {
    display: inline-block;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.85rem;
}

.text-right {
    text-align: right;
}

/* ════════════════════════════════════════════════════════════
   Chart Section
   ════════════════════════════════════════════════════════════ */
.chart-container {
    padding: 20px 0;
}

.chart-bars {
    display: flex;
    align-items: flex-end;
    justify-content: space-around;
    gap: 12px;
    height: 200px;
    padding: 0 20px;
}

.chart-bar-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.bar-wrapper {
    width: 100%;
    height: 160px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}

.bar {
    width: 100%;
    max-width: 40px;
    background: linear-gradient(180deg, var(--primary), var(--purple));
    border-radius: 8px 8px 0 0;
    position: relative;
    transition: all 0.3s;
    min-height: 4px;
}

.bar:hover {
    background: linear-gradient(180deg, var(--primary-dark), var(--primary));
    transform: scaleX(1.1);
}

.bar-value {
    position: absolute;
    top: -28px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 0.65rem;
    font-weight: 700;
    color: var(--text-secondary);
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.2s;
}

.bar:hover .bar-value {
    opacity: 1;
}

.bar-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.bar-label .day {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--text-primary);
}

.bar-label .month {
    font-size: 0.65rem;
    color: var(--text-muted);
}

/* Data Table */
.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    text-align: left;
    padding: 14px 16px;
    font-size: 0.7rem;
    font-weight: 800;
    color: var(--text-muted);
    text-transform: uppercase;
    border-bottom: 2px solid var(--border-light);
}

.data-table th i {
    margin-right: 6px;
    color: var(--primary);
}

.data-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-light);
}

.data-table tbody tr:hover {
    background: var(--bg-hover);
}

.date-badge {
    display: inline-block;
    background: var(--bg-hover);
    color: var(--text-primary);
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.85rem;
}

.orders-count {
    color: var(--text-secondary);
    font-weight: 600;
}

.revenue-amount {
    display: inline-block;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 700;
}

.average-amount {
    color: var(--text-secondary);
    font-weight: 600;
}

/* ════════════════════════════════════════════════════════════
   Category Section
   ════════════════════════════════════════════════════════════ */
.category-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.category-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.category-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.category-name {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text-primary);
}

.category-count {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.category-stats {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.category-revenue {
    font-size: 0.9rem;
    font-weight: 800;
    color: var(--primary);
    text-align: right;
}

.category-bar {
    height: 8px;
    background: var(--border-light);
    border-radius: 10px;
    overflow: hidden;
}

.category-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--secondary), var(--danger));
    border-radius: 10px;
    transition: width 0.5s ease;
}

/* ════════════════════════════════════════════════════════════
   Responsive Design
   ════════════════════════════════════════════════════════════ */
@media (max-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .reports-dashboard {
        padding: 16px;
    }
    
    .dashboard-header {
        padding: 16px;
    }
    
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .title-section {
        width: 100%;
    }
    
    .dashboard-title {
        font-size: 1.25rem;
    }
    
    .date-filter-form {
        width: 100%;
        flex-direction: column;
        padding: 12px;
    }
    
    .filter-group {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-divider {
        display: none;
    }
    
    .btn-apply-filter {
        width: 100%;
        justify-content: center;
    }
    
    .kpi-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .kpi-value {
        font-size: 1.1rem;
    }
    
    .kpi-icon {
        width: 44px;
        height: 44px;
        font-size: 1.2rem;
    }
    
    .dashboard-grid {
        gap: 12px;
    }
    
    .card-header {
        padding: 16px;
    }
    
    .card-body {
        padding: 16px;
    }
    
    .chart-bars {
        height: 150px;
        gap: 6px;
    }
    
    .bar-wrapper {
        height: 110px;
    }
    
    .bar {
        max-width: 28px;
    }
    
    .orders-table th,
    .orders-table td {
        padding: 10px 8px;
        font-size: 0.8rem;
    }
    
    .cell-waiter span {
        display: none;
    }
}

@media (max-width: 480px) {
    .kpi-grid {
        grid-template-columns: 1fr;
    }
    
    .kpi-card-inner {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .kpi-trend {
        position: static;
        margin-top: 8px;
    }
    
    .top-item {
        flex-wrap: wrap;
    }
    
    .item-progress {
        width: 100%;
        min-width: 100%;
    }
    
    .category-item {
        flex-direction: column;
        align-items: stretch;
    }
    
    .category-stats {
        align-items: stretch;
    }
    
    .category-revenue {
        text-align: left;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.kpi-card,
.dashboard-card {
    animation: fadeInUp 0.5s ease-out;
}

.kpi-card:nth-child(1) { animation-delay: 0.1s; }
.kpi-card:nth-child(2) { animation-delay: 0.2s; }
.kpi-card:nth-child(3) { animation-delay: 0.3s; }
.kpi-card:nth-child(4) { animation-delay: 0.4s; }
</style>

<!-- JavaScript -->
<script>
// Toggle Chart/Table View
function toggleChartView() {
    const chartView = document.getElementById('chartView');
    const tableView = document.getElementById('tableView');
    const btn = document.querySelector('.btn-chart-toggle');
    
    if (chartView.style.display === 'none') {
        chartView.style.display = 'block';
        tableView.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-bars"></i><i class="fas fa-chart-bar"></i>';
    } else {
        chartView.style.display = 'none';
        tableView.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-chart-bar"></i><i class="fas fa-bars"></i>';
    }
}

// Format currency for chart labels
function formatCurrencyShort(amount) {
    if (amount >= 1000000) {
        return (amount / 1000000).toFixed(1) + 'M';
    }
    if (amount >= 1000) {
        return (amount / 1000).toFixed(0) + 'K';
    }
    return amount.toString();
}

// Add hover effect to bars
document.querySelectorAll('.bar').forEach(bar => {
    bar.addEventListener('mouseenter', function() {
        const value = this.querySelector('.bar-value');
        if (value) {
            value.style.opacity = '1';
        }
    });
    
    bar.addEventListener('mouseleave', function() {
        const value = this.querySelector('.bar-value');
        if (value) {
            value.style.opacity = '0';
        }
    });
});

// Animate progress bars on load
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-fill, .category-fill');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 100);
    });
});
</script>