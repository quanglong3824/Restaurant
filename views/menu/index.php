<?php // views/menu/index.php — Digital Menu (Waiter POS & Showcase) ?>
<style>
    /* Menu Type Tabs */
    .menu-type-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        background: var(--surface-2);
        padding: 0.5rem;
        border-radius: var(--radius-lg);
        overflow-x: auto;
    }

    .menu-type-tab {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: transparent;
        border: 2px solid transparent;
        border-radius: var(--radius-md);
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .menu-type-tab:hover {
        background: var(--gold-light);
        color: var(--gold-dark);
    }

    .menu-type-tab.is-active {
        background: var(--gold);
        color: #fff;
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .menu-type-tab i {
        font-size: 1rem;
    }
    
    /* Category Filter Pills */
    .category-filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: var(--surface-2);
        border-radius: var(--radius-lg);
    }
    
    .filter-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }
    
    .filter-pill:hover {
        border-color: var(--gold);
        color: var(--gold-dark);
    }
    
    .filter-pill.is-active {
        background: var(--gold);
        color: #fff;
        border-color: var(--gold);
    }
    
    .filter-pill i {
        font-size: 0.75rem;
    }
    
    /* Pagination */
    .menu-pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
        padding: 1rem;
    }
    
    .pagination-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        color: var(--text);
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
        text-decoration: none;
    }
    
    .pagination-btn:hover:not(:disabled) {
        border-color: var(--gold);
        color: var(--gold);
    }
    
    .pagination-btn.is-active {
        background: var(--gold);
        color: #fff;
        border-color: var(--gold);
    }
    
    .pagination-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
    
    .pagination-info {
        padding: 0 1rem;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    /* Set Cards for À la carte */
    .set-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 1rem;
    }

    .set-card:hover {
        border-color: var(--gold);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
    }

    .set-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }

    .set-card-img {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-md);
        object-fit: cover;
        background: var(--surface-2);
    }

    .set-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 0.25rem;
    }

    .set-card-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .set-card-price {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--gold);
    }

    .set-items-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px dashed var(--border);
    }

    .set-item-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        background: var(--surface-2);
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text);
    }

    .set-item-tag.required {
        background: var(--gold-light);
        color: var(--gold-dark);
        border: 1px solid var(--gold);
    }

    /* Modern List View Menu */
    .app-menu-header {
        margin-bottom: 1.5rem;
    }

    .cat-pills-wrapper {
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 0.5rem;
        scrollbar-width: none;
    }

    .cat-pills-wrapper::-webkit-scrollbar {
        display: none;
    }

    .cat-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.25rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 0.75rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .cat-pill:hover {
        border-color: var(--gold-dark);
        color: var(--gold-dark);
    }

    .cat-pill.is-active {
        background: var(--gold);
        color: #fff;
        border-color: var(--gold);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    /* Layout POS Split */
    .pos-layout {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .pos-menu-col {
        flex: 1;
        min-width: 0;
    }

    .pos-cart-col {
        width: 100%;
    }

    @media (min-width: 1024px) {
        .pos-layout {
            flex-direction: row;
            align-items: flex-start;
        }

        .pos-cart-col {
            width: 380px;
            position: sticky;
            top: 1.5rem;
        }
    }

    /* List Item Card (Ngang) */
    .list-item-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 0.75rem;
        margin-bottom: 1rem;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-card);
        cursor: pointer;
        transition: all 0.2s;
    }

    .list-item-card:hover {
        transform: translateX(4px);
        border-color: var(--border-gold);
    }

    .list-item-img {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-md);
        object-fit: cover;
        background: var(--surface-2);
        flex-shrink: 0;
    }

    .list-item-body {
        flex: 1;
        min-width: 0;
    }

    .list-item-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .list-item-en {
        font-size: 0.8rem;
        color: var(--text-dim);
        margin-bottom: 0.3rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .list-item-price {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--gold-dark);
    }

    .list-item-action {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--gold-light);
        color: var(--gold-dark);
        margin-left: 0.5rem;
        flex-shrink: 0;
    }

    .cat-hero-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--text);
        margin: 2rem 0 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
    }

    .cat-hero-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
        min-width: 50px;
    }
    
    /* Fix for menu section layout */
    .menu-section {
        width: 100%;
        clear: both;
        display: block;
    }
    
    .menu-items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
        width: 100%;
    }

    /* Cart Styling */
    .cart-panel {
        background: var(--surface);
        border-radius: var(--radius-xl);
        border: 1px solid var(--border);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        max-height: calc(100vh - 40px);
    }

    .cart-header {
        padding: 1.25rem;
        background: var(--gold-light);
        border-bottom: 1px solid var(--border-gold);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-body {
        padding: 1rem;
        overflow-y: auto;
        flex: 1;
    }

    .cart-footer {
        padding: 1.25rem;
        border-top: 1px solid var(--border);
        background: var(--surface);
    }

    /* Modal Khổng Lồ Đẹp Nhất */
    .detail-modal-card {
        max-width: 540px !important;
        border-radius: var(--radius-xl) !important;
        display: flex;
        flex-direction: column;
        max-height: 90vh;
    }

    .detail-modal-hero {
        width: 100%;
        height: 260px;
        background-color: var(--surface-2);
        position: relative;
        flex-shrink: 0;
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        gap: 0;
    }

    .detail-modal-hero::-webkit-scrollbar {
        height: 6px;
    }

    .detail-modal-hero::-webkit-scrollbar-thumb {
        background: var(--gold);
        border-radius: 4px;
    }

    .detail-modal-slide-img {
        flex: 0 0 100%;
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: #000;
        scroll-snap-align: start;
    }

    .detail-modal-body {
        padding: 1.75rem;
        overflow-y: auto;
        flex: 1;
    }

    .close-float-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(8px);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 1.2rem;
        color: var(--text);
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }
</style>

<div class="page-content pos-layout">

    <!-- CỘT TRÁI: TẤT CẢ MENU DIỆN MẠO MỚI -->
    <div class="pos-menu-col">
        <!-- Menu Type Tabs -->
        <div class="menu-type-tabs">
            <?php foreach ($menuTypes as $type): ?>
                <a href="<?= BASE_URL ?>/menu?type=<?= e($type['key']) ?><?= $tableId ? '&table_id='.$tableId : '' ?><?= $orderId ? '&order_id='.$orderId : '' ?>" 
                    class="menu-type-tab <?= $currentType === $type['key'] ? 'is-active' : '' ?>">
                    <i class="fas <?= e($type['icon']) ?>"></i>
                    <?= e($type['label']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($currentType === 'alacarte' && !empty($sets)): ?>
            <!-- Sets/Combos Section with Filter -->
            <div class="category-filter-bar" style="margin-bottom: 1rem;">
                <button class="filter-pill is-active" data-set-filter="all">
                    <i class="fas fa-th-large"></i> Tất cả Set
                </button>
                <button class="filter-pill" data-set-filter="breakfast">
                    <i class="fas fa-coffee"></i> Ăn Sáng
                </button>
                <button class="filter-pill" data-set-filter="lunch">
                    <i class="fas fa-utensils"></i> Trưa
                </button>
                <button class="filter-pill" data-set-filter="dinner">
                    <i class="fas fa-moon"></i> Tối
                </button>
                <button class="filter-pill" data-set-filter="family">
                    <i class="fas fa-users"></i> Gia Đình
                </button>
                <button class="filter-pill" data-set-filter="bbq">
                    <i class="fas fa-fire"></i> BBQ
                </button>
                <button class="filter-pill" data-set-filter="seafood">
                    <i class="fas fa-fish"></i> Hải Sản
                </button>
            </div>
            
            <div id="setsContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem;">
                <?php foreach ($sets as $set): ?>
                    <?php
                    // Determine set category based on name
                    $setCategory = 'other';
                    $setNameLower = strtolower($set['name']);
                    if (strpos($setNameLower, 'ăn sáng') !== false || strpos($setNameLower, 'breakfast') !== false) {
                        $setCategory = 'breakfast';
                    } elseif (strpos($setNameLower, 'trưa') !== false || strpos($setNameLower, 'lunch') !== false || strpos($setNameLower, 'văn phòng') !== false) {
                        $setCategory = 'lunch';
                    } elseif (strpos($setNameLower, 'tối') !== false || strpos($setNameLower, 'dinner') !== false || strpos($setNameLower, 'lãng mạn') !== false) {
                        $setCategory = 'dinner';
                    } elseif (strpos($setNameLower, 'gia đình') !== false || strpos($setNameLower, 'family') !== false) {
                        $setCategory = 'family';
                    } elseif (strpos($setNameLower, 'bbq') !== false) {
                        $setCategory = 'bbq';
                    } elseif (strpos($setNameLower, 'hải sản') !== false || strpos($setNameLower, 'seafood') !== false) {
                        $setCategory = 'seafood';
                    }
                    ?>
                    <div class="set-card" data-set-category="<?= $setCategory ?>" onclick="openSetModal(<?= htmlspecialchars(json_encode($set)) ?>)">
                            <div class="set-card-header">
                                <?php if ($set['image']): ?>
                                    <img src="<?= BASE_URL ?>/public/uploads/<?= e($set['image']) ?>" class="set-card-img" alt="<?= e($set['name']) ?>">
                                <?php else: ?>
                                    <div class="set-card-img" style="display:flex;align-items:center;justify-content:center;background:var(--surface-2);">
                                        <i class="fas fa-utensils" style="font-size:2rem;color:var(--gold);"></i>
                                    </div>
                                <?php endif; ?>
                                <div style="flex:1;">
                                    <div class="set-card-title"><?= e($set['name']) ?></div>
                                    <div class="set-card-price"><?= formatPrice($set['price']) ?></div>
                                </div>
                            </div>
                            <?php if ($set['description']): ?>
                                <div class="set-card-desc"><?= e($set['description']) ?></div>
                            <?php endif; ?>
                            <div class="set-items-list">
                                <?php foreach ($set['items'] as $si): ?>
                                    <span class="set-item-tag <?= $si['is_required'] ? 'required' : '' ?>">
                                        <i class="fas fa-<?= $si['is_required'] ? 'check' : 'circle' ?>"></i>
                                        <?= e($si['name']) ?> x<?= $si['quantity'] ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Category Filter Bar (Only for Asia/Europe tabs) -->
        <?php if (!empty($categories) && $currentType !== 'alacarte'): ?>
            <div class="category-filter-bar">
                <button class="filter-pill is-active" data-filter="all">
                    <i class="fas fa-th-large"></i> Tất cả
                </button>
                <?php foreach ($categories as $cat): ?>
                    <button class="filter-pill" data-filter="<?= e($cat['name']) ?>">
                        <i class="fas <?= e($cat['icon'] ?? 'fa-utensils') ?>"></i>
                        <?= e($cat['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Menu Items with Pagination -->
        <div id="menuItemsContainer">
            <?php
            // Flatten items for pagination
            $allItems = [];
            foreach ($grouped as $catName => $items) {
                foreach ($items as $item) {
                    $item['category_name'] = $catName;
                    $allItems[] = $item;
                }
            }
            
            $itemsPerPage = 12;
            $totalPages = ceil(count($allItems) / $itemsPerPage);
            $currentPage = max(1, min((int)($this->input('page', 1)), $totalPages));
            $offset = ($currentPage - 1) * $itemsPerPage;
            $pagedItems = array_slice($allItems, $offset, $itemsPerPage);
            
            // Group paged items by category for display
            $pagedGrouped = [];
            foreach ($pagedItems as $item) {
                $catName = $item['category_name'];
                if (!isset($pagedGrouped[$catName])) {
                    $pagedGrouped[$catName] = [];
                }
                $pagedGrouped[$catName][] = $item;
            }
            ?>
            
            <?php foreach ($pagedGrouped as $catName => $items): ?>
                <div class="menu-section" data-section="<?= e($catName) ?>">
                    <h2 class="cat-hero-title">
                        <span style="color:var(--gold)"><i class="fas fa-hashtag"></i></span> <?= e($catName) ?>
                    </h2>

                    <div class="menu-items-grid">
                        <?php foreach ($items as $item): ?>
                        <?php
                        // Check if item is available and has stock (>0 or -1)
                        $isStockOut = isset($item['stock']) && $item['stock'] == 0;
                        $unavailable = (!$item['is_available'] || $isStockOut) ? 'opacity:0.5; pointer-events:none; filter:grayscale(1);' : '';
                        $imgSrc = $item['image'] ? BASE_URL . '/public/uploads/' . e($item['image']) : '';
                        $galleryStr = '';
                        if (!empty($item['gallery'])) {
                            $gArr = [];
                            foreach (explode('|', $item['gallery']) as $g) {
                                $gArr[] = BASE_URL . '/public/uploads/' . e($g);
                            }
                            $galleryStr = implode('|', $gArr);
                        }
                        ?>
                        <div class="list-item-card" style="<?= $unavailable ?>">
                            <div style="display:flex; flex:1; align-items:center; cursor:pointer;" data-id="<?= $item['id'] ?>"
                                data-name="<?= e($item['name']) ?>" data-price="<?= $item['price'] ?>" data-img="<?= $imgSrc ?>"
                                data-desc="<?= e($item['description'] ?? '') ?>" data-order="<?= $orderId ?: '' ?>"
                                data-gallery="<?= $galleryStr ?>" onclick="handleOpenItemModal(this)">
                                <?php if ($item['image']): ?>
                                    <img src="<?= $imgSrc ?>" class="list-item-img" alt="<?= e($item['name']) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="list-item-img"
                                        style="display:flex;align-items:center;justify-content:center;font-size:1.8rem;color:var(--border-gold);">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="list-item-body">
                                    <h3 class="list-item-name"><?= e($item['name']) ?></h3>
                                    <?php if ($item['name_en']): ?>
                                        <div class="list-item-en"><?= e($item['name_en']) ?></div>
                                    <?php endif; ?>
                                    <div class="list-item-price"><?= formatPrice($item['price']) ?></div>
                                </div>
                            </div>

                            <div class="list-item-action"
                                onclick="quickAdd(event, <?= $item['id'] ?>, <?= $orderId ?: 'null' ?>)"
                                style="cursor:pointer; z-index:2;">
                                <i class="fas fa-plus"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($allItems)): ?>
            <div class="empty-state" style="margin-top:4rem; text-align:center;">
                <i class="fas fa-box-open" style="font-size:3rem; color:var(--text-dim); margin-bottom:1rem;"></i>
                <h3>Menu chưa có dữ liệu</h3>
                <p style="color:var(--text-muted)">Admin hãy bổ sung các món ăn hấp dẫn vào nhé.</p>
            </div>
        <?php endif; ?>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="menu-pagination">
                <a href="?page=<?= max(1, $currentPage - 1) ?>&type=<?= e($currentType) ?><?= $tableId ? '&table_id='.$tableId : '' ?>" 
                    class="pagination-btn" <?= $currentPage <= 1 ? 'disabled' : '' ?>>
                    <i class="fas fa-chevron-left"></i>
                </a>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == 1 || $i == $totalPages || ($i >= $currentPage - 1 && $i <= $currentPage + 1)): ?>
                        <a href="?page=<?= $i ?>&type=<?= e($currentType) ?><?= $tableId ? '&table_id='.$tableId : '' ?>" 
                            class="pagination-btn <?= $i == $currentPage ? 'is-active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php elseif ($i == $currentPage - 2 || $i == $currentPage + 2): ?>
                        <span class="pagination-info">...</span>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <a href="?page=<?= min($totalPages, $currentPage + 1) ?>&type=<?= e($currentType) ?><?= $tableId ? '&table_id='.$tableId : '' ?>" 
                    class="pagination-btn" <?= $currentPage >= $totalPages ? 'disabled' : '' ?>>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <div style="text-align:center; margin-top:0.5rem; font-size:0.85rem; color:var(--text-muted);">
                Trang <?= $currentPage ?> / <?= $totalPages ?> · <?= count($allItems) ?> món
            </div>
        <?php endif; ?>
    </div>

    <!-- CỘT PHẢI: GIỎ HÀNG (CHỈ HIỂN THỊ KHI ĐÃ CHỌN BÀN GỌI MÓN) -->
    <?php if ($orderId > 0): ?>
        <div class="pos-cart-col">
            <div class="cart-panel">
                <div class="cart-header">
                    <div>
                        <h3 style="color:var(--gold-dark); margin-bottom: 0.1rem;"><i class="fas fa-receipt"></i> Giỏ đang
                            gọi</h3>
                        <span style="font-size: 0.8rem; color:var(--text); opacity:0.8;">Bàn #<?= e($tableId) ?> -
                            <?= e($order['guest_count'] ?? 1) ?> khách</span>
                    </div>
                    <div><span style="font-weight:800; font-size:1.2rem; color:var(--gold-dark);"><?= count($orderItems) ?>
                            món</span></div>
                </div>

                <div class="cart-body">
                    <?php $draftCount = 0; ?>
                    <?php if (empty($orderItems)): ?>
                        <p style="text-align:center; color:var(--text-muted); font-size:0.9rem; margin-top:2rem;">Chưa có món
                            nào được chọn.</p>
                    <?php else: ?>
                        <?php foreach ($orderItems as $idx => $it): ?>
                            <?php if ($it['status'] === 'draft')
                                $draftCount++; ?>
                            <div
                                style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem; padding-bottom:1rem; border-bottom:1px solid var(--surface-2);">
                                <div style="flex:1;">
                                    <div style="font-weight:600; font-size:0.95rem; color:var(--text);"><?= e($it['item_name']) ?>
                                    </div>
                                    <div style="font-size:0.8rem; color:var(--text-dim); margin-top:2px;">
                                        <?= formatPrice($it['item_price']) ?> x <?= $it['quantity'] ?>
                                    </div>
                                    <?php if ($it['notes']): ?>
                                        <div style="font-size:0.8rem; color:var(--warning); font-style:italic; margin-top:4px;"><i
                                                class="fas fa-pen"></i> <?= e($it['notes']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div style="font-weight:800; font-size:0.95rem; text-align:right;">
                                    <?= formatPrice($it['item_price'] * $it['quantity']) ?>
                                    <?php if ($it['status'] === 'confirmed'): ?>
                                        <br><span style="font-size:0.75rem; color:var(--success); font-weight:normal;"><i
                                                class="fas fa-check-circle"></i> Bếp đang làm</span>
                                    <?php else: ?>
                                        <br><span style="font-size:0.75rem; color:var(--text-muted); font-weight:normal;">Món
                                            nháp</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="cart-footer">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.25rem;">
                        <span style="font-weight:600; color:var(--text);">Tổng thanh toán</span>
                        <span
                            style="font-weight:800; font-size:1.4rem; color:var(--danger);"><?= formatPrice($orderTotal) ?></span>
                    </div>
                    <?php if (isset($isCustomer) && $isCustomer): ?>
                        <div style="display:flex; gap:0.5rem;">
                            <?php if ($draftCount > 0): ?>
                                <button type="button" onclick="customerConfirmOrder(<?= $orderId ?>)"
                                    class="btn btn-gold btn-block btn-lg" style="flex: 1; justify-content:center;">
                                    <i class="fas fa-bell"></i> GỬI YÊU CẦU ĐẶT MÓN
                                </button>
                            <?php else: ?>
                                <div style="text-align:center; width:100%; color:var(--success); font-weight:600;">
                                    <i class="fas fa-check-circle"></i> Đang chờ phục vụ...
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div style="display:flex; gap:0.5rem;">
                            <a href="<?= BASE_URL ?>/orders?table_id=<?= $tableId ?>&order_id=<?= $orderId ?>"
                                class="btn btn-gold btn-block btn-lg" style="flex: 1; justify-content:center;">
                                <i class="fas fa-paper-plane"></i>
                                <?= $draftCount > 0 ? "Gửi bếp ($draftCount món)" : "Xem chi tiết Bill" ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- ========================================== -->
<!-- SIÊU MODAL: CHI TIẾT ĐỂ THÊM VÀO GIỎ / MỞ BÀN GỌI MÓN MỚI -->
<!-- ========================================== -->
<div class="modal-backdrop" id="modalItemDetail">
    <div class="modal detail-modal-card" style="padding: 0;">
        <div class="detail-modal-hero" id="modalItemImg">
            <button class="close-float-btn" data-modal-close type="button"><i class="fas fa-times"></i></button>
            <div id="modalItemImgPlaceholder"
                style="display:flex;align-items:center;justify-content:center;height:100%;font-size:4rem;color:var(--text-muted); background:var(--surface-2);">
                <i class="fas fa-image"></i>
            </div>
        </div>

        <div class="detail-modal-body bg-blur">
            <h2 id="modalItemName"
                style="font-size: 1.6rem; font-weight:800; margin-bottom: 0.25rem; color:var(--text);">Tên món</h2>
            <div id="modalItemPrice"
                style="color: var(--gold-dark); font-weight: 800; font-size: 1.35rem; margin-bottom: 1rem;">Giá</div>

            <div id="descBlock"
                style="background:var(--surface-2); padding:1rem; border-radius:var(--radius-md); margin-bottom:1.5rem;">
                <h4
                    style="font-size:0.85rem; text-transform:uppercase; color:var(--text-dim); letter-spacing:1px; margin-bottom:0.5rem;">
                    <i class="fas fa-leaf"></i> Thành phần & Hương vị
                </h4>
                <p id="modalItemDesc" style="color: var(--text); font-size: 0.95rem; line-height: 1.5; margin:0;"></p>
            </div>

            <div id="orderControlsSection"
                style="display:none; border-top:1px dashed var(--border); padding-top:1.5rem;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color:var(--text);"><i class="fas fa-pen"></i>
                        Ghi chú riêng cho Nhà bếp</label>
                    <input type="text" id="modalItemNote" class="form-control"
                        placeholder="VD: Không hành, thêm phô mai, cay ít..."
                        style="background:var(--bg); padding:12px;">
                </div>

                <div
                    style="display: flex; align-items: center; justify-content: space-between; margin-top: 1.5rem; gap: 1rem;">
                    <div
                        style="display: flex; align-items: center; background: var(--bg); border: 1px solid var(--border); border-radius: 50px; padding: 4px;">
                        <button type="button" onclick="changeModalQty(-1)"
                            style="width: 44px; height: 44px; border:none; background:transparent; font-size:1.1rem; cursor:pointer; color:var(--text);"><i
                                class="fas fa-minus"></i></button>
                        <span id="modalItemQty"
                            style="width: 40px; text-align: center; font-weight: 800; font-size: 1.15rem; color:var(--text);">1</span>
                        <button type="button" onclick="changeModalQty(1)"
                            style="width: 44px; height: 44px; border:none; background:transparent; font-size:1.1rem; cursor:pointer; color:var(--text);"><i
                                class="fas fa-plus"></i></button>
                    </div>

                    <button type="button" id="modalBtnAdd" class="btn btn-gold btn-lg"
                        style="flex: 1; justify-content: center; height: 54px; box-shadow: 0 4px 16px rgba(212,175,55,0.3);"
                        onclick="confirmAddToOrder()">
                        <i class="fas fa-cart-plus"></i> THÊM BÀN ĐANG CHỌN <span id="modalBtnTotal"
                            style="margin-left: 6px;"></span>
                    </button>
                </div>
            </div>

            <?php if (!isset($isCustomer) || !$isCustomer): ?>
                <div id="viewOnlySection" style="display:none; border-top:1px dashed var(--border); padding-top:1.5rem;">
                    <p style="font-weight:600; color:var(--text); margin-bottom: 0.75rem;"><i class="fas fa-bolt"></i> Mở
                        Bàn Mới Để Đặt Món Này Vào:</p>
                    <form action="<?= BASE_URL ?>/tables/open" method="POST" id="formDirectOpen">
                        <input type="hidden" name="direct_menu_item_id" id="directMenuItemId">
                        <div class="form-group">
                            <select name="table_id" class="form-control"
                                style="background:var(--bg); border:1px solid var(--border);" required>
                                <option value="">-- CHỌN BÀN TRỐNG ĐỂ VÀO NGỒI --</option>
                                <?php if (isset($allTables)): ?>
                                    <?php
                                    $freeTables = array_filter($allTables, fn($t) => $t['status'] === 'available');
                                    $busyTables = array_filter($allTables, fn($t) => $t['status'] === 'occupied');
                                    ?>
                                    <?php if (!empty($freeTables)): ?>
                                        <optgroup label="Bàn Trống (Mở mới)">
                                            <?php foreach ($freeTables as $t): ?>
                                                <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (Khu <?= e($t['area']) ?>)</option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endif; ?>
                                    <?php if (!empty($busyTables)): ?>
                                        <optgroup label="Bàn Đang Phục Vụ">
                                            <?php foreach ($busyTables as $t): ?>
                                                <option value="<?= $t['id'] ?>" disabled><?= e($t['name']) ?> - Đang bận</option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group" style="display:none;">
                            <input type="number" name="guest_count" value="1">
                        </div>
                        <button type="submit" class="btn btn-gold btn-block btn-lg" style="margin-top: 1rem;"><i
                                class="fas fa-door-open"></i> Vào Bàn & Lưu Order</button>
                    </form>
                </div>
            <?php else: ?>
                <div id="viewOnlySection"
                    style="display:none; border-top:1px dashed var(--border); padding-top:1.5rem; text-align:center;">
                    <p style="color:var(--text-muted);">Vui lòng thêm món vào giỏ hàng bên dưới.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Set/Combo Detail Modal -->
<div class="modal-backdrop" id="setDetailModal">
    <div class="modal" style="max-width: 500px;">
        <div class="modal-header">
            <h3 id="setModalName">Tên Set</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div id="setModalPrice" style="font-size: 1.5rem; font-weight: 800; color: var(--gold);"></div>
            </div>
            <p id="setModalDesc" style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;"></p>
            <div style="background: var(--surface-2); padding: 1rem; border-radius: var(--radius-md);">
                <h4 style="font-size: 0.85rem; text-transform: uppercase; color: var(--text-dim); margin-bottom: 0.75rem;">
                    <i class="fas fa-list"></i> Món trong set
                </h4>
                <div id="setModalItems" class="set-items-list" style="border: none; padding: 0; margin: 0;"></div>
            </div>
            <button id="setModalAddBtn" class="btn btn-gold btn-block btn-lg" style="margin-top: 1.5rem;">
                <i class="fas fa-cart-plus"></i> THÊM SET VÀO ORDER
            </button>
        </div>
    </div>
</div>

<div id="addToast" class="add-toast" aria-live="polite"></div>

<script>
    let currentItem = null;

    // Set Category Filter (À la carte tab)
    document.querySelectorAll('[data-set-filter]').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('[data-set-filter]').forEach(p => p.classList.remove('is-active'));
            pill.classList.add('is-active');

            const filter = pill.dataset.setFilter;
            const sets = document.querySelectorAll('#setsContainer .set-card');
            sets.forEach(card => {
                const category = card.dataset.setCategory;
                card.style.display = (filter === 'all' || category === filter) ? '' : 'none';
            });
        });
    });

    // Category Filter Pills (Main Menu)
    document.querySelectorAll('.filter-pill').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('is-active'));
            pill.classList.add('is-active');

            const filter = pill.dataset.filter;
            if (filter !== 'all') {
                document.querySelectorAll('.menu-section').forEach(section => {
                    section.style.display = (section.dataset.section === filter) ? '' : 'none';
                });
            } else {
                document.querySelectorAll('.menu-section').forEach(section => {
                    section.style.display = '';
                });
            }
        });
    });

    // Legacy Cat Pills (keep for backward compatibility)
    document.querySelectorAll('.cat-pill').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('is-active'));
            pill.classList.add('is-active');

            const cat = pill.dataset.cat;
            document.querySelectorAll('.menu-section').forEach(section => {
                section.style.display = (cat === 'all' || section.dataset.section === cat) ? '' : 'none';
            });
        });
    });

    function formatMoney(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
    }

    function handleOpenItemModal(el) {
        const id = el.getAttribute('data-id');
        const name = el.getAttribute('data-name');
        const price = parseFloat(el.getAttribute('data-price')) || 0;
        const img = el.getAttribute('data-img');
        const desc = el.getAttribute('data-desc');
        const orderId = el.getAttribute('data-order') || null;
        const gallery = el.getAttribute('data-gallery');

        openItemModal(id, name, price, img, desc, orderId, gallery);
    }

    function openItemModal(id, name, price, imgSrc, desc, orderId, galleryStr) {
        currentItem = { id, name, price, orderId, qty: 1 };

        document.getElementById('modalItemName').textContent = name;
        document.getElementById('modalItemPrice').textContent = formatMoney(price);
        document.getElementById('directMenuItemId').value = id;

        const descEl = document.getElementById('modalItemDesc');
        const descBlock = document.getElementById('descBlock');
        if (desc && desc.trim() !== '') {
            descBlock.style.display = 'block';
            descEl.textContent = desc;
        } else {
            descBlock.style.display = 'none';
        }

        const imgEl = document.getElementById('modalItemImg');
        const placeholderEl = document.getElementById('modalItemImgPlaceholder');

        // Reset gallery slides only
        const oldImgs = imgEl.querySelectorAll('img.detail-modal-slide-img');
        oldImgs.forEach(img => img.remove());

        placeholderEl.style.display = 'none';

        if (galleryStr && galleryStr.trim() !== '') {
            const arr = galleryStr.split('|');
            arr.forEach(url => {
                const img = document.createElement('img');
                img.src = url;
                img.className = 'detail-modal-slide-img';
                imgEl.appendChild(img);
            });
            imgEl.style.backgroundImage = 'none';
        } else if (imgSrc && imgSrc !== '') {
            const img = document.createElement('img');
            img.src = imgSrc;
            img.className = 'detail-modal-slide-img';
            imgEl.appendChild(img);
            imgEl.style.backgroundImage = 'none';
        } else {
            placeholderEl.style.display = 'flex';
        }

        const orderSection = document.getElementById('orderControlsSection');
        const viewSection = document.getElementById('viewOnlySection');

        if (orderId && orderId !== null) {
            orderSection.style.display = 'block';
            viewSection.style.display = 'none';
            document.getElementById('modalItemNote').value = '';
            updateModalUI();
        } else {
            orderSection.style.display = 'none';
            viewSection.style.display = 'block';
        }

        Aurora.openModal('modalItemDetail');
    }

    function changeModalQty(delta) {
        if (!currentItem) return;
        currentItem.qty = Math.max(1, currentItem.qty + delta);
        updateModalUI();
    }

    function updateModalUI() {
        document.getElementById('modalItemQty').textContent = currentItem.qty;
        document.getElementById('modalBtnTotal').textContent = formatMoney(currentItem.qty * currentItem.price);
    }

    function updateCartUI(data) {
        if (!data.ok) return;
        
        const cartBody = document.querySelector('.cart-body');
        const cartTotal = document.querySelector('.order-total-bar__amount, .cart-footer .danger, #orderTotal');
        const cartItemCount = document.querySelector('.cart-header b, .cart-header span[style*="font-size:1.2rem"]');
        const cartFooter = document.querySelector('.cart-footer');
        
        if (cartTotal) cartTotal.textContent = data.total_fmt;
        if (cartItemCount) cartItemCount.textContent = data.items.length + ' món';
        
        if (cartBody) {
            if (data.items.length === 0) {
                cartBody.innerHTML = '<p style="text-align:center; color:var(--text-muted); font-size:0.9rem; margin-top:2rem;">Chưa có món nào được chọn.</p>';
            } else {
                let html = '';
                let draftCount = 0;
                data.items.forEach(it => {
                    if (it.status === 'draft') draftCount++;
                    html += `
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem; padding-bottom:1rem; border-bottom:1px solid var(--surface-2);">
                            <div style="flex:1;">
                                <div style="font-weight:600; font-size:0.95rem; color:var(--text);">${it.item_name}</div>
                                <div style="font-size:0.8rem; color:var(--text-dim); margin-top:2px;">
                                    ${it.price_fmt} x ${it.quantity}
                                    <div style="display:inline-flex; gap:0.5rem; margin-left:0.5rem;">
                                        <button onclick="changeCartQty(${it.id}, -1)" style="border:none; background:var(--surface-2); width:20px; height:20px; border-radius:50%; cursor:pointer;">-</button>
                                        <button onclick="changeCartQty(${it.id}, 1)" style="border:none; background:var(--surface-2); width:20px; height:20px; border-radius:50%; cursor:pointer;">+</button>
                                    </div>
                                </div>
                                ${it.notes ? `<div style="font-size:0.8rem; color:var(--warning); font-style:italic; margin-top:4px;"><i class="fas fa-pen"></i> ${it.notes}</div>` : ''}
                            </div>
                            <div style="font-weight:800; font-size:0.95rem; text-align:right;">
                                ${it.subtotal_fmt}
                                <br><span style="font-size:0.75rem; color:${it.status === 'confirmed' ? 'var(--success)' : 'var(--text-muted)'}; font-weight:normal;">
                                    <i class="fas ${it.status === 'confirmed' ? 'fa-check-circle' : 'fa-clock'}"></i> ${it.status === 'confirmed' ? 'Bếp đang làm' : 'Món nháp'}
                                </span>
                            </div>
                        </div>
                    `;
                });
                cartBody.innerHTML = html;
                
                // Update footer button
                if (cartFooter) {
                    const btnContainer = cartFooter.querySelector('div[style*="display:flex; gap:0.5rem"]');
                    if (btnContainer) {
                        if (draftCount > 0) {
                            btnContainer.innerHTML = `
                                <button type="button" onclick="confirmOrderAjax(<?= $orderId ?>)"
                                    class="btn btn-gold btn-block btn-lg" style="flex: 1; justify-content:center; background:var(--danger); border-color:var(--danger);">
                                    <i class="fas fa-concierge-bell"></i> XÁC NHẬN GỬI BẾP (${draftCount})
                                </button>
                            `;
                        } else {
                            btnContainer.innerHTML = `
                                <a href="<?= BASE_URL ?>/orders?table_id=<?= $tableId ?>&order_id=<?= $orderId ?>"
                                    class="btn btn-gold btn-block btn-lg" style="flex: 1; justify-content:center;">
                                    <i class="fas fa-file-invoice-dollar"></i> XEM CHI TIẾT BILL
                                </a>
                            `;
                        }
                    }
                }
            }
        }
    }

    function changeCartQty(itemId, delta) {
        const orderId = <?= $orderId ?: 0 ?>;
        if (!orderId) return;

        fetch('<?= BASE_URL ?>/orders/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ item_id: itemId, order_id: orderId, qty: 'delta:' + delta })
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) updateCartUI(data);
        });
    }

    function confirmOrderAjax(orderId) {
        if (!confirm('Xác nhận gửi các món nháp này xuống bếp?')) return;
        
        const data = new FormData();
        data.append('order_id', orderId);
        data.append('table_id', <?= $tableId ?: 0 ?>);
        
        fetch('<?= BASE_URL ?>/orders/confirm', {
            method: 'POST',
            body: data
        })
        .then(res => res.json())
        .then(res => {
            if (res.ok) {
                showToast('Đã gửi bếp thành công!');
                // Reload data to update status
                fetchCartData();
            } else {
                alert(res.message || 'Lỗi gửi yêu cầu');
            }
        })
        .catch(err => alert('Lỗi kết nối'));
    }

    function fetchCartData() {
        const orderId = <?= $orderId ?: 0 ?>;
        if (!orderId) return;
        
        // We reuse addItem with qty=0 to just get the current cart data
        fetch('<?= BASE_URL ?>/orders/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ order_id: orderId, menu_item_id: 0, qty: 0 })
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) updateCartUI(data);
        });
    }

    function confirmAddToOrder() {
        if (!currentItem || !currentItem.orderId) return;
        const note = document.getElementById('modalItemNote').value.trim();
        const btn = document.getElementById('modalBtnAdd');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
        
        const data = new FormData();
        data.append('order_id', currentItem.orderId);
        data.append('menu_item_id', currentItem.id);
        data.append('qty', currentItem.qty);
        data.append('note', note);

        fetch('<?= BASE_URL ?>/orders/add', {
            method: 'POST',
            body: data
        })
            .then(res => res.json())
            .then(res => {
                if (res.ok) {
                    Aurora.closeModal('modalItemDetail');
                    showToast('Đã thêm ' + currentItem.name);
                    updateCartUI(res);
                } else {
                    alert(res.message || 'Có lỗi xảy ra');
                }
            })
            .catch(err => {
                alert('Không thể kết nối đến server');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = `<i class="fas fa-cart-plus"></i> THÊM BÀN ĐANG CHỌN  <span id="modalBtnTotal" style="margin-left: 6px;">${formatMoney(currentItem.qty * currentItem.price)}</span>`;
            });
    }

    function quickAdd(event, itemId, orderId) {
        event.stopPropagation();

        if (!orderId) {
            alert('Vui lòng Mở Bàn trước khi gọi món! Bạn có thể bấm vào món để Mở Bàn.');
            return;
        }

        const btn = event.currentTarget;
        const orgHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.style.pointerEvents = 'none';

        const data = new FormData();
        data.append('order_id', orderId);
        data.append('menu_item_id', itemId);
        data.append('qty', 1);
        data.append('note', '');

        fetch('<?= BASE_URL ?>/orders/add', {
            method: 'POST',
            body: data
        })
            .then(res => res.json())
            .then(res => {
                if (res.ok) {
                    showToast('Đã thêm món thành công!');
                    updateCartUI(res);
                } else {
                    alert(res.message || 'Lỗi thêm món');
                }
            })
            .catch(err => {
                alert('Lỗi kết nối');
            })
            .finally(() => {
                btn.innerHTML = orgHtml;
                btn.style.pointerEvents = 'auto';
            });
    }

    function customerConfirmOrder(orderId) {
        if (!confirm('Xác nhận gửi yêu cầu đặt món này cho nhân viên phục vụ?')) return;
        
        const data = new FormData();
        data.append('order_id', orderId);
        
        fetch('<?= BASE_URL ?>/orders/confirm', {
            method: 'POST',
            body: data
        })
        .then(res => res.json())
        .then(res => {
            if (res.ok) {
                alert('Đã gửi yêu cầu thành công! Vui lòng đợi nhân viên trong giây lát.');
                window.location.reload();
            } else {
                alert(res.message || 'Lỗi gửi yêu cầu');
            }
        })
        .catch(err => alert('Lỗi kết nối'));
    }

    function showToast(msg) {
        const toast = document.getElementById('addToast');
        toast.textContent = msg;
        toast.classList.add('show');
        clearTimeout(window._toastTimer);
        window._toastTimer = setTimeout(() => toast.classList.remove('show'), 2500);
    }

    // Set/Combo Modal
    function openSetModal(set) {
        const modal = document.getElementById('setDetailModal');
        document.getElementById('setModalName').textContent = set.name;
        document.getElementById('setModalPrice').textContent = formatMoney(set.price);
        document.getElementById('setModalDesc').textContent = set.description || '';
        
        const itemsList = document.getElementById('setModalItems');
        itemsList.innerHTML = '';
        set.items.forEach(item => {
            const tag = document.createElement('span');
            tag.className = 'set-item-tag ' + (item.is_required ? 'required' : '');
            tag.innerHTML = '<i class="fas fa-' + (item.is_required ? 'check' : 'circle') + '"></i> ' + 
                           item.name + ' x' + item.quantity;
            itemsList.appendChild(tag);
        });
        
        document.getElementById('setModalAddBtn').onclick = function() {
            if (!<?= $orderId ?: 0 ?>) {
                alert('Vui lòng mở bàn trước khi gọi món!');
                return;
            }
            const btn = this;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
            btn.disabled = true;
            
            const data = new FormData();
            data.append('order_id', <?= $orderId ?: 0 ?>);
            data.append('set_id', set.id);
            data.append('is_set', '1');
            
            // Add each item in the set
            set.items.forEach((item, idx) => {
                data.append('items[' + idx + '][menu_item_id]', item.id);
                data.append('items[' + idx + '][quantity]', item.quantity);
                data.append('items[' + idx + '][is_required]', item.is_required ? '1' : '0');
            });
            
            fetch('<?= BASE_URL ?>/orders/add-set', {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(res => {
                if (res.ok) {
                    Aurora.closeModal('setDetailModal');
                    window.location.reload();
                } else {
                    alert(res.message || 'Có lỗi xảy ra');
                    btn.innerHTML = '<i class="fas fa-cart-plus"></i> THÊM SET VÀO ORDER';
                    btn.disabled = false;
                }
            })
            .catch(err => {
                alert('Không thể kết nối server');
                btn.innerHTML = '<i class="fas fa-cart-plus"></i> THÊM SET VÀO ORDER';
                btn.disabled = false;
            });
        };
        
        Aurora.openModal('setDetailModal');
    }
</script>