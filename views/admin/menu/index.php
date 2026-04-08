<?php // views/admin/menu/index.php ?>
<?php
// Use filter counts from controller (computed from ALL items)
$totalAll = $pagination['total'] ?? count($items);
$countRestaurant = $filterCounts['restaurant'] ?? 0;
$countRoom = $filterCounts['room_service'] ?? 0;
$countBoth = $filterCounts['both'] ?? 0;
?>
<link rel="stylesheet" href="<?= asset('public/css/admin/menu.css') ?>">

<div class="card">
    <div class="card-header card-header--column">
        <div class="card-header__actions">
            <h2><i class="fas fa-utensils"></i> Danh sách Món ăn
                <span id="countBadge" class="count-badge"><?= $countAll ?> món</span>
            </h2>
            <div class="card-header__buttons">
                <a href="<?= BASE_URL ?>/admin/menu" class="btn btn-outline <?= !isset($_GET['type']) || $_GET['type'] === '' ? 'active' : '' ?>">
                    <i class="fas fa-utensils"></i> Món Lẻ
                </a>
                <a href="<?= BASE_URL ?>/admin/menu/sets" class="btn btn-outline <?= isset($_GET['type']) && $_GET['type'] === 'sets' ? 'active' : '' ?>">
                    <i class="fas fa-layer-group"></i> Set & Combo
                </a>
                <a href="<?= BASE_URL ?>/admin/menu/create" class="btn btn-gold">
                    <i class="fas fa-plus"></i> Thêm món
                </a>
                <?php if (Auth::check() && Auth::user()['role'] === ROLE_IT): ?>
                <a href="<?= BASE_URL ?>/admin/menu/clear" class="btn btn-outline btn-outline--danger" title="Xóa dữ liệu thực đơn (IT only)">
                    <i class="fas fa-trash-alt"></i> Xóa dữ liệu
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── STAT CHIPS ─────────────────────────────────────── -->
        <div class="stat-chips-wrapper">
            <button class="stat-chip active" data-service="" onclick="quickFilter(this,'')">
                <i class="fas fa-border-all"></i> Tất cả <span class="chip-count"><?= $countAll ?></span>
            </button>
            <button class="stat-chip" data-service="restaurant" onclick="quickFilter(this,'restaurant')">
                <i class="fas fa-utensils"></i> Nhà hàng <span class="chip-count"><?= $countRestaurant ?></span>
            </button>
            <button class="stat-chip" data-service="room_service" onclick="quickFilter(this,'room_service')">
                <i class="fas fa-bed"></i> Room Service <span class="chip-count"><?= $countRoom ?></span>
            </button>
            <button class="stat-chip" data-service="both" onclick="quickFilter(this,'both')">
                <i class="fas fa-arrows-left-right"></i> Cả hai <span class="chip-count"><?= $countBoth ?></span>
            </button>
        </div>

        <!-- ── FILTER BAR ──────────────────────────────────────── -->
        <div class="filter-bar">
            <!-- Search -->
            <div class="filter-input-wrap">
                <i class="fas fa-search filter-icon"></i>
                <input type="text" id="searchInput" class="filter-input" placeholder="Tìm tên món...">
                <button id="clearSearch" class="filter-clear u-hidden" onclick="clearSearchInput()" title="Xóa">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Category -->
            <div class="filter-select-wrap">
                <i class="fas fa-folder filter-icon"></i>
                <select id="catFilter" class="filter-select">
                    <option value="">Tất cả danh mục</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status -->
            <div class="filter-select-wrap">
                <i class="fas fa-eye filter-icon"></i>
                <select id="statusFilter" class="filter-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active">Đang hiển thị</option>
                    <option value="inactive">Đang ẩn</option>
                    <option value="available">Còn hàng</option>
                    <option value="unavailable">Hết hàng</option>
                </select>
            </div>

            <!-- Reset -->
            <button id="resetFilters" class="btn btn-outline btn-sm" onclick="resetAllFilters()" title="Xóa bộ lọc">
                <i class="fas fa-rotate-left"></i> Đặt lại
            </button>
        </div>
    </div>

    <div class="table-wrap">
        <table id="menuTable">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tên món</th>
                    <th>Danh mục</th>
                    <th>Phục vụ</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Hiển thị</th>
                    <th>Còn hàng</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item):
                    $st = $item['service_type'] ?? 'both';
                    $stMap = [
                        'restaurant' => ['label' => 'Nhà hàng', 'icon' => 'fa-utensils', 'color' => '#0ea5e9'],
                        'room_service' => ['label' => 'Room Service', 'icon' => 'fa-bed', 'color' => '#8b5cf6'],
                        'both' => ['label' => 'Cả hai', 'icon' => 'fa-arrows-left-right', 'color' => '#16a34a'],
                    ];
                    $s = $stMap[$st] ?? $stMap['both'];
                ?>
                    <tr data-cat="<?= $item['category_id'] ?>" data-service="<?= $st ?>"
                        data-name="<?= strtolower(e($item['name'])) ?> <?= strtolower(e($item['name_en'] ?? '')) ?>"
                        data-active="<?= $item['is_active'] ?>" data-available="<?= $item['is_available'] ?>">
                        <td>
                            <?php if ($item['image']): ?>
                                <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>" alt="" class="menu-item-image">
                            <?php else: ?>
                                <div class="menu-item-image-placeholder">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= e($item['name']) ?></strong>
                            <?php if ($item['name_en']): ?>
                                <span class="menu-item-name-en"><?= e($item['name_en']) ?></span>
                            <?php endif; ?>
                            <?php
                            $noteChips = array_filter(array_map('trim', explode(',', $item['note_options'] ?? '')));
                            if (!empty($noteChips)): ?>
                                <div class="note-chips">
                                    <?php foreach ($noteChips as $chip): ?>
                                        <span class="note-chip">
                                            <i class="fas fa-tag"></i> <?= e($chip) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="menu-item-category"><?= e($item['category_name'] ?? '') ?></td>
                        <td>
                            <span class="service-badge" style="--service-color: <?= $s['color'] ?>">
                                <i class="fas <?= $s['icon'] ?>"></i> <?= $s['label'] ?>
                            </span>
                        </td>
                        <td class="menu-item-price"><?= formatPrice($item['price']) ?></td>
                        <td>
                            <?php if (!isset($item['stock']) || $item['stock'] == -1): ?>
                                <span class="stock-infinite">∞ Không giới hạn</span>
                            <?php elseif ($item['stock'] < 5): ?>
                                <span class="badge stock-low"><?= $item['stock'] ?> còn lại</span>
                            <?php else: ?>
                                <span class="badge stock-available"><?= $item['stock'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="toggle-btn <?= $item['is_active'] ? 'toggle-btn--on' : '' ?>"
                                onclick="toggleItem(<?= $item['id'] ?>, 'active', this)"
                                title="<?= $item['is_active'] ? 'Đang hiện — Click để ẩn' : 'Đang ẩn — Click để hiện' ?>">
                                <i class="fas <?= $item['is_active'] ? 'fa-eye' : 'fa-eye-slash' ?>"></i>
                            </button>
                        </td>
                        <td>
                            <button class="toggle-btn <?= $item['is_available'] ? 'toggle-btn--on' : 'toggle-btn--off' ?>"
                                onclick="toggleItem(<?= $item['id'] ?>, 'available', this)"
                                title="<?= $item['is_available'] ? 'Còn hàng — Click để đánh Hết' : 'Hết hàng — Click để Mở lại' ?>">
                                <?= $item['is_available'] ? 'Còn hàng' : 'Hết hàng' ?>
                            </button>
                        </td>
                        <td>
                            <div class="menu-item-actions">
                                <a href="<?= BASE_URL ?>/admin/menu/edit?id=<?= $item['id'] ?>"
                                    class="btn btn-outline btn-sm" title="Sửa món"><i class="fas fa-pen"></i></a>
                                <form method="POST" action="<?= BASE_URL ?>/admin/menu/delete">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-danger-outline btn-sm"
                                        data-confirm="Xóa món '<?= e($item['name']) ?>'?" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="9" class="menu-empty-state">
                            <i class="fas fa-utensils fa-2x"></i>
                            Chưa có món nào.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- No results state -->
        <div id="noResultsState" class="no-results-state">
            <i class="fas fa-search fa-2x"></i>
            <p>Không tìm thấy món phù hợp</p>
            <button onclick="resetAllFilters()" class="btn btn-outline btn-sm">
                <i class="fas fa-rotate-left me-1"></i> Xóa bộ lọc
            </button>
        </div>

        <!-- Pagination -->
        <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
            <div class="menu-pagination">
                <span class="menu-pagination-info">
                    Trang <strong><?= $pagination['page'] ?>/<?= $pagination['totalPages'] ?></strong>
                    (<?= $pagination['total'] ?> món)
                </span>
                <div class="menu-pagination-buttons">
                    <?php
                    $currentPage = $pagination['page'];
                    $totalPages = $pagination['totalPages'];

                    // First page
                    if ($currentPage > 1):
                        ?>
                        <a href="?page=1" class="btn btn-outline btn-sm"><i class="fas fa-angles-left"></i> Đầu</a>
                    <?php endif; ?>

                    <!-- Previous page -->
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?= $currentPage - 1 ?>" class="btn btn-outline btn-sm"><i class="fas fa-angle-left"></i> Trước</a>
                    <?php endif; ?>

                    <!-- Page numbers -->
                    <?php
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);

                    if ($startPage > 1):
                        ?>
                        <span class="btn btn-outline btn-sm menu-pagination-ellipsis">...</span>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="?page=<?= $i ?>" class="btn btn-sm <?= $i === $currentPage ? 'btn-gold' : 'btn-outline' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($endPage < $totalPages): ?>
                        <span class="btn btn-outline btn-sm menu-pagination-ellipsis">...</span>
                    <?php endif; ?>

                    <!-- Next page -->
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?= $currentPage + 1 ?>" class="btn btn-outline btn-sm">Sau <i class="fas fa-angle-right"></i></a>
                    <?php endif; ?>

                    <!-- Last page -->
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?= $totalPages ?>" class="btn btn-outline btn-sm">Cuối <i class="fas fa-angles-right"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
