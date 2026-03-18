<?php // views/menu/index.php — Digital Menu (Improved POS Layout) ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/menu/index.css">

<div class="page-content pos-layout">

    <!-- MAIN MENU -->
    <div class="pos-menu-col">
        <div class="menu-type-tabs">
            <?php foreach ($menuTypes as $type): ?>
                <a href="<?= BASE_URL ?>/menu?type=<?= e($type['key']) ?><?= $tableId ? '&table_id=' . $tableId : '' ?><?= $orderId ? '&order_id=' . $orderId : '' ?>"
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
                    <button class="filter-pill" data-filter="<?= e($cat['name']) ?>">
                        <?= e($cat['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div id="menuItemsContainer">
            <?php if ($currentType === 'alacarte'): ?>
                <?php if (!empty($sets)): ?>
                    <div class="menu-section" data-section="Sets & Combo">
                        <h3><i class="fas fa-utensils"></i> SETS & COMBO</h3>
                        <div class="menu-items-grid">
                            <?php foreach ($sets as $set): ?>
                                <div class="list-item-card" style="border:1px solid var(--gold-light); background:#fffcf5;">
                                    <div style="display:flex; flex:1; align-items:center; cursor:pointer; gap:0.65rem;"
                                        onclick="handleOpenSetModal(<?= e(json_encode($set)) ?>)">
                                        <?php if ($set['image']): ?>
                                            <img src="<?= BASE_URL . '/public/uploads/' . e($set['image']) ?>" class="list-item-img" alt="<?= e($set['name']) ?>">
                                        <?php else: ?>
                                            <div class="list-item-img" style="display:flex;align-items:center;justify-content:center;color:var(--gold-dark);">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="list-item-body">
                                            <div class="list-item-name" style="color:var(--gold-dark);">
                                                <?= e($set['name']) ?>
                                            </div>
                                            <div class="list-item-price"><?= formatPrice($set['price']) ?></div>
                                        </div>
                                    </div>
                                    <div class="list-item-action" style="background:var(--gold); color:white;" onclick="handleOpenSetModal(<?= e(json_encode($set)) ?>)">
                                        <i class="fas fa-list-ul"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <?php foreach ($grouped as $catName => $items): ?>
                    <div class="menu-section" data-section="<?= e($catName) ?>">
                        <h3><i class="fas fa-caret-right"></i> <?= e($catName) ?></h3>
                        <div class="menu-items-grid">
                            <?php foreach ($items as $item): ?>
                                <div class="list-item-card">
                                    <div style="display:flex; flex:1; align-items:center; cursor:pointer; gap:0.65rem;"
                                        data-id="<?= $item['id'] ?>" data-name="<?= e($item['name']) ?>"
                                        data-price="<?= $item['price'] ?>"
                                        data-img="<?= $item['image'] ? BASE_URL . '/public/uploads/' . e($item['image']) : '' ?>"
                                        data-desc="<?= e($item['description'] ?? '') ?>" data-order="<?= $orderId ?: '' ?>"
                                        onclick="handleOpenItemModal(this)">
                                        <?php if ($item['image']): ?>
                                            <img src="<?= BASE_URL . '/public/uploads/' . e($item['image']) ?>" class="list-item-img" alt="<?= e($item['name']) ?>">
                                        <?php else: ?>
                                            <div class="list-item-img" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                                                <i class="fas fa-image"></i>
                                            </div>
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
            <?php endif; ?>
        </div>
    </div>

    <!-- CART SIDEBAR - Always Visible -->
    <div class="pos-cart-col" id="posCartCol">
        <div class="cart-panel">
            <div class="cart-header" onclick="toggleMobileCart()">
                <div>
                    <h4><?= $tableId > 0 ? e($tableModel->getFullDisplayName($tableId)) : 'GIỎ HÀNG' ?></h4>
                    <small id="cartHeaderSub"><?= $orderId > 0 ? e($order['guest_count'] ?? 1) . ' khách' : 'Chưa chọn bàn' ?></small>
                </div>
                <div class="cart-header-actions">
                    <?php if ($orderId > 0 && count(array_filter($orderItems, fn($it) => $it['status'] === 'draft')) > 0): ?>
                        <button type="button" class="split-btn" onclick="event.stopPropagation(); openSplitModal()" title="Tách bàn">
                            <i class="fas fa-cut"></i>
                        </button>
                    <?php endif; ?>
                    <i class="fas fa-chevron-up d-md-none" id="cartChevron"></i>
                </div>
            </div>
            
            <div class="cart-body">
                <?php if ($orderId <= 0): ?>
                    <div class="select-table-box">
                        <i class="fas fa-chair"></i>
                        <p class="fw-bold mb-3">Vui lòng chọn bàn</p>
                        <select class="cart-table-select" onchange="handleTableSelect(this.value)">
                            <option value="">-- Chọn bàn --</option>
                            <?php foreach ($tables as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (<?= e($t['area']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-muted small">Bạn cần chọn bàn trước khi thêm món.</p>
                    </div>
                <?php elseif (empty($orderItems)): ?>
                    <div class="empty-cart">
                        <i class="fas fa-shopping-basket"></i>
                        <p>Chưa có món nào</p>
                    </div>
                <?php else: ?>
                    <?php 
                    $draftItems = array_filter($orderItems, fn($it) => $it['status'] === 'draft');
                    $pendingItems = array_filter($orderItems, fn($it) => $it['status'] === 'pending');
                    $confirmedItems = array_filter($orderItems, fn($it) => $it['status'] === 'confirmed');
                    ?>

                    <?php if (!empty($draftItems)): ?>
                        <div class="section-label"><i class="fas fa-edit"></i> Món nháp</div>
                        <?php foreach ($draftItems as $it): ?>
                            <div class="cart-item-row" data-item-id="<?= $it['id'] ?>">
                                <div style="flex:1;">
                                    <div class="cart-item-name"><?= e($it['item_name']) ?></div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="cart-item-price"><?= formatPrice($it['item_price']) ?></span>
                                        <div class="qty-control">
                                            <button onclick="changeCartQty(<?= $it['id'] ?>, -1)"><i class="fas fa-minus"></i></button>
                                            <span><?= $it['quantity'] ?></span>
                                            <button onclick="changeCartQty(<?= $it['id'] ?>, 1)"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="cart-item-price"><?= formatPrice($it['item_price'] * $it['quantity']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($pendingItems)): ?>
                        <div class="section-label text-warning"><i class="fas fa-clock"></i> Chờ xác nhận</div>
                        <?php foreach ($pendingItems as $it): ?>
                            <div class="cart-item-row opacity-75">
                                <div style="flex:1;">
                                    <div class="cart-item-name"><?= e($it['item_name']) ?></div>
                                    <span class="cart-item-qty">x<?= $it['quantity'] ?></span>
                                </div>
                                <div class="text-end">
                                    <div class="cart-item-price"><?= formatPrice($it['item_price'] * $it['quantity']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($confirmedItems)): ?>
                        <div class="section-label text-success"><i class="fas fa-check-circle"></i> Đã gửi bếp</div>
                        <?php foreach ($confirmedItems as $it): ?>
                            <div class="cart-item-row opacity-75">
                                <div style="flex:1;">
                                    <div class="cart-item-name"><?= e($it['item_name']) ?></div>
                                    <span class="cart-item-qty">x<?= $it['quantity'] ?></span>
                                </div>
                                <div class="text-end">
                                    <div class="cart-item-price"><?= formatPrice($it['item_price'] * $it['quantity']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="cart-footer">
                <div class="total-row">
                    <span class="total-label">Tổng cộng</span>
                    <span class="total-amount" id="orderTotal"><?= formatPrice($orderTotal) ?></span>
                </div>
                <div id="cartActionBtn">
                    <?php if ($orderId > 0): ?>
                        <?php $draftCount = count(array_filter($orderItems, fn($it) => $it['status'] === 'draft')); ?>
                        <?php if ($draftCount > 0): ?>
                            <button type="button" onclick="confirmOrderAjax(<?= $orderId ?>)" class="cart-action-btn btn-gold w-100" style="background:var(--danger)">
                                <i class="fas fa-concierge-bell me-2"></i> GỬI BẾP (<?= $draftCount ?>)
                            </button>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/orders?table_id=<?= $tableId ?>&order_id=<?= $orderId ?>" class="cart-action-btn btn-gold w-100" style="background:var(--success)">
                                <i class="fas fa-file-invoice me-2"></i> XEM HÓA ĐƠN
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button disabled class="cart-action-btn btn-ghost w-100">CHƯA CHỌN BÀN</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="addToast" class="add-toast"></div>

<!-- Item Modal -->
<div class="modal-backdrop" id="modalItemDetail">
    <div class="modal modal-premium">
        <div class="modal-header">
            <h3 id="modalItemName"></h3>
            <button class="modal-close" data-modal-close><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="modalItemImgContainer" class="mb-3" style="height:200px; border-radius:12px; overflow:hidden;"></div>
            <div id="modalItemPrice" class="text-gold fw-800 mb-2" style="font-size:1.2rem;"></div>
            <p id="modalItemDesc" class="text-muted small mb-4"></p>

            <div id="orderControlsSection" style="display:none;">
                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <input type="text" id="modalItemNote" class="form-control" placeholder="Ví dụ: Không hành, ít cay...">
                </div>
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div class="qty-control">
                        <button onclick="changeModalQty(-1)"><i class="fas fa-minus"></i></button>
                        <span id="modalItemQty">1</span>
                        <button onclick="changeModalQty(1)"><i class="fas fa-plus"></i></button>
                    </div>
                    <button onclick="confirmAddToOrder()" class="btn-gold flex-fill">
                        THÊM: <span id="modalBtnTotal"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const MENU_CONFIG = {
    baseUrl: '<?= BASE_URL ?>',
    orderId: <?= $orderId ?: 0 ?>,
    tableId: <?= $tableId ?: 0 ?>
};
</script>

<script src="<?= BASE_URL ?>/public/js/menu/index.js" defer></script>
