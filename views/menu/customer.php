<?php // views/menu/customer.php — Customer Digital Menu (Mobile First) ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/menu/customer.css">

<div class="customer-menu-container">
    <!-- Category Navigation (Horizontal Scroll) -->
    <nav class="category-nav">
        <div class="category-nav-inner">
            <a href="#all" class="cat-pill active" data-category="all">Tất cả</a>
            <?php foreach ($categories as $cat): ?>
                <a href="#cat-<?= $cat['id'] ?>" class="cat-pill" data-category="<?= $cat['id'] ?>">
                    <?= e($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- Search / Filter (Optional) -->
    <div class="menu-search-bar">
        <i class="fas fa-search"></i>
        <input type="text" id="menuSearch" placeholder="Tìm món ăn...">
    </div>

    <!-- Menu Items List -->
    <div class="menu-sections">
        <?php 
        $grouped = [];
        foreach ($menuItems as $item) {
            $catId = $item['category_id'] ?? 0;
            $grouped[$catId][] = $item;
        }
        ?>

        <?php foreach ($categories as $cat): ?>
            <?php if (isset($grouped[$cat['id']])): ?>
                <section class="menu-section" id="cat-<?= $cat['id'] ?>">
                    <h2 class="section-title">
                        <?= e($cat['name']) ?>
                        <?php if (!empty($cat['name_en'])): ?>
                            <span class="section-title-en">/ <?= e($cat['name_en']) ?></span>
                        <?php endif; ?>
                    </h2>
                    <div class="menu-grid">
                        <?php foreach ($grouped[$cat['id']] as $item): ?>
                            <div class="menu-card" data-id="<?= $item['id'] ?>" data-name="<?= e($item['name']) ?>" data-price="<?= $item['price'] ?>">
                                <div class="menu-card-img-wrapper" onclick="showItemDetail(<?= e(json_encode($item)) ?>)">
                                    <?php if ($item['image']): ?>
                                        <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>" class="menu-card-img">
                                    <?php else: ?>
                                        <div class="menu-card-placeholder"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                    <?php if ($item['is_bestseller']): ?>
                                        <span class="badge-bestseller">Bestseller</span>
                                    <?php endif; ?>
                                </div>
                                <div class="menu-card-body">
                                    <h3 class="menu-card-name" onclick="showItemDetail(<?= e(json_encode($item)) ?>)">
                                        <?= e($item['name']) ?>
                                        <?php if (!empty($item['name_en'])): ?>
                                            <div class="menu-card-name-en"><?= e($item['name_en']) ?></div>
                                        <?php endif; ?>
                                    </h3>
                                    <div class="menu-card-footer">
                                        <span class="menu-card-price"><?= formatPrice($item['price']) ?></span>
                                        <button class="btn-add-quick" onclick="quickAdd(<?= $item['id'] ?>, '<?= e($item['name']) ?>', <?= $item['price'] ?>)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<!-- Floating Cart Button -->
<div id="cartBar" class="cart-bar hidden">
    <div class="cart-bar-info">
        <div class="cart-bar-count"><span id="cartCount">0</span> món</div>
        <div class="cart-bar-total" id="cartTotal">0₫</div>
    </div>
    <button class="btn-view-cart" onclick="toggleCartModal()">
        Xem giỏ hàng <i class="fas fa-chevron-right"></i>
    </button>
</div>

<!-- Cart Modal -->
<div id="cartModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom">
        <div class="modal-header">
            <h3>Giỏ hàng của bạn</h3>
            <button class="modal-close" onclick="toggleCartModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="cartItemsList">
            <!-- Items filled by JS -->
        </div>
        <div class="modal-footer">
            <div class="cart-notes">
                <textarea id="orderNotes" placeholder="Ghi chú thêm cho đầu bếp..."></textarea>
            </div>
            <div class="cart-summary">
                <div class="summary-row">
                    <span>Tổng tiền</span>
                    <strong id="modalCartTotal">0₫</strong>
                </div>
            </div>
            <button class="btn-submit-order" onclick="submitOrder()">
                <i class="fas fa-paper-plane"></i> GỬI ORDER
            </button>
        </div>
    </div>
</div>

<!-- Item Detail Modal -->
<div id="itemDetailModal" class="modal-backdrop hidden">
    <div class="modal modal-premium">
        <div class="modal-header-img" id="detailImg"></div>
        <div class="modal-body">
            <h2 id="detailName"></h2>
            <div id="detailPrice" class="text-gold fw-bold mb-2"></div>
            <p id="detailDesc" class="text-muted mb-4"></p>
            
            <div class="qty-control-wrapper mb-4">
                <label>Số lượng</label>
                <div class="qty-control">
                    <button onclick="changeDetailQty(-1)"><i class="fas fa-minus"></i></button>
                    <span id="detailQty">1</span>
                    <button onclick="changeDetailQty(1)"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Ghi chú món (nếu có)</label>
                <input type="text" id="detailNote" class="form-control" placeholder="Ví dụ: Ít cay, không hành...">
            </div>
            
            <button class="btn-gold w-100 py-3" onclick="addFromDetail()">
                Thêm vào giỏ hàng - <span id="detailBtnTotal"></span>
            </button>
        </div>
    </div>
</div>

<script>
    const CUSTOMER_CONFIG = {
        tableId: <?= $table['id'] ?>,
        tableName: '<?= e($table['name']) ?>',
        baseUrl: '<?= BASE_URL ?>'
    };
</script>
<script src="<?= BASE_URL ?>/public/js/menu/customer.js" defer></script>
