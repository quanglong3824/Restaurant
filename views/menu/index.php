<div class="page-content pos-layout">
    <!-- Overlay cho mobile khi mở giỏ -->
    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart(false)"></div>

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
            <div id="setsContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem;">
                <?php foreach ($sets as $set): ?>
                    <div class="set-card" onclick="openSetModal(<?= htmlspecialchars(json_encode($set)) ?>)">
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
                        <div class="list-item-card">
                            <div style="display:flex; flex:1; align-items:center; cursor:pointer;" data-id="<?= $item['id'] ?>"
                                data-name="<?= e($item['name']) ?>" data-price="<?= $item['price'] ?>" data-img="<?= $item['image'] ? BASE_URL . '/public/uploads/' . e($item['image']) : '' ?>"
                                data-desc="<?= e($item['description'] ?? '') ?>" data-order="<?= $orderId ?: '' ?>"
                                data-gallery="<?= $item['gallery'] ?? '' ?>" onclick="handleOpenItemModal(this)">
                                <?php if ($item['image']): ?>
                                    <img src="<?= BASE_URL . '/public/uploads/' . e($item['image']) ?>" class="list-item-img" alt="<?= e($item['name']) ?>" loading="lazy">
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
    </div>

    <!-- CỘT PHẢI: GIỎ HÀNG NỔI -->
    <?php if ($orderId > 0): ?>
        <button class="cart-fab" id="cartFab" onclick="toggleCart(true)">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div class="cart-fab-badge" id="fabCount"><?= count($orderItems) ?></div>
                <span style="font-weight:700; letter-spacing:0.5px;">XEM GIỎ HÀNG</span>
            </div>
            <span style="font-weight:800;" id="fabTotal"><?= formatPrice($orderTotal) ?></span>
        </button>

        <div class="pos-cart-col" id="cartCol">
            <div class="cart-panel">
                <div class="cart-header">
                    <div>
                        <h3 style="color:var(--gold-dark); margin-bottom: 0.1rem; display:flex; align-items:center; gap:0.5rem;">
                            <i class="fas fa-shopping-basket"></i> Giỏ đang gọi
                        </h3>
                        <span style="font-size: 0.8rem; color:var(--text); opacity:0.8;">Bàn #<?= e($tableId) ?> - <?= e($order['guest_count'] ?? 1) ?> khách</span>
                    </div>
                    <button class="cart-close-mobile" onclick="toggleCart(false)">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>

                <div class="cart-body">
                    <?php $draftCount = 0; ?>
                    <?php if (empty($orderItems)): ?>
                        <p style="text-align:center; color:var(--text-muted); font-size:0.9rem; margin-top:2rem;">Chưa có món nào được chọn.</p>
                    <?php else: ?>
                        <?php foreach ($orderItems as $it): ?>
                            <?php if ($it['status'] === 'draft') $draftCount++; ?>
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem; padding-bottom:1rem; border-bottom:1px solid var(--surface-2);">
                                <div style="flex:1;">
                                    <div style="font-weight:600; font-size:0.95rem; color:var(--text);"><?= e($it['item_name']) ?></div>
                                    <div style="font-size:0.8rem; color:var(--text-dim); margin-top:2px;">
                                        <?= formatPrice($it['item_price']) ?> x <?= $it['quantity'] ?>
                                        <div style="display:inline-flex; gap:0.5rem; margin-left:0.5rem;">
                                            <button onclick="changeCartQty(<?= $it['id'] ?>, -1)" style="border:none; background:var(--surface-2); width:24px; height:24px; border-radius:50%; cursor:pointer;"><i class="fas fa-minus" style="font-size:0.7rem;"></i></button>
                                            <button onclick="changeCartQty(<?= $it['id'] ?>, 1)" style="border:none; background:var(--surface-2); width:24px; height:24px; border-radius:50%; cursor:pointer;"><i class="fas fa-plus" style="font-size:0.7rem;"></i></button>
                                        </div>
                                    </div>
                                    <?php if ($it['notes']): ?>
                                        <div style="font-size:0.8rem; color:var(--warning); font-style:italic; margin-top:4px;"><i class="fas fa-pen"></i> <?= e($it['notes']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div style="font-weight:800; font-size:0.95rem; text-align:right;">
                                    <?= formatPrice($it['item_price'] * $it['quantity']) ?>
                                    <br><span style="font-size:0.75rem; color:<?= $it['status'] === 'confirmed' ? 'var(--success)' : 'var(--text-muted)' ?>; font-weight:normal;">
                                        <i class="fas <?= $it['status'] === 'confirmed' ? 'fa-check-circle' : 'fa-clock' ?>"></i> <?= $it['status'] === 'confirmed' ? 'Bếp đang làm' : 'Món nháp' ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="cart-footer">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.25rem;">
                        <span style="font-weight:600; color:var(--text);">Tổng thanh toán</span>
                        <span style="font-weight:800; font-size:1.4rem; color:var(--danger);" id="orderTotal"><?= formatPrice($orderTotal) ?></span>
                    </div>
                    <div style="display:flex; gap:0.5rem;">
                        <?php if ($draftCount > 0): ?>
                            <button type="button" onclick="confirmOrderAjax(<?= $orderId ?>)"
                                class="btn btn-gold btn-block btn-lg" style="flex: 1; justify-content:center; background:var(--danger); border-color:var(--danger);">
                                <i class="fas fa-concierge-bell"></i> XÁC NHẬN GỬI BẾP (<?= $draftCount ?>)
                            </button>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/orders?table_id=<?= $tableId ?>&order_id=<?= $orderId ?>"
                                class="btn btn-gold btn-block btn-lg" style="flex: 1; justify-content:center;">
                                <i class="fas fa-file-invoice-dollar"></i> XEM CHI TIẾT BILL
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- ========================================== -->
<!-- MODALS & SCRIPTS -->
<!-- ========================================== -->

<!-- Item Detail Modal -->
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
            <h2 id="modalItemName" style="font-size: 1.6rem; font-weight:800; margin-bottom: 0.25rem; color:var(--text);">Tên món</h2>
            <div id="modalItemPrice" style="color: var(--gold-dark); font-weight: 800; font-size: 1.35rem; margin-bottom: 1rem;">Giá</div>

            <div id="descBlock" style="background:var(--surface-2); padding:1rem; border-radius:var(--radius-md); margin-bottom:1.5rem;">
                <h4 style="font-size:0.85rem; text-transform:uppercase; color:var(--text-dim); letter-spacing:1px; margin-bottom:0.5rem;">
                    <i class="fas fa-leaf"></i> Thành phần & Hương vị
                </h4>
                <p id="modalItemDesc" style="color: var(--text); font-size: 0.95rem; line-height: 1.5; margin:0;"></p>
            </div>

            <div id="orderControlsSection" style="display:none; border-top:1px dashed var(--border); padding-top:1.5rem;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color:var(--text);"><i class="fas fa-pen"></i> Ghi chú riêng</label>
                    <input type="text" id="modalItemNote" class="form-control" placeholder="VD: Không hành, cay ít..." style="background:var(--bg); padding:12px;">
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 1.5rem; gap: 1rem;">
                    <div style="display: flex; align-items: center; background: var(--bg); border: 1px solid var(--border); border-radius: 50px; padding: 4px;">
                        <button type="button" onclick="changeModalQty(-1)" style="width: 44px; height: 44px; border:none; background:transparent; font-size:1.1rem; cursor:pointer;"><i class="fas fa-minus"></i></button>
                        <span id="modalItemQty" style="width: 40px; text-align: center; font-weight: 800; font-size: 1.15rem;">1</span>
                        <button type="button" onclick="changeModalQty(1)" style="width: 44px; height: 44px; border:none; background:transparent; font-size:1.1rem; cursor:pointer;"><i class="fas fa-plus"></i></button>
                    </div>

                    <button type="button" id="modalBtnAdd" class="btn btn-gold btn-lg" style="flex: 1; justify-content: center; height: 54px;" onclick="confirmAddToOrder()">
                        <i class="fas fa-cart-plus"></i> THÊM <span id="modalBtnTotal" style="margin-left: 6px;"></span>
                    </button>
                </div>
            </div>

            <div id="viewOnlySection" style="display:none; border-top:1px dashed var(--border); padding-top:1.5rem;">
                <p style="font-weight:600; color:var(--text); margin-bottom: 0.75rem;"><i class="fas fa-bolt"></i> Mở Bàn Mới Để Đặt Món:</p>
                <form action="<?= BASE_URL ?>/tables/open" method="POST">
                    <input type="hidden" name="direct_menu_item_id" id="directMenuItemId">
                    <div class="form-group">
                        <select name="table_id" class="form-control" required>
                            <option value="">-- CHỌN BÀN TRỐNG --</option>
                            <?php if (isset($allTables)): ?>
                                <?php foreach ($allTables as $t): ?>
                                    <?php if ($t['status'] === 'available'): ?>
                                        <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (<?= e($t['area']) ?>)</option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-gold btn-block btn-lg" style="margin-top: 1rem;"><i class="fas fa-door-open"></i> Vào Bàn & Lưu Order</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Set Detail Modal -->
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
                <h4 style="font-size: 0.85rem; text-transform: uppercase; color: var(--text-dim); margin-bottom: 0.75rem;">Món trong set</h4>
                <div id="setModalItems" class="set-items-list" style="border: none; padding: 0; margin: 0;"></div>
            </div>
            <button id="setModalAddBtn" class="btn btn-gold btn-block btn-lg" style="margin-top: 1.5rem;">THÊM SET VÀO ORDER</button>
        </div>
    </div>
</div>

<div id="addToast" class="add-toast"></div>

<script>
    let currentItem = null;

    function toggleCart(show) {
        const cartCol = document.getElementById('cartCol');
        const cartOverlay = document.getElementById('cartOverlay');
        if (!cartCol) return;
        
        if (show) {
            cartCol.classList.add('is-visible');
            cartOverlay?.classList.add('is-visible');
            document.body.style.overflow = 'hidden';
        } else {
            cartCol.classList.remove('is-visible');
            cartOverlay?.classList.remove('is-visible');
            document.body.style.overflow = '';
        }
    }

    function formatMoney(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
    }

    function updateCartUI(data) {
        if (!data.ok) return;
        
        const cartBody = document.querySelector('.cart-body');
        const cartTotal = document.getElementById('orderTotal');
        const fabTotal = document.getElementById('fabTotal');
        const fabCount = document.getElementById('fabCount');
        const cartFooter = document.querySelector('.cart-footer');
        
        if (cartTotal) cartTotal.textContent = data.total_fmt;
        if (fabTotal) fabTotal.textContent = data.total_fmt;
        if (fabCount) fabCount.textContent = data.items.length;
        
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
                                        <button onclick="changeCartQty(${it.id}, -1)" style="border:none; background:var(--surface-2); width:24px; height:24px; border-radius:50%; cursor:pointer;"><i class="fas fa-minus" style="font-size:0.7rem;"></i></button>
                                        <button onclick="changeCartQty(${it.id}, 1)" style="border:none; background:var(--surface-2); width:24px; height:24px; border-radius:50%; cursor:pointer;"><i class="fas fa-plus" style="font-size:0.7rem;"></i></button>
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
        if (!confirm('Gửi các món nháp này xuống bếp?')) return;
        const data = new FormData();
        data.append('order_id', orderId);
        data.append('table_id', <?= $tableId ?: 0 ?>);
        
        fetch('<?= BASE_URL ?>/orders/confirm', { method: 'POST', body: data })
        .then(res => res.json())
        .then(res => {
            if (res.ok) {
                showToast('Đã gửi bếp thành công!');
                location.reload();
            } else {
                alert(res.message);
            }
        });
    }

    function handleOpenItemModal(el) {
        const id = el.dataset.id;
        const name = el.dataset.name;
        const price = parseFloat(el.dataset.price) || 0;
        const img = el.dataset.img;
        const desc = el.dataset.desc;
        const orderId = el.dataset.order || null;
        const gallery = el.dataset.gallery;

        currentItem = { id, name, price, orderId, qty: 1 };
        document.getElementById('modalItemName').textContent = name;
        document.getElementById('modalItemPrice').textContent = formatMoney(price);
        document.getElementById('directMenuItemId').value = id;
        document.getElementById('modalItemDesc').textContent = desc || '';
        document.getElementById('descBlock').style.display = desc ? 'block' : 'none';

        const imgEl = document.getElementById('modalItemImg');
        const placeholder = document.getElementById('modalItemImgPlaceholder');
        imgEl.querySelectorAll('img').forEach(i => i.remove());
        
        if (img) {
            placeholder.style.display = 'none';
            const i = document.createElement('img');
            i.src = img;
            i.className = 'detail-modal-slide-img';
            imgEl.appendChild(i);
        } else {
            placeholder.style.display = 'flex';
        }

        const orderSection = document.getElementById('orderControlsSection');
        const viewSection = document.getElementById('viewOnlySection');
        if (orderId) {
            orderSection.style.display = 'block';
            viewSection.style.display = 'none';
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
        const data = new FormData();
        data.append('order_id', currentItem.orderId);
        data.append('menu_item_id', currentItem.id);
        data.append('qty', currentItem.qty);
        data.append('note', document.getElementById('modalItemNote').value);

        fetch('<?= BASE_URL ?>/orders/add', { method: 'POST', body: data })
        .then(res => res.json())
        .then(res => {
            if (res.ok) {
                Aurora.closeModal('modalItemDetail');
                showToast('Đã thêm món!');
                updateCartUI(res);
            }
        });
    }

    function quickAdd(event, itemId, orderId) {
        event.stopPropagation();
        if (!orderId) { alert('Vui lòng chọn bàn!'); return; }
        const data = new FormData();
        data.append('order_id', orderId);
        data.append('menu_item_id', itemId);
        data.append('qty', 1);
        fetch('<?= BASE_URL ?>/orders/add', { method: 'POST', body: data })
        .then(res => res.json())
        .then(res => {
            if (res.ok) { showToast('Đã thêm món!'); updateCartUI(res); }
        });
    }

    function showToast(msg) {
        const t = document.getElementById('addToast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2000);
    }

    // Filters
    document.querySelectorAll('.filter-pill').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('is-active'));
            pill.classList.add('is-active');
            const f = pill.dataset.filter;
            document.querySelectorAll('.menu-section').forEach(s => {
                s.style.display = (f === 'all' || s.dataset.section === f) ? 'block' : 'none';
            });
        });
    });

    function openSetModal(set) {
        document.getElementById('setModalName').textContent = set.name;
        document.getElementById('setModalPrice').textContent = formatMoney(set.price);
        const list = document.getElementById('setModalItems');
        list.innerHTML = set.items.map(i => `<span class="set-item-tag ${i.is_required ? 'required' : ''}">${i.name} x${i.quantity}</span>`).join('');
        document.getElementById('setModalAddBtn').onclick = () => {
            const data = new FormData();
            data.append('order_id', <?= $orderId ?: 0 ?>);
            data.append('set_id', set.id);
            fetch('<?= BASE_URL ?>/orders/add-set', { method: 'POST', body: data })
            .then(res => res.json())
            .then(res => { if (res.ok) location.reload(); });
        };
        Aurora.openModal('setDetailModal');
    }
</script>