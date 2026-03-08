<?php // views/orders/list.php — Danh sách tất cả các bàn đang order (đang bận) ?>
<div class="page-content">

    <div class="order-list-header">
        <div>
            <h1 style="font-size:1.5rem; font-weight:800; color:var(--text); margin-bottom:0.25rem;">Quản lý Bàn Đang
                Bận</h1>
            <p style="color:var(--text-muted); font-size:0.9rem;">Tổng số
                <?= count($orders) ?> bàn đang được phục vụ
            </p>
        </div>

        <!-- Filter Tabs -->
        <div class="order-filters" id="areaFilterContainer">
            <button class="filter-btn is-active" data-area="all">Tất cả khu vực</button>
            <?php
            foreach ($areas as $area):
                ?>
                <button class="filter-btn" data-area="<?= e($area) ?>">Khu
                    <?= e($area) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="order-list-body" id="orderListBody">
        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <i class="fas fa-laugh-beam" style="font-size: 4rem; color: var(--border-gold); margin-bottom: 1rem;"></i>
                <h3 style="font-size: 1.25rem; color: var(--text);">Thảnh thơi tay chân!</h3>
                <p style="color: var(--text-muted);">Hiện không có bàn nào đang phục vụ.</p>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $o): ?>
                <?php
                // Calculate wait time
                $openedTime = strtotime($o['opened_at']);
                $diffMins = floor((time() - $openedTime) / 60);
                $timeDisplay = $diffMins > 60
                    ? floor($diffMins / 60) . 'h ' . ($diffMins % 60) . 'm'
                    : $diffMins . ' phút';
                ?>
                <div class="order-row-card" data-area="<?= e($o['table_area']) ?>"
                    onclick="window.location.href='<?= BASE_URL ?>/orders?table_id=<?= $o['table_id'] ?>&order_id=<?= $o['id'] ?>'">
                    <div class="order-row-main">
                        <div class="order-table-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="order-table-info">
                            <h3>Bàn
                                <?= e($o['table_name']) ?> <span
                                    class="badge-order-id"># <?= $o['id'] ?></span>
                            </h3>
                            <div class="order-table-meta">
                                <span><i class="fas fa-user-friends"></i>
                                    <?= $o['guest_count'] ?> khách
                                </span>
                                <span><i class="fas fa-map-marker-alt"></i> Khu
                                    <?= e($o['table_area']) ?>
                                </span>
                                <span><i class="fas fa-user-tie"></i> Phục vụ:
                                    <?= e($o['waiter_name']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="order-row-stats">
                        <div class="stat-box">
                            <div class="label">Thời gian ngồi</div>
                            <div class="value time-display">
                                <i class="far fa-clock"></i>
                                <?= $timeDisplay ?>
                            </div>
                        </div>
                        <div class="stat-box stat-left-border">
                            <div class="label">Đã gọi</div>
                            <div class="value">
                                <?= $o['item_count'] ?> món
                            </div>
                        </div>
                        <div class="stat-box stat-left-border">
                            <div class="label">Tổng tiền</div>
                            <div class="value price">
                                <?= formatPrice($o['total']) ?>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right"
                                class="chevron-icon"></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Empty state for filters -->
        <div id="emptyFilterState" class="empty-state" style="display: none;">
            <i class="fas fa-search" style="font-size: 3rem; color: var(--border-gold); margin-bottom: 1rem;"></i>
            <h3 style="font-size: 1.2rem; color: var(--text);">Khu vực này hiện không có bàn trống/bận.</h3>
        </div>
    </div>

</div>

<!-- External CSS for Orders List View -->
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/list.css">

<!-- External JavaScript for Orders List View -->
<script src="<?= BASE_URL ?>/public/js/orders/list.js"></script>
