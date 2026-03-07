<?php // views/menu/index.php — Digital Menu (Waiter POS & Showcase) ?>
<style>
    /* POS Layout & Grid */
    .pos-layout {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        position: relative;
    }

    .pos-menu-col {
        flex: 1;
        min-width: 0;
        padding-bottom: 100px; /* Space for mobile FAB */
    }

    @media (min-width: 1024px) {
        .pos-layout { flex-direction: row; align-items: flex-start; }
        .pos-menu-col { padding-bottom: 0; }
        .pos-cart-col {
            position: sticky;
            top: 1.5rem;
            width: 400px;
            height: calc(100vh - 120px);
            z-index: 10;
            padding-left: 1.5rem;
        }
    }

    /* Menu Type Tabs */
    .menu-type-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        background: var(--surface-2);
        padding: 0.5rem;
        border-radius: var(--radius-lg);
        overflow-x: auto;
    }

    .menu-type-tab {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-md);
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-muted);
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .menu-type-tab.is-active {
        background: var(--gold);
        color: #fff;
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    /* Category Filters */
    .category-filter-bar {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        padding: 0.5rem 0;
        scrollbar-width: none;
    }
    .category-filter-bar::-webkit-scrollbar { display: none; }
    
    .filter-pill {
        padding: 0.5rem 1rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .filter-pill.is-active {
        background: var(--gold-dark);
        color: #fff;
        border-color: var(--gold-dark);
    }

    /* Floating Cart (Mobile) */
    .pos-cart-col {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        z-index: 1000;
        transform: translateY(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .pos-cart-col.is-visible { transform: translateY(0); }

    @media (min-width: 1024px) {
        .pos-cart-col { transform: none !important; position: sticky; }
    }

    .cart-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 24px 24px 0 0;
        box-shadow: 0 -10px 30px rgba(0,0,0,0.15);
        display: flex;
        flex-direction: column;
        height: 85vh;
    }

    @media (min-width: 1024px) {
        .cart-panel { border-radius: 20px; height: 100%; box-shadow: var(--shadow-card); }
    }

    .cart-header {
        padding: 1.25rem;
        background: var(--gold-light);
        border-bottom: 1px solid var(--border-gold);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-body { padding: 1rem; overflow-y: auto; flex: 1; }
    .cart-footer { padding: 1.25rem; border-top: 1px solid var(--border); background: var(--surface); }

    /* FAB Mobile - Circular Style */
    .cart-fab {
        position: fixed;
        bottom: 85px; /* Same position as old chat button */
        right: 20px;
        width: 60px;
        height: 60px;
        background: var(--gold);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
        z-index: 998;
        border: none;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .cart-fab:active { transform: scale(0.9); }

    .cart-fab-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        background: var(--danger);
        color: white;
        min-width: 22px;
        height: 22px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.75rem;
        padding: 0 5px;
        border: 2px solid var(--surface);
    }

    .cart-fab i { font-size: 1.4rem; }
    .cart-fab span:not(.cart-fab-badge) { display: none; } /* Hide text on mobile FAB */

    .cart-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.5);
        z-index: 999; display: none; backdrop-filter: blur(4px);
    }
    .cart-overlay.is-visible { display: block; }

    /* Menu Items Card */
    .menu-items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }

    .list-item-card {
        display: flex; align-items: center; gap: 1rem;
        background: var(--surface); border-radius: 12px;
        padding: 0.75rem; border: 1px solid var(--border);
        transition: all 0.2s;
    }
    .list-item-card:hover { border-color: var(--gold); transform: translateX(4px); }

    .list-item-img {
        width: 70px; height: 70px; border-radius: 8px;
        object-fit: cover; background: var(--surface-2);
    }

    .list-item-body { flex: 1; min-width: 0; }
    .list-item-name { font-size: 1rem; font-weight: 700; margin-bottom: 0.2rem; }
    .list-item-price { font-size: 0.95rem; font-weight: 800; color: var(--gold-dark); }

    .list-item-action {
        width: 38px; height: 38px; border-radius: 50%;
        background: var(--gold-light); color: var(--gold-dark);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 1.1rem;
    }

    /* Toast */
    .add-toast {
        position: fixed; bottom: 100px; left: 50%;
        transform: translateX(-50%) translateY(20px);
        background: #333; color: white;
        padding: 0.75rem 1.5rem; border-radius: 50px;
        font-weight: 600; box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        opacity: 0; transition: all 0.3s; z-index: 2000;
    }
    .add-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
</style>

<div class="page-content pos-layout">
    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart(false)"></div>

    <!-- MAIN MENU -->
    <div class="pos-menu-col">
        <div class="menu-type-tabs">
            <?php foreach ($menuTypes as $type): ?>
                <a href="<?= BASE_URL ?>/menu?type=<?= e($type['key']) ?><?= $tableId ? '&table_id='.$tableId : '' ?><?= $orderId ? '&order_id='.$orderId : '' ?>" 
                    class="menu-type-tab <?= $currentType === $type['key'] ? 'is-active' : '' ?>">
                    <i class="fas <?= e($type['icon']) ?>"></i>
                    <?= e($type['label']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($categories) && $currentType !== 'alacarte'): ?>
            <div class="category-filter-bar">
                <button class="filter-pill is-active" data-filter="all">Tất cả</button>
                <?php foreach ($categories as $cat): ?>
                    <button class="filter-pill" data-filter="<?= e($cat['name']) ?>"><?= e($cat['name']) ?></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div id="menuItemsContainer">
            <?php foreach ($grouped as $catName => $items): ?>
                <div class="menu-section" data-section="<?= e($catName) ?>">
                    <h3 style="margin: 1.5rem 0 1rem; font-weight: 800; color: var(--gold-dark); font-size: 1.1rem;">
                        <i class="fas fa-caret-right"></i> <?= e($catName) ?>
                    </h3>
                    <div class="menu-items-grid">
                        <?php foreach ($items as $item): ?>
                        <div class="list-item-card">
                            <div style="display:flex; flex:1; align-items:center; cursor:pointer;" 
                                data-id="<?= $item['id'] ?>" data-name="<?= e($item['name']) ?>" 
                                data-price="<?= $item['price'] ?>" data-img="<?= $item['image'] ? BASE_URL . '/public/uploads/' . e($item['image']) : '' ?>"
                                data-desc="<?= e($item['description'] ?? '') ?>" data-order="<?= $orderId ?: '' ?>" 
                                onclick="handleOpenItemModal(this)">
                                <?php if ($item['image']): ?>
                                    <img src="<?= BASE_URL . '/public/uploads/' . e($item['image']) ?>" class="list-item-img" alt="<?= e($item['name']) ?>">
                                <?php else: ?>
                                    <div class="list-item-img" style="display:flex;align-items:center;justify-content:center;background:var(--surface-2);color:var(--text-muted);"><i class="fas fa-image"></i></div>
                                <?php endif; ?>
                                <div class="list-item-body">
                                    <div class="list-item-name"><?= e($item['name']) ?></div>
                                    <div class="list-item-price"><?= formatPrice($item['price']) ?></div>
                                </div>
                            </div>
                            <div class="list-item-action" onclick="quickAdd(event, <?= $item['id'] ?>, <?= $orderId ?: 'null' ?>)">
                                <i class="fas fa-plus"></i>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- CART SIDEBAR -->
    <?php if ($orderId > 0): ?>
        <button class="cart-fab" id="cartFab" onclick="toggleCart(true)">
            <i class="fas fa-shopping-basket"></i>
            <div class="cart-fab-badge" id="fabCount"><?= count($orderItems) ?></div>
        </button>

        <div class="pos-cart-col" id="cartCol">
            <div class="cart-panel">
                <div class="cart-header" onclick="if(window.innerWidth < 1024) toggleCart(false)" style="cursor:pointer;">
                    <div>
                        <h4 style="margin:0; font-weight:800; color:var(--gold-dark);"><?= e($tableModel->getFullDisplayName($tableId)) ?></h4>
                        <small style="color:var(--text-muted);"><?= e($order['guest_count'] ?? 1) ?> khách</small>
                    </div>
                    <button style="background:none; border:none; color:var(--gold-dark); font-size:1.2rem; cursor:pointer;" class="desktop-hide">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="cart-body" onclick="handleBodyClick(event)">
                    <?php $draftCount = 0; if (empty($orderItems)): ?>
                        <div style="text-align:center; padding-top:3rem; color:var(--text-dim); pointer-events:none;">
                            <i class="fas fa-shopping-basket" style="font-size:2rem; margin-bottom:1rem; opacity:0.3;"></i>
                            <p>Chưa có món nào.</p>
                        </div>
                    <?php else: foreach ($orderItems as $it): if ($it['status'] === 'draft') $draftCount++; ?>
                        <div class="cart-item-row" style="display:flex; justify-content:space-between; margin-bottom:1.2rem; padding-bottom:1.2rem; border-bottom:1px dashed var(--border);">
                            <div style="flex:1;">
                                <div style="font-weight:700; font-size:0.95rem; margin-bottom:4px;"><?= e($it['item_name']) ?></div>
                                <div style="display:flex; align-items:center; gap:0.75rem;">
                                    <span style="font-size:0.85rem; color:var(--gold-dark); font-weight:700;"><?= formatPrice($it['item_price']) ?></span>
                                    <div style="display:inline-flex; align-items:center; background:var(--surface-2); border-radius:20px; padding:2px 8px;">
                                        <button onclick="event.stopPropagation(); changeCartQty(<?= $it['id'] ?>, -1)" style="border:none; background:none; padding:4px; cursor:pointer;"><i class="fas fa-minus" style="font-size:0.7rem;"></i></button>
                                        <span style="width:24px; text-align:center; font-weight:800; font-size:0.85rem;"><?= $it['quantity'] ?></span>
                                        <button onclick="event.stopPropagation(); changeCartQty(<?= $it['id'] ?>, 1)" style="border:none; background:none; padding:4px; cursor:pointer;"><i class="fas fa-plus" style="font-size:0.7rem;"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align:right; pointer-events:none;">
                                <div style="font-weight:800; font-size:0.95rem;"><?= formatPrice($it['item_price'] * $it['quantity']) ?></div>
                                <small style="color:<?= $it['status'] === 'confirmed' ? 'var(--success)' : 'var(--text-muted)' ?>; font-weight:600;">
                                    <?= $it['status'] === 'confirmed' ? 'Đã gửi bếp' : 'Món nháp' ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
                <div class="cart-footer">
                    <div style="display:flex; justify-content:space-between; margin-bottom:1.25rem; font-weight:800;">
                        <span style="color:var(--text-muted);">TỔNG CỘNG</span>
                        <span style="color:var(--danger); font-size:1.4rem;" id="orderTotal"><?= formatPrice($orderTotal) ?></span>
                    </div>
                    <div id="cartActionBtn">
                        <?php if ($draftCount > 0): ?>
                            <button type="button" onclick="confirmOrderAjax(<?= $orderId ?>)" class="btn btn-gold btn-block" style="background:var(--danger); border:none; height:54px; font-size:1.05rem; box-shadow:0 4px 12px rgba(239,68,68,0.3);">
                                <i class="fas fa-concierge-bell"></i> GỬI BẾP (<?= $draftCount ?> món)
                            </button>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/orders?table_id=<?= $tableId ?>&order_id=<?= $orderId ?>" class="btn btn-gold btn-block" style="height:54px; font-size:1rem;">
                                <i class="fas fa-file-invoice-dollar"></i> XEM CHI TIẾT BILL
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div id="addToast" class="add-toast"></div>

<!-- Item Detail Modal -->
<div class="modal-backdrop" id="modalItemDetail">
    <div class="modal" style="max-width:500px; padding:0; overflow:hidden; border-radius:20px;">
        <div style="width:100%; height:250px; background:var(--surface-2); position:relative;" id="modalItemImg">
            <button class="modal-close" data-modal-close style="position:absolute; top:15px; right:15px; background:rgba(0,0,0,0.4); color:white; border-radius:50%; width:36px; height:36px;"><i class="fas fa-times"></i></button>
            <div id="modalItemImgPlaceholder" style="display:flex;align-items:center;justify-content:center;height:100%;font-size:3.5rem;color:var(--text-dim);"><i class="fas fa-image"></i></div>
        </div>
        <div style="padding:1.5rem;">
            <h2 id="modalItemName" style="margin:0; font-weight:800; font-size:1.6rem;"></h2>
            <div id="modalItemPrice" style="color:var(--gold-dark); font-weight:800; font-size:1.3rem; margin:0.5rem 0 1.25rem;"></div>
            <p id="modalItemDesc" style="font-size:0.95rem; color:var(--text-muted); line-height:1.6;"></p>
            
            <div id="orderControlsSection" style="display:none; border-top:1px dashed var(--border); padding-top:1.25rem; margin-top:1.25rem;">
                <label style="font-weight:700; font-size:0.85rem; color:var(--text-muted); display:block; margin-bottom:0.5rem;">GHI CHÚ MÓN:</label>
                <input type="text" id="modalItemNote" class="form-control" placeholder="Không hành, ít cay..." style="background:var(--surface-2); border:none; padding:12px 15px;">
                
                <div style="display:flex; align-items:center; margin-top:1.5rem; gap:1.25rem;">
                    <div style="display:flex; align-items:center; background:var(--surface-2); border-radius:50px; padding:4px;">
                        <button onclick="changeModalQty(-1)" style="width:40px; height:40px; border:none; background:none; cursor:pointer; font-size:1.1rem;"><i class="fas fa-minus"></i></button>
                        <span id="modalItemQty" style="width:36px; text-align:center; font-weight:800; font-size:1.2rem;">1</span>
                        <button onclick="changeModalQty(1)" style="width:40px; height:40px; border:none; background:none; cursor:pointer; font-size:1.1rem;"><i class="fas fa-plus"></i></button>
                    </div>
                    <button onclick="confirmAddToOrder()" class="btn btn-gold" style="flex:1; height:50px; font-weight:800; font-size:1.05rem;">
                        THÊM <span id="modalBtnTotal" style="margin-left:8px;"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentItem = null;
    function formatMoney(amount) { return new Intl.NumberFormat('vi-VN').format(amount) + '₫'; }
    function toggleCart(show) {
        const c = document.getElementById('cartCol');
        const o = document.getElementById('cartOverlay');
        if (!c) return;
        if (show) { c.classList.add('is-visible'); o?.classList.add('is-visible'); document.body.style.overflow = 'hidden'; }
        else { c.classList.remove('is-visible'); o?.classList.remove('is-visible'); document.body.style.overflow = ''; }
    }

    function handleBodyClick(e) {
        if (window.innerWidth >= 1024) return;
        // Nếu click trực tiếp vào cart-body hoặc vùng không chứa class quan trọng -> Đóng giỏ
        const isItemRow = e.target.closest('.cart-item-row');
        const isButton = e.target.closest('button, a');
        if (!isItemRow && !isButton) {
            toggleCart(false);
        }
    }

    function updateCartUI(data) {
        if (!data.ok) return;
        const body = document.querySelector('.cart-body');
        const totalEl = document.getElementById('orderTotal');
        const fTotal = document.getElementById('fabTotal');
        const fCount = document.getElementById('fabCount');
        const btnContainer = document.getElementById('cartActionBtn');
        
        if (totalEl) totalEl.textContent = data.total_fmt;
        if (fTotal) fTotal.textContent = data.total_fmt;
        if (fCount) fCount.textContent = data.items.length;
        
        if (body) {
            if (data.items.length === 0) {
                body.innerHTML = '<div style="text-align:center; padding-top:3rem; color:var(--text-dim);"><i class="fas fa-shopping-basket" style="font-size:2rem; margin-bottom:1rem; opacity:0.3;"></i><p>Chưa có món nào.</p></div>';
            } else {
                let html = ''; let draftCount = 0;
                data.items.forEach(it => {
                    if (it.status === 'draft') draftCount++;
                    html += `<div style="display:flex; justify-content:space-between; margin-bottom:1.2rem; padding-bottom:1.2rem; border-bottom:1px dashed var(--border);">
                        <div style="flex:1;">
                            <div style="font-weight:700; font-size:0.95rem; margin-bottom:4px;">${it.item_name}</div>
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                <span style="font-size:0.85rem; color:var(--gold-dark); font-weight:700;">${it.price_fmt}</span>
                                <div style="display:inline-flex; align-items:center; background:var(--surface-2); border-radius:20px; padding:2px 8px;">
                                    <button onclick="changeCartQty(${it.id}, -1)" style="border:none; background:none; padding:4px; cursor:pointer;"><i class="fas fa-minus" style="font-size:0.7rem;"></i></button>
                                    <span style="width:24px; text-align:center; font-weight:800; font-size:0.85rem;">${it.quantity}</span>
                                    <button onclick="changeCartQty(${it.id}, 1)" style="border:none; background:none; padding:4px; cursor:pointer;"><i class="fas fa-plus" style="font-size:0.7rem;"></i></button>
                                </div>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-weight:800; font-size:0.95rem;">${it.subtotal_fmt}</div>
                            <small style="color:${it.status === 'confirmed' ? 'var(--success)' : 'var(--text-muted)'}; font-weight:600;">
                                ${it.status === 'confirmed' ? 'Đã gửi bếp' : 'Món nháp'}
                            </small>
                        </div>
                    </div>`;
                });
                body.innerHTML = html;
                
                if (btnContainer) {
                    if (draftCount > 0) {
                        btnContainer.innerHTML = `<button type="button" onclick="confirmOrderAjax(<?= $orderId ?>)" class="btn btn-gold btn-block" style="background:var(--danger); border:none; height:54px; font-size:1.05rem; box-shadow:0 4px 12px rgba(239,68,68,0.3);"><i class="fas fa-concierge-bell"></i> GỬI BẾP (${draftCount} món)</button>`;
                    } else {
                        btnContainer.innerHTML = `<a href="<?= BASE_URL ?>/orders?table_id=<?= $tableId ?>&order_id=<?= $orderId ?>" class="btn btn-gold btn-block" style="height:54px; font-size:1rem;"><i class="fas fa-file-invoice-dollar"></i> XEM CHI TIẾT BILL</a>`;
                    }
                }
            }
        }
    }

    function changeCartQty(itemId, delta) {
        fetch('<?= BASE_URL ?>/orders/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ item_id: itemId, order_id: <?= $orderId ?: 0 ?>, qty: 'delta:' + delta })
        }).then(r => r.json()).then(data => { if (data.ok) updateCartUI(data); });
    }

    function confirmOrderAjax(orderId) {
        if (!confirm('Xác nhận gửi các món nháp này xuống bếp?')) return;
        fetch('<?= BASE_URL ?>/orders/confirm', { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ order_id: orderId, table_id: <?= $tableId ?: 0 ?> })
        }).then(r => r.json()).then(res => { if (res.ok) { showToast('Đã gửi bếp thành công!'); location.reload(); } });
    }

    function handleOpenItemModal(el) {
        const d = el.dataset;
        currentItem = { id: d.id, name: d.name, price: parseFloat(d.price), orderId: d.order, qty: 1 };
        document.getElementById('modalItemName').textContent = d.name;
        document.getElementById('modalItemPrice').textContent = formatMoney(d.price);
        document.getElementById('modalItemDesc').textContent = d.desc || '';
        const imgEl = document.getElementById('modalItemImg');
        const placeholder = document.getElementById('modalItemImgPlaceholder');
        imgEl.querySelectorAll('img').forEach(i => i.remove());
        if (d.img) {
            placeholder.style.display = 'none';
            const i = document.createElement('img');
            i.src = d.img; i.style.cssText = 'width:100%; height:100%; object-fit:cover;';
            imgEl.appendChild(i);
        } else { placeholder.style.display = 'flex'; }
        document.getElementById('orderControlsSection').style.display = d.order ? 'block' : 'none';
        if (d.order) updateModalUI();
        Aurora.openModal('modalItemDetail');
    }

    function changeModalQty(delta) { if (!currentItem) return; currentItem.qty = Math.max(1, currentItem.qty + delta); updateModalUI(); }
    function updateModalUI() { document.getElementById('modalItemQty').textContent = currentItem.qty; document.getElementById('modalBtnTotal').textContent = formatMoney(currentItem.qty * currentItem.price); }

    function confirmAddToOrder() {
        const f = new FormData();
        f.append('order_id', currentItem.orderId); f.append('menu_item_id', currentItem.id);
        f.append('qty', currentItem.qty); f.append('note', document.getElementById('modalItemNote').value);
        fetch('<?= BASE_URL ?>/orders/add', { method: 'POST', body: f }).then(res => res.json()).then(res => { 
            if (res.ok) { Aurora.closeModal('modalItemDetail'); showToast('Đã thêm món!'); updateCartUI(res); } 
        });
    }

    function quickAdd(event, itemId, orderId) {
        event.stopPropagation();
        if (!orderId) { alert('Vui lòng chọn bàn trước khi gọi món!'); return; }
        const f = new FormData(); f.append('order_id', orderId); f.append('menu_item_id', itemId); f.append('qty', 1);
        fetch('<?= BASE_URL ?>/orders/add', { method: 'POST', body: f }).then(res => res.json()).then(res => { 
            if (res.ok) { showToast('Đã thêm món!'); updateCartUI(res); } 
        });
    }

    function showToast(msg) {
        const t = document.getElementById('addToast');
        if (!t) return;
        t.textContent = msg; t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2000);
    }

    document.querySelectorAll('.filter-pill').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('is-active'));
            pill.classList.add('is-active');
            const f = pill.dataset.filter;
            document.querySelectorAll('.menu-section').forEach(s => { s.style.display = (f === 'all' || s.dataset.section === f) ? 'block' : 'none'; });
        });
    });
</script>