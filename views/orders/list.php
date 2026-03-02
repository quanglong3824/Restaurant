<?php // views/orders/list.php — Danh sách tất cả các bàn đang order (đang bận) ?>
<style>
    .order-list-header {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (min-width: 768px) {
        .order-list-header {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .order-filters {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .order-filters::-webkit-scrollbar {
        display: none;
    }

    .filter-btn {
        padding: 0.6rem 1.25rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .filter-btn.is-active {
        background: var(--gold);
        color: #fff;
        border-color: var(--gold);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .order-row-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        border: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 1rem;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .order-row-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        border-color: var(--border-gold);
    }

    @media (min-width: 768px) {
        .order-row-card {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .order-row-main {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .order-table-icon {
        width: 60px;
        height: 60px;
        background: var(--gold-light);
        color: var(--gold-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .order-table-info h3 {
        font-size: 1.25rem;
        margin-bottom: 0.25rem;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .order-table-meta {
        font-size: 0.85rem;
        color: var(--text-muted);
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .order-row-stats {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .stat-box {
        text-align: right;
    }

    .stat-box .label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: var(--text-dim);
        margin-bottom: 0.2rem;
    }

    .stat-box .value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text);
    }

    .stat-box .value.price {
        color: var(--gold-dark);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
    }
</style>

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
                                    style="font-size:0.75rem; padding: 2px 8px; background: var(--bg); border:1px solid var(--border); border-radius:50px; color:var(--text-muted); font-weight:normal;">#
                                    <?= $o['id'] ?>
                                </span>
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
                            <div class="value" style="color: <?= $diffMins > 90 ? 'var(--danger)' : 'var(--text)' ?>">
                                <i class="far fa-clock"></i>
                                <?= $timeDisplay ?>
                            </div>
                        </div>
                        <div class="stat-box" style="border-left: 1px solid var(--border); padding-left: 1.5rem;">
                            <div class="label">Đã gọi</div>
                            <div class="value">
                                <?= $o['item_count'] ?> món
                            </div>
                        </div>
                        <div class="stat-box" style="border-left: 1px solid var(--border); padding-left: 1.5rem;">
                            <div class="label">Tổng tiền</div>
                            <div class="value price">
                                <?= formatPrice($o['total']) ?>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right"
                                style="color:var(--border-gold); font-size: 1.5rem; margin-left: 0.5rem;"></i>
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

<script>
    // Xử lý bộ lọc khu vực
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');

            const filterArea = btn.getAttribute('data-area');
            const rows = document.querySelectorAll('.order-row-card');
            let visibleCount = 0;

            rows.forEach(row => {
                if (filterArea === 'all' || row.getAttribute('data-area') === filterArea) {
                    row.style.display = 'flex';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show empty state if no rows visible
            const emptyState = document.getElementById('emptyFilterState');
            if (emptyState) {
                emptyState.style.display = (visibleCount === 0 && rows.length > 0) ? 'block' : 'none';
            }
        });
    });
</script>