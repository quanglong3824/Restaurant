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
            <?php 
                $hasItems = false;
                if (isset($orderItems) && count($orderItems) > 0) {
                    foreach ($orderItems as $oi) {
                        if ($oi['status'] !== 'cancelled') {
                            $hasItems = true;
                            break;
                        }
                    }
                }
            ?>
            <button class="action-btn <?= $hasItems ? 'glow-payment' : '' ?>" onclick="<?= $hasItems ? 'showBillTam()' : "callWaiter('payment')" ?>">
                <i class="fas fa-file-invoice-dollar"></i>
                <span><?= $hasItems ? 'Hóa đơn' : 'Thanh toán' ?></span>
            </button>
            <button class="action-btn" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
                <span>Làm mới</span>
            </button>
        </div>
    </header>

    <style>
        .menu-type-filter {
            display: flex;
            gap: 8px;
            padding: 10px 15px;
            overflow-x: auto;
            background: #fff;
            border-bottom: 1px solid #eee;
            scrollbar-width: none;
        }
        .menu-type-filter::-webkit-scrollbar { display: none; }
        .type-btn {
            white-space: nowrap;
            padding: 6px 16px;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            transition: all 0.2s;
        }
        .type-btn.active {
            background: var(--gold);
            color: #fff;
            border-color: var(--gold);
        }

        .glow-payment {
            background: var(--gold) !important;
            color: white !important;
            box-shadow: 0 0 15px var(--gold);
            animation: pulse-gold 2s infinite;
        }
        @keyframes pulse-gold {
            0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(212, 175, 55, 0); }
            100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
        }
    </style>

    <!-- Category Navigation (Sticky) -->
    <nav class="category-nav">
        <div class="category-nav-inner">
            <a href="javascript:void(0)" class="cat-pill active" data-category="all">Tất cả</a>
            <?php foreach ($categories as $cat): ?>
                <a href="#cat-<?= $cat['id'] ?>" class="cat-pill" data-category="<?= $cat['id'] ?>" data-type="<?= $cat['menu_type'] ?>">
                    <?= e($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- Menu Type Filter (Quick Access) -->
    <div class="menu-type-filter">
        <button class="type-btn active" data-type="all">TẤT CẢ</button>
        <button class="type-btn" data-type="asia">MÓN Á</button>
        <button class="type-btn" data-type="europe">MÓN ÂU</button>
        <button class="type-btn" data-type="set">SET MENU</button>
        <button class="type-btn" data-type="alacarte">ALACARTE</button>
    </div>

    <!-- Search Bar -->
    <div class="menu-search-container">
        <div class="menu-search-bar">
            <i class="fas fa-search"></i>
            <input type="text" id="menuSearch" placeholder="Tìm món ăn (Vietnamese / English)...">
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
                <section class="menu-section" id="cat-<?= $cat['id'] ?>" data-type="<?= $cat['menu_type'] ?>">
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
                                 data-name-en="<?= e($item['name_en'] ?? '') ?>"
                                 data-price="<?= $item['price'] ?>"
                                 data-type="<?= $cat['menu_type'] ?>"
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
...
</div>

<!-- Bill Tam Modal -->
<div id="billTamModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom modal-premium">
        <div class="modal-header">
            <h3><i class="fas fa-file-invoice-dollar me-2"></i> Hóa đơn tạm tính</h3>
            <button class="modal-close" onclick="closeBillTam()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="billItemsList" class="bill-items-container">
                <?php if ($hasItems): ?>
                    <?php 
                        $total = 0; 
                        foreach ($orderItems as $oi): 
                            if ($oi['status'] === 'cancelled') continue;
                            $total += $oi['item_price'] * $oi['quantity'];
                    ?>
                        <div class="bill-item">
                            <div class="bill-item-main">
                                <span class="bill-qty"><?= $oi['quantity'] ?>x</span>
                                <span class="bill-name"><?= e($oi['item_name']) ?></span>
                                <span class="bill-price"><?= formatPrice($oi['item_price'] * $oi['quantity']) ?></span>
                            </div>
                            <div class="bill-item-status <?= $oi['status'] ?>">
                                <?= $oi['status'] === 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận' ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-muted py-4">Bàn chưa có món nào được gọi.</p>
                <?php endif; ?>
            </div>
            
            <?php if ($hasItems): ?>
            <div class="bill-summary mt-3">
                <div class="d-flex justify-content-between">
                    <span>Tổng tiền món:</span>
                    <strong><?= formatPrice($total) ?></strong>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <button class="btn-gold w-100 mb-2" onclick="callWaiter('payment')">
                <i class="fas fa-hand-holding-usd me-2"></i> YÊU CẦU THANH TOÁN
            </button>
            <button class="btn-ghost w-100" onclick="closeBillTam()">TIẾP TỤC ĐẶT MÓN</button>
        </div>
    </div>
</div>

<style>
    .bill-items-container { max-height: 40vh; overflow-y: auto; }
    .bill-item { padding: 12px 0; border-bottom: 1px dashed var(--border); }
    .bill-item-main { display: flex; align-items: center; gap: 10px; font-weight: 600; }
    .bill-qty { color: var(--gold-dark); min-width: 30px; }
    .bill-name { flex: 1; }
    .bill-price { color: var(--text-dark); }
    .bill-item-status { font-size: 0.7rem; margin-top: 4px; padding-left: 40px; }
    .bill-item-status.confirmed { color: #10b981; }
    .bill-item-status.pending { color: #f59e0b; }
    .bill-summary { background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid var(--border); }
</style>

<!-- Scripts Configuration -->
<script>
    const CUSTOMER_CONFIG = {
        tableId: <?= $table['id'] ?>,
        tableName: '<?= e($table['name']) ?>',
        baseUrl: '<?= BASE_URL ?>',
        isIT: <?= Auth::isIT() ? 'true' : 'false' ?>,
        hasItems: <?= $hasItems ? 'true' : 'false' ?>
    };
    
    function showBillTam() {
        document.getElementById('billTamModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeBillTam() {
        document.getElementById('billTamModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
<script src="<?= BASE_URL ?>/public/js/menu/customer.js" defer></script>
