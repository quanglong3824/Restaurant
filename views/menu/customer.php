<?php // views/menu/customer.php — Customer Digital Menu (Mobile First) ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/menu/customer.css">

<div class="customer-menu-wrapper">
    <!-- Premium Header -->
    <header class="menu-header">
        <div class="header-top">
            <div class="brand-logo">
                <h1 class="playfair">AURORA</h1>
                <span>RESTAURANT</span>
            </div>
            <div class="table-info">
                <span class="table-label">BÀN</span>
                <span class="table-number"><?= e($table['name']) ?></span>
            </div>
        </div>
        
        <!-- Action Quick Bar -->
        <div class="action-bar">
            <button class="action-btn" onclick="callWaiter('support')">
                <i class="fas fa-hand-paper"></i>
                <span>Gọi phục vụ</span>
            </button>
            <button class="action-btn" onclick="callWaiter('payment')">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Thanh toán</span>
            </button>
            <button class="action-btn" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
                <span>Làm mới</span>
            </button>
        </div>
    </header>

    <!-- Category Navigation (Sticky) -->
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

    <!-- Search Bar -->
    <div class="menu-search-container">
        <div class="menu-search-bar">
            <i class="fas fa-search"></i>
            <input type="text" id="menuSearch" placeholder="Tìm kiếm món ăn...">
        </div>
    </div>

    <!-- Menu Content -->
    <main class="menu-sections">
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
                    <div class="section-header">
                        <h2 class="section-title"><?= e($cat['name']) ?></h2>
                        <?php if (!empty($cat['name_en'])): ?>
                            <span class="section-title-en"><?= e($cat['name_en']) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="menu-list">
                        <?php foreach ($grouped[$cat['id']] as $item): ?>
                            <div class="menu-item-card" 
                                 data-id="<?= $item['id'] ?>" 
                                 data-name="<?= e($item['name']) ?>" 
                                 data-price="<?= $item['price'] ?>"
                                 onclick="showItemDetail(<?= e(json_encode($item)) ?>)">
                                
                                <div class="item-img-box">
                                    <?php if ($item['image']): ?>
                                        <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>" loading="lazy">
                                    <?php else: ?>
                                        <div class="item-placeholder"><i class="fas fa-utensils"></i></div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($item['tags']) && !empty($item['tags'])): ?>
                                        <?php if (strpos($item['tags'], 'bestseller') !== false): ?>
                                            <span class="item-badge bestseller">HOT</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                                <div class="item-info">
                                    <div class="item-main-row">
                                        <h3 class="item-name"><?= e($item['name']) ?></h3>
                                        <span class="item-price"><?= formatPrice($item['price']) ?></span>
                                    </div>
                                    <?php if (!empty($item['name_en'])): ?>
                                        <div class="item-name-en"><?= e($item['name_en']) ?></div>
                                    <?php endif; ?>
                                    <p class="item-desc"><?= e($item['description'] ?? '') ?></p>
                                    
                                    <div class="item-footer">
                                        <button class="btn-add-circle" onclick="event.stopPropagation(); quickAdd(<?= $item['id'] ?>, '<?= e($item['name']) ?>', <?= $item['price'] ?>)">
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
    </main>
</div>

<!-- Floating Cart Button -->
<div id="cartBar" class="cart-bar hidden">
    <div class="cart-bar-content">
        <div class="cart-icon-box">
            <i class="fas fa-shopping-basket"></i>
            <span class="cart-badge" id="cartCount">0</span>
        </div>
        <div class="cart-info">
            <span class="cart-label">Giỏ hàng của bạn</span>
            <span class="cart-total" id="cartTotal">0₫</span>
        </div>
        <button class="btn-view-cart" onclick="toggleCartModal()">
            XEM GIỎ <i class="fas fa-chevron-right ms-1"></i>
        </button>
    </div>
</div>

<!-- Cart Modal (Slide Up) -->
<div id="cartModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom">
        <div class="modal-header">
            <h3><i class="fas fa-shopping-cart me-2"></i> Chi tiết đơn hàng</h3>
            <button class="modal-close" onclick="toggleCartModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="cartItemsList" class="cart-items-container">
                <!-- Items filled by JS -->
            </div>
            
            <div class="order-notes-box mt-3">
                <label class="form-label small fw-bold text-muted">GHI CHÚ ĐƠN HÀNG</label>
                <textarea id="orderNotes" placeholder="VD: Không lấy hành, ít cay..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <div class="total-summary">
                <span>Tổng cộng</span>
                <strong id="modalCartTotal">0₫</strong>
            </div>
            <button class="btn-submit-order" id="btnSubmitOrder" onclick="submitOrder()">
                <i class="fas fa-paper-plane me-2"></i> XÁC NHẬN GỬI BẾP
            </button>
        </div>
    </div>
</div>

<!-- Item Detail Modal -->
<div id="itemDetailModal" class="modal-backdrop hidden">
    <div class="modal modal-center modal-premium">
        <div class="detail-img-box" id="detailImg"></div>
        <div class="modal-body">
            <div class="detail-header">
                <h2 id="detailName"></h2>
                <div id="detailPrice" class="detail-price-val"></div>
            </div>
            <p id="detailDesc" class="detail-description"></p>
            
            <div class="detail-controls">
                <div class="qty-selector">
                    <button class="qty-btn" onclick="changeDetailQty(-1)"><i class="fas fa-minus"></i></button>
                    <span id="detailQty" class="qty-value">1</span>
                    <button class="qty-btn" onclick="changeDetailQty(1)"><i class="fas fa-plus"></i></button>
                </div>
                
                <div class="detail-note-input">
                    <input type="text" id="detailNote" placeholder="Ghi chú cho món này...">
                </div>
            </div>
            
            <button class="btn-add-main" onclick="addFromDetail()">
                THÊM VÀO GIỎ - <span id="detailBtnTotal"></span>
            </button>
            <button class="btn-close-detail" onclick="closeItemDetail()">ĐÓNG</button>
        </div>
    </div>
</div>

<!-- Scripts Configuration -->
<script>
    const CUSTOMER_CONFIG = {
        tableId: <?= $table['id'] ?>,
        tableName: '<?= e($table['name']) ?>',
        baseUrl: '<?= BASE_URL ?>',
        isIT: <?= Auth::isIT() ? 'true' : 'false' ?>
    };
</script>
<script src="<?= BASE_URL ?>/public/js/menu/customer.js" defer></script>
