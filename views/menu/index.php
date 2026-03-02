<?php // views/menu/index.php — Digital Menu (Waiter POS & Showcase) ?>
<style>
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
    }

    .cat-hero-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
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
        <!-- Header & Category Pills -->
        <div class="app-menu-header">
            <div class="cat-pills-wrapper">
                <button class="cat-pill is-active" data-cat="all">
                    <i class="fas fa-th-large"></i> Tất cả
                </button>
                <?php foreach ($categories as $cat): ?>
                    <button class="cat-pill" data-cat="<?= e($cat['name']) ?>">
                        <i class="fas <?= e($cat['icon'] ?? 'fa-utensils') ?>"></i>
                        <?= e($cat['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <?php foreach ($grouped as $catName => $items): ?>
            <div class="menu-section" data-section="<?= e($catName) ?>">
                <h2 class="cat-hero-title">
                    <span style="color:var(--gold)"><i class="fas fa-hashtag"></i></span> <?= e($catName) ?>
                </h2>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
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

        <?php if (empty($grouped)): ?>
            <div class="empty-state" style="margin-top:4rem; text-align:center;">
                <i class="fas fa-box-open" style="font-size:3rem; color:var(--text-dim); margin-bottom:1rem;"></i>
                <h3>Menu chưa có dữ liệu</h3>
                <p style="color:var(--text-muted)">Admin hãy bổ sung các món ăn hấp dẫn vào nhé.</p>
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

<div id="addToast" class="add-toast" aria-live="polite"></div>

<script>
    let currentItem = null;

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

    function confirmAddToOrder() {
        if (!currentItem || !currentItem.orderId) return;
        const note = document.getElementById('modalItemNote').value.trim();
        const btn = document.getElementById('modalBtnAdd');
        btn.style.opacity = '0.7';
        btn.textContent = 'Đang đẩy lên...';
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
                    window.location.reload();
                } else {
                    alert(res.message || 'Có lỗi xảy ra');
                }
            })
            .catch(err => {
                alert('Khong the ket noi den server');
            })
            .finally(() => {
                btn.style.opacity = '1';
                updateModalUI();
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
                    window.location.reload();
                } else {
                    alert(res.message || 'Lỗi thêm món');
                    btn.innerHTML = orgHtml;
                    btn.style.pointerEvents = 'auto';
                }
            })
            .catch(err => {
                alert('Lỗi kết nối');
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
</script>