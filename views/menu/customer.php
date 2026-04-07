<?php // views/menu/customer.php — Customer Digital Menu
// Xác định ngữ cảnh phục vụ dựa trên type của bàn/phòng
$isRoomService = isset($table['type']) && $table['type'] === 'room';
$contextLabel  = $isRoomService ? 'ROOM SERVICE' : 'RESTAURANT';
$contextIcon   = $isRoomService ? 'fa-bed' : 'fa-utensils';
$contextColor  = $isRoomService ? '#8b5cf6' : 'var(--gold)';

// Tính $hasItems
$hasItems = false;
if (isset($orderItems) && count($orderItems) > 0) {
    foreach ($orderItems as $oi) {
        if ($oi['status'] !== 'cancelled') { $hasItems = true; break; }
    }
}

// Nhóm menuItems theo category_id (đã lọc đúng service_type từ controller)
$grouped = [];
foreach ($menuItems as $mi) {
    $grouped[$mi['category_id']][] = $mi;
}
// Chỉ lấy categories có món
$activeCategories = array_filter($categories, fn($c) => isset($grouped[$c['id']]));

// Tổng tiền order hiện tại
$orderTotal = 0;
if ($hasItems) {
    foreach ($orderItems as $oi) {
        if ($oi['status'] !== 'cancelled') $orderTotal += $oi['item_price'] * $oi['quantity'];
    }
}
?>

<!-- ══════════════════════════════════════════════════════
     OVERLAYS: Location Check & Out-of-range
═══════════════════════════════════════════════════════ -->
<div id="frozenOverlay" class="loc-overlay" style="display:none;">
    <div class="loc-card" style="--card-accent:#ef4444;">
        <div class="loc-icon-ring" style="color:#ef4444;">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <h3 style="color:#ef4444;">BẠN ĐÃ RỜI KHỎI KHU VỰC</h3>
        <p class="loc-sub">Thực đơn tạm thời bị khoá để bảo mật đơn hàng</p>
        <div class="loc-dist-badge err">
            <i class="fas fa-walking"></i> Khoảng cách: <span id="frozenDistVal">...</span>m
        </div>
        <p class="loc-hint">Vui lòng quay lại khu vực để tiếp tục</p>
    </div>
</div>

<div id="locationOverlay" class="loc-overlay">
    <script>
        if (localStorage.getItem(`locationVerified_table_${CUSTOMER_CONFIG?.tableId}`) === 'true') {
            document.getElementById('locationOverlay').style.display = 'none';
        }
    </script>
    <div class="loc-card">
        <div class="loc-icon-ring"><i class="fas fa-shield-alt"></i></div>
        <h3>XÁC NHẬN HIỆN DIỆN</h3>
        <p class="loc-sub">AURORA HOTEL PLAZA</p>
        <div id="liveDistance" class="loc-dist-badge" style="display:none;">
            <i class="fas fa-map-marker-alt"></i> <span id="distVal">...</span>m
        </div>
        <p style="font-size:.875rem;color:#cbd5e1;line-height:1.6;">
            Để bảo mật đơn hàng và tốc độ phục vụ tối ưu, vui lòng xác nhận vị trí của bạn.
        </p>
        <ul class="loc-benefits">
            <li><i class="fas fa-check-circle"></i> Đơn hàng xác nhận ngay lập tức</li>
            <li><i class="fas fa-lock"></i> Không lưu lịch sử vị trí</li>
            <li><i class="fas fa-history"></i> Tự động xoá khi rời đi</li>
        </ul>
        <div id="locationError" class="loc-error" style="display:none;"></div>
        <button id="btnAllowLocation" class="btn-loc-start">
            <i class="fas fa-location-arrow"></i> BẮT ĐẦU TRẢI NGHIỆM
        </button>
        <p class="loc-privacy">Bằng cách tiếp tục, bạn đồng ý với chính sách bảo mật của chúng tôi.</p>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════
     CSS
═══════════════════════════════════════════════════════ -->
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/menu/customer.css">
<style>
/* ── Location Overlay ── */
.loc-overlay {
    position:fixed;inset:0;display:flex;align-items:center;justify-content:center;
    padding:20px;z-index:10000;
    background:linear-gradient(135deg,rgba(15,23,42,.96),rgba(30,41,59,.99));
    backdrop-filter:blur(18px);color:#fff;
}
.loc-card {
    background:#1e293b;padding:36px 28px;border-radius:28px;
    border:1px solid rgba(212,175,55,.3);max-width:400px;width:100%;
    box-shadow:0 30px 60px rgba(0,0,0,.5);text-align:center;
}
.loc-icon-ring {
    width:68px;height:68px;border-radius:50%;
    background:rgba(212,175,55,.1);border:1.5px solid var(--gold,#d4af37);
    color:var(--gold,#d4af37);font-size:1.8rem;
    display:flex;align-items:center;justify-content:center;margin:0 auto 16px;
}
.loc-card h3 {
    font-family:'Playfair Display',serif;font-size:1.1rem;letter-spacing:2px;
    margin:0 0 4px;color:#fff;
}
.loc-sub { font-size:.7rem;letter-spacing:2px;color:var(--gold,#d4af37);margin:0 0 18px; }
.loc-dist-badge {
    display:inline-flex;align-items:center;gap:8px;
    background:rgba(212,175,55,.15);color:var(--gold,#d4af37);
    padding:8px 18px;border-radius:50px;font-size:.85rem;font-weight:700;
    border:1px solid rgba(212,175,55,.3);margin-bottom:16px;
    animation:pulseSubtle 2s infinite;
}
.loc-dist-badge.err { background:rgba(239,68,68,.1);color:#f87171;border-color:rgba(239,68,68,.3); }
@keyframes pulseSubtle { 0%,100%{opacity:.8;transform:scale(1)} 50%{opacity:1;transform:scale(1.04)} }
.loc-benefits {
    list-style:none;padding:0;margin:16px 0 20px;text-align:left;
}
.loc-benefits li {
    font-size:.83rem;color:#cbd5e1;margin-bottom:10px;
    display:flex;align-items:center;gap:10px;
}
.loc-benefits i { color:var(--gold,#d4af37);font-size:.9rem; }
.loc-error {
    background:rgba(239,68,68,.1);color:#f87171;padding:10px 14px;
    border-radius:10px;border:1px solid rgba(239,68,68,.2);
    font-size:.82rem;margin-bottom:16px;text-align:left;
}
.loc-hint { font-size:.8rem;color:#94a3b8;margin-top:10px; }
.btn-loc-start {
    width:100%;background:linear-gradient(135deg,#d4af37,#b8860b);
    color:#fff;border:none;padding:15px;border-radius:14px;
    font-weight:800;font-size:.95rem;letter-spacing:1px;
    cursor:pointer;transition:all .3s;display:flex;align-items:center;
    justify-content:center;gap:10px;margin-bottom:12px;
}
.btn-loc-start:hover { transform:translateY(-2px);box-shadow:0 10px 24px rgba(212,175,55,.35); }
.loc-privacy { font-size:.68rem;color:#475569;margin:0; }

/* ── Context banner (room vs restaurant) ── */
.ctx-banner {
    display:flex;align-items:center;gap:10px;
    padding:10px 18px;border-radius:12px;margin:12px 16px 0;
    font-size:.8rem;font-weight:700;letter-spacing:.5px;border:1.5px solid;
}
.ctx-banner i { font-size:1rem; }

/* ── Menu type tab bar ── */
.type-tab-bar {
    display:flex;gap:6px;padding:10px 16px;overflow-x:auto;
    background:#fff;scrollbar-width:none;border-bottom:1px solid #f1f5f9;
}
.type-tab-bar::-webkit-scrollbar { display:none; }
.type-tab {
    white-space:nowrap;padding:7px 18px;border-radius:50px;
    border:1.5px solid #e2e8f0;background:#f8fafc;
    font-size:.75rem;font-weight:700;color:#64748b;
    cursor:pointer;transition:all .2s;flex-shrink:0;
}
.type-tab.active {
    background:var(--gold,#c5a059);color:#fff;
    border-color:var(--gold,#c5a059);
    box-shadow:0 3px 10px rgba(197,160,89,.3);
}

/* ── Item tags ── */
.item-tags { display:flex;flex-wrap:wrap;gap:4px;margin-top:5px; }
.item-tag {
    font-size:.6rem;font-weight:800;padding:2px 7px;border-radius:5px;color:#fff;
}
.item-tag.bestseller { background:#ef4444; }
.item-tag.new        { background:#8b5cf6; }
.item-tag.spicy      { background:#f97316; }
.item-tag.vegetarian { background:#16a34a; }
.item-tag.recommended{ background:#0ea5e9; }

/* ── Unavailable overlay ── */
.item-unavailable {
    opacity:.5;pointer-events:none;position:relative;
}
.item-unavailable::after {
    content:'Hết hàng';position:absolute;inset:0;display:flex;
    align-items:center;justify-content:center;
    background:rgba(255,255,255,.7);border-radius:inherit;
    font-weight:800;color:#94a3b8;font-size:.8rem;
}

/* ── Empty state ── */
.menu-empty-state {
    text-align:center;padding:3rem 1.5rem;color:#94a3b8;
}
.menu-empty-state i { font-size:3rem;opacity:.3;display:block;margin-bottom:1rem; }

/* ── Bill items (current order) ── */
.bill-items-container { max-height:40vh;overflow-y:auto; }
.bill-item { padding:12px 0;border-bottom:1px dashed #e2e8f0; }
.bill-item-main { display:flex;align-items:center;gap:10px;font-weight:600; }
.bill-qty { color:var(--gold-dark,#a68341);min-width:28px;font-size:.9rem; }
.bill-name { flex:1;font-size:.9rem; }
.bill-price { color:#0f172a;font-weight:700; }
.bill-item-status { font-size:.7rem;margin-top:3px;padding-left:38px;font-weight:600; }
.bill-item-status.confirmed { color:#10b981; }
.bill-item-status.pending   { color:#f59e0b; }
.bill-item-status.draft     { color:#94a3b8; }
.bill-summary {
    background:#f8fafc;padding:14px;border-radius:12px;
    border:1px solid #e2e8f0;margin-top:12px;
}
.bill-total-row { display:flex;justify-content:space-between;align-items:center; }
.bill-total-row span { color:#64748b;font-weight:600; }
.bill-total-row strong { font-size:1.3rem;color:var(--gold-dark,#a68341);font-weight:800; }

/* ── Glow payment button ── */
.glow-payment {
    background:var(--gold,#c5a059)!important;color:#fff!important;
    box-shadow:0 0 15px rgba(197,160,89,.7);
    animation:pulseGold 2s infinite;
}
@keyframes pulseGold {
    0%   { box-shadow:0 0 0 0 rgba(197,160,89,.7); }
    70%  { box-shadow:0 0 0 14px rgba(197,160,89,0); }
    100% { box-shadow:0 0 0 0 rgba(197,160,89,0); }
}

/* ── Misc utils ── */
.w-100{width:100%} .mb-2{margin-bottom:.5rem} .me-2{margin-right:.5rem}
.btn-gold {
    background:linear-gradient(135deg,var(--gold,#c5a059),var(--gold-dark,#a68341));
    color:#fff;border:none;padding:14px 20px;border-radius:12px;
    font-weight:700;letter-spacing:.5px;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 4px 15px rgba(197,160,89,.3);transition:all .3s;
    font-family:inherit;
}
.btn-gold:active { transform:scale(.98); }
.btn-ghost {
    background:#fff;color:#64748b;border:1px solid #e2e8f0;
    padding:14px 20px;border-radius:12px;font-weight:600;
    cursor:pointer;display:flex;align-items:center;justify-content:center;
    font-family:inherit;transition:all .2s;
}
.btn-ghost:active { background:#f8fafc; }
</style>

<!-- ══════════════════════════════════════════════════════
     MAIN MENU WRAPPER
═══════════════════════════════════════════════════════ -->
<div class="customer-menu-wrapper" id="menuWrapper" style="display:none;">
    <script>
        if (localStorage.getItem(`locationVerified_table_${CUSTOMER_CONFIG?.tableId}`) === 'true') {
            document.getElementById('menuWrapper').style.display = 'block';
        }
    </script>

    <!-- Header -->
    <header class="menu-header">
        <div class="header-top">
            <div class="brand-logo">
                <h1 class="playfair">AURORA</h1>
                <span><?= $contextLabel ?></span>
            </div>
            <div class="table-info">
                <span class="table-label"><?= $isRoomService ? 'PHÒNG' : 'BÀN' ?></span>
                <span class="table-number"><?= e($table['name']) ?></span>
            </div>
        </div>

        <!-- Context banner -->
        <div class="ctx-banner" style="color:<?= $contextColor ?>;background:<?= $contextColor ?>15;border-color:<?= $contextColor ?>44;">
            <i class="fas <?= $contextIcon ?>"></i>
            <?php if ($isRoomService): ?>
                Thực đơn phục vụ tại phòng — Đặt món sẽ được mang đến tận nơi
            <?php else: ?>
                Thực đơn nhà hàng — Đặt món ngay tại bàn và chờ phục vụ
            <?php endif; ?>
        </div>

        <!-- Action bar -->
        <div class="action-bar">
            <button class="action-btn" onclick="callWaiter('support')">
                <i class="fas fa-<?= $isRoomService ? 'concierge-bell' : 'hand-paper' ?>"></i>
                <span><?= $isRoomService ? 'Gọi lễ tân' : 'Gọi phục vụ' ?></span>
            </button>
            <button class="action-btn <?= $hasItems ? 'glow-payment' : '' ?>"
                    onclick="<?= $hasItems ? 'showBillTam()' : "callWaiter('payment')" ?>">
                <i class="fas fa-file-invoice-dollar"></i>
                <span><?= $hasItems ? 'Hoá đơn' : 'Thanh toán' ?></span>
            </button>
            <button class="action-btn" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
                <span>Làm mới</span>
            </button>
        </div>
    </header>

    <!-- Type tab bar (chỉ sinh ra các menu_type thực sự có trong danh mục của bàn này) -->
    <?php
    $presentTypes = array_unique(array_column($activeCategories, 'menu_type'));
    $typeLabels = ['asia'=>'Món Á', 'europe'=>'Món Âu', 'alacarte'=>'Alacarte', 'other'=>'Đ.Uống & Khác'];
    // Chỉ hiển thị tab bar nếu có từ 2 type trở lên
    ?>
    <?php if (count($presentTypes) > 1): ?>
    <div class="type-tab-bar" id="typeTabBar">
        <button class="type-tab active" data-type="all">TẤT CẢ</button>
        <?php foreach ($presentTypes as $tp): if (!isset($typeLabels[$tp])) continue; ?>
            <button class="type-tab" data-type="<?= $tp ?>"><?= strtoupper($typeLabels[$tp]) ?></button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Category nav sticky -->
    <nav class="category-nav">
        <div class="category-nav-inner">
            <a href="javascript:void(0)" class="cat-pill active" data-category="all">Tất cả</a>
            <?php foreach ($activeCategories as $cat): ?>
                <a href="#cat-<?= $cat['id'] ?>" class="cat-pill"
                   data-category="<?= $cat['id'] ?>" data-type="<?= $cat['menu_type'] ?>">
                    <?= e($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- Search -->
    <div class="menu-search-container">
        <div class="menu-search-bar">
            <i class="fas fa-search"></i>
            <input type="text" id="menuSearch" placeholder="Tìm món (tên Việt / English)...">
            <button id="btnClearSearch" style="display:none;background:none;border:none;color:#94a3b8;cursor:pointer;padding:0 4px;" onclick="clearMenuSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Menu Sections -->
    <main class="menu-sections" id="menuSections">
        <?php foreach ($activeCategories as $cat): ?>
            <?php if (!isset($grouped[$cat['id']])) continue; ?>
            <section class="menu-section" id="cat-<?= $cat['id'] ?>" data-type="<?= $cat['menu_type'] ?>">
                <div class="section-header">
                    <h2 class="section-title"><?= e($cat['name']) ?></h2>
                    <?php if (!empty($cat['name_en'])): ?>
                        <span class="section-title-en"><?= e($cat['name_en']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="menu-list">
                    <?php foreach ($grouped[$cat['id']] as $item):
                        $isUnavailable = !$item['is_available'];
                        $tags = array_filter(array_map('trim', explode(',', $item['tags'] ?? '')));
                    ?>
                        <div class="menu-item-card<?= $isUnavailable ? ' item-unavailable' : '' ?>"
                             data-id="<?= $item['id'] ?>"
                             data-name="<?= strtolower(e($item['name'])) ?>"
                             data-name-en="<?= strtolower(e($item['name_en'] ?? '')) ?>"
                             data-price="<?= $item['price'] ?>"
                             data-type="<?= $cat['menu_type'] ?>"
                             data-options="<?= e($item['note_options'] ?? '') ?>"
                             data-options-en="<?= e($item['note_options_en'] ?? '') ?>"
                             onclick="<?= $isUnavailable ? '' : 'showItemDetail(' . e(json_encode($item)) . ')' ?>">

                            <div class="item-img-box">
                                <?php if ($item['image']): ?>
                                    <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>"
                                         alt="<?= e($item['name']) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="item-placeholder"><i class="fas fa-utensils"></i></div>
                                <?php endif; ?>
                                <?php if (in_array('bestseller', $tags)): ?>
                                    <span class="item-badge bestseller">HOT</span>
                                <?php elseif (in_array('new', $tags)): ?>
                                    <span class="item-badge" style="background:#8b5cf6;">NEW</span>
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
                                <?php if (!empty($item['description'])): ?>
                                    <p class="item-desc"><?= e($item['description']) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($tags)): ?>
                                <div class="item-tags">
                                    <?php foreach ($tags as $tag): if (!in_array($tag, ['bestseller','new','spicy','vegetarian','recommended'])) continue; ?>
                                        <span class="item-tag <?= $tag ?>"><?= ucfirst($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>

                                <div class="item-footer">
                                    <?php if (!$isUnavailable): ?>
                                    <button class="btn-add-circle"
                                            onclick="event.stopPropagation(); quickAdd(<?= $item['id'] ?>, '<?= e($item['name']) ?>', <?= $item['price'] ?>)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <?php else: ?>
                                    <span style="font-size:.72rem;color:#94a3b8;font-weight:700;">Hết hàng</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

        <?php if (empty($activeCategories)): ?>
        <div class="menu-empty-state">
            <i class="fas fa-utensils"></i>
            <p style="font-weight:700;font-size:1rem;">Chưa có thực đơn</p>
            <p style="font-size:.85rem;">Vui lòng liên hệ nhân viên để được hỗ trợ</p>
        </div>
        <?php endif; ?>

        <div id="searchNoResult" class="menu-empty-state" style="display:none;">
            <i class="fas fa-search"></i>
            <p style="font-weight:700;font-size:1rem;">Không tìm thấy món phù hợp</p>
            <button onclick="clearMenuSearch()" style="background:none;border:1.5px solid #e2e8f0;border-radius:20px;padding:8px 20px;cursor:pointer;font-weight:600;color:#64748b;margin-top:8px;">
                Xoá tìm kiếm
            </button>
        </div>
    </main>
</div>

<!-- ── Floating Cart Bar ── -->
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
            XEM GIỎ <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>

<!-- ── Cart Modal ── -->
<div id="cartModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom">
        <div class="modal-header">
            <h3><i class="fas fa-shopping-cart me-2"></i> Chi tiết đơn hàng</h3>
            <button class="modal-close" onclick="toggleCartModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="cartItemsList" class="cart-items-container"></div>
            <div class="order-notes-box mt-3" style="margin-top:1rem;">
                <label style="font-size:.72rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;">GHI CHÚ ĐƠN HÀNG</label>
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

<!-- ── Item Detail Modal ── -->
<div id="itemDetailModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom modal-premium">
        <div class="modal-header border-0" style="border:none;position:relative;">
            <button class="modal-close-circle" onclick="closeItemDetail()"><i class="fas fa-times"></i></button>
        </div>
        <div class="item-detail-img" id="detailImg" style="width:100%;height:240px;background-size:cover;background-position:center;position:relative;"></div>
        <div class="modal-body">
            <div style="margin-bottom:1rem;">
                <h2 id="detailName" class="playfair" style="margin:0 0 4px;font-size:1.4rem;font-weight:800;"></h2>
                <div id="detailNameEn" class="item-name-en"></div>
                <div id="detailPrice" class="item-price" style="font-size:1.2rem;font-weight:800;color:var(--gold-dark);"></div>
                <p id="detailDesc" class="item-desc" style="margin-top:8px;font-size:.875rem;color:#64748b;line-height:1.5;"></p>
            </div>
            <div id="detailOptsWrap" style="display:none;margin-bottom:1.25rem;">
                <label style="font-size:.72rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;display:block;">
                    Tuỳ chọn nhanh / Quick Options
                </label>
                <div id="detailOptsContainer" style="display:flex;flex-wrap:wrap;gap:8px;"></div>
            </div>
            <div class="order-controls">
                <div class="qty-control-premium">
                    <button onclick="changeDetailQty(-1)"><i class="fas fa-minus"></i></button>
                    <span id="detailQty">1</span>
                    <button onclick="changeDetailQty(1)"><i class="fas fa-plus"></i></button>
                </div>
                <div class="note-input-box">
                    <i class="fas fa-edit"></i>
                    <input type="text" id="detailNote" placeholder="Ghi chú thêm (No onion, less spicy...)">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-submit-order w-100" id="btnAddOrder" onclick="addFromDetail()">
                <i class="fas fa-cart-plus me-2"></i> THÊM VÀO ĐƠN HÀNG
            </button>
        </div>
    </div>
</div>

<!-- ── Bill Modal ── -->
<div id="billTamModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom modal-premium">
        <div class="modal-header">
            <h3><i class="fas fa-file-invoice-dollar me-2"></i> Hoá đơn tạm tính</h3>
            <button class="modal-close" onclick="closeBillTam()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="bill-items-container">
                <?php if ($hasItems): ?>
                    <?php foreach ($orderItems as $oi):
                        if ($oi['status'] === 'cancelled') continue; ?>
                        <div class="bill-item">
                            <div class="bill-item-main">
                                <span class="bill-qty"><?= $oi['quantity'] ?>x</span>
                                <span class="bill-name"><?= e($oi['item_name']) ?></span>
                                <span class="bill-price"><?= formatPrice($oi['item_price'] * $oi['quantity']) ?></span>
                            </div>
                            <?php if (!empty($oi['note'])): ?>
                                <div style="font-size:.7rem;color:#94a3b8;padding-left:36px;margin-top:2px;">
                                    <i class="fas fa-pen" style="font-size:.6rem;"></i> <?= e($oi['note']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="bill-item-status <?= $oi['status'] ?>">
                                <?php
                                $statusTxt = ['confirmed'=>'✅ Đã xác nhận','pending'=>'⏳ Chờ xác nhận','draft'=>'📝 Chờ gửi bếp'];
                                echo $statusTxt[$oi['status']] ?? $oi['status'];
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="bill-summary">
                        <div class="bill-total-row">
                            <span>Tổng tiền món</span>
                            <strong><?= formatPrice($orderTotal) ?></strong>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="menu-empty-state">
                        <i class="fas fa-receipt"></i>
                        <p>Bàn chưa có món nào được gọi.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="modal-footer" style="display:flex;flex-direction:column;gap:.5rem;">
            <button class="btn-gold w-100" onclick="callWaiter('payment')">
                <i class="fas fa-hand-holding-usd me-2"></i> YÊU CẦU THANH TOÁN
            </button>
            <button class="btn-ghost w-100" onclick="closeBillTam()">TIẾP TỤC ĐẶT MÓN</button>
        </div>
    </div>
</div>

<!-- opt-chip style -->
<style>
.opt-chip-premium {
    padding:6px 14px;background:#f8fafc;color:#475569;border-radius:50px;
    font-size:.8rem;font-weight:600;cursor:pointer;transition:all .2s;
    border:1.5px solid #e2e8f0;display:inline-flex;align-items:center;gap:6px;
}
.opt-chip-premium.active {
    background:rgba(197,160,89,.12);color:var(--gold-dark,#a68341);
    border-color:var(--gold,#c5a059);transform:scale(1.03);
}
.modal-close-circle {
    position:absolute;top:12px;right:12px;width:34px;height:34px;
    border-radius:50%;background:rgba(0,0,0,.45);color:#fff;border:none;
    display:flex;align-items:center;justify-content:center;z-index:10;cursor:pointer;
}
</style>

<!-- Config & inline utilities -->
<script>
const CUSTOMER_CONFIG = {
    tableId:           <?= $table['id'] ?>,
    tableName:         '<?= e($table['name']) ?>',
    isRoomService:     <?= $isRoomService ? 'true' : 'false' ?>,
    baseUrl:           '<?= BASE_URL ?>',
    isIT:              <?= (\Auth::isIT() ?? false) ? 'true' : 'false' ?>,
    hasItems:          <?= $hasItems ? 'true' : 'false' ?>,
    restaurantCoords:  { lat: <?= RESTAURANT_LAT ?>, lng: <?= RESTAURANT_LNG ?> },
    maxDistance:       <?= MAX_ORDER_DISTANCE ?>,
    showBill:          <?= isset($_GET['show_bill']) ? 'true' : 'false' ?>
};

/* ── Type tab filter ── */
document.querySelectorAll('.type-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.type-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const type = btn.dataset.type;
        document.querySelectorAll('.menu-section').forEach(sec => {
            sec.style.display = (type === 'all' || sec.dataset.type === type) ? '' : 'none';
        });
        document.querySelectorAll('.cat-pill[data-type]').forEach(pill => {
            pill.style.display = (type === 'all' || pill.dataset.type === type) ? '' : 'none';
        });
        // scroll to top of menu
        const ms = document.getElementById('menuSections');
        if (ms) ms.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});

/* ── Search with clear button ── */
const _searchEl = document.getElementById('menuSearch');
const _clearBtn = document.getElementById('btnClearSearch');
if (_searchEl) {
    _searchEl.addEventListener('input', _filterMenu);
    function _filterMenu() {
        const q = _searchEl.value.trim().toLowerCase();
        _clearBtn.style.display = q ? '' : 'none';
        let anyVisible = false;
        document.querySelectorAll('.menu-item-card').forEach(card => {
            const match = card.dataset.name.includes(q) || card.dataset.nameEn.includes(q);
            card.style.display = match ? 'flex' : 'none';
            if (match) anyVisible = true;
        });
        document.querySelectorAll('.menu-section').forEach(sec => {
            const hasVisible = [...sec.querySelectorAll('.menu-item-card')].some(c => c.style.display !== 'none');
            sec.style.display = hasVisible ? '' : 'none';
        });
        document.getElementById('searchNoResult').style.display = (!anyVisible && q) ? '' : 'none';
    }
}
function clearMenuSearch() {
    if (_searchEl) { _searchEl.value = ''; _filterMenu(); }
}

/* ── Bill modal ── */
function showBillTam() {
    document.getElementById('billTamModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeBillTam() {
    document.getElementById('billTamModal').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>
<script src="<?= BASE_URL ?>/public/js/menu/customer.js?v=<?= time() ?>" defer></script>
