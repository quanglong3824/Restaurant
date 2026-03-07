<?php // views/orders/index.php — Order của bàn (Waiter)
// Phân chia món: Draft (đang chọn) vs Confirmed (đã gửi bếp)
$draftItems = [];
$confirmedItems = [];
$hasDraft = false;

if (!empty($items)) {
    foreach ($items as $item) {
        if (($item['status'] ?? 'draft') === 'confirmed') {
            $confirmedItems[] = $item;
        } else {
            $draftItems[] = $item;
            $hasDraft = true;
        }
    }
}
?>
<style>
    /* Order Page - Consistent with Menu Design */
    .order-header-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 0px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .order-header-card__info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .order-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        color: var(--text);
    }

    .order-meta i {
        color: var(--gold);
    }

    .order-meta--small {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .badge-gold {
        background: var(--gold-light);
        color: var(--gold-dark);
        padding: 0.25rem 0.75rem;
        border-radius: 0px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .order-section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text);
        margin: 1.5rem 0 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--gold);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .order-section-title i {
        color: var(--gold);
    }

    .order-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 0px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s;
    }

    .order-item:hover {
        border-color: var(--gold);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.15);
    }

    .order-item--confirmed {
        background: var(--surface-2);
        opacity: 0.9;
    }

    .order-item__name {
        flex: 1;
        font-weight: 600;
        color: var(--text);
        font-size: 0.95rem;
    }

    .order-item__note {
        display: block;
        font-size: 0.8rem;
        color: var(--warning);
        font-style: italic;
        margin-top: 0.25rem;
    }

    .order-item__controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .order-item__price {
        font-weight: 800;
        color: var(--gold-dark);
        font-size: 1rem;
        min-width: 100px;
        text-align: right;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.35rem 0.75rem;
        border-radius: 0px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-success {
        background: var(--success-bg);
        color: var(--success-text);
    }

    .badge-warning {
        background: var(--warning-bg);
        color: var(--warning-text);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn-lg {
        padding: 0.875rem 1.5rem;
        font-size: 1rem;
    }

    .order-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }

    /* Quantity Buttons - Consistent with Menu */
    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 0px;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
    }

    .qty-btn:hover {
        border-color: var(--gold);
        color: var(--gold);
        background: var(--gold-light);
    }

    .qty-val {
        min-width: 32px;
        text-align: center;
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--text);
    }

    .order-item__del {
        width: 36px;
        height: 36px;
        border-radius: 0px;
        border: 1px solid var(--danger-border);
        background: transparent;
        color: var(--danger);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        margin-left: 0.5rem;
    }

    .order-item__del:hover {
        background: var(--danger-bg);
        border-color: var(--danger);
    }

    /* Total Bar - Consistent with Cart */
    .order-total-bar {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 0px;
        padding: 1rem 1.25rem;
        margin: 1.5rem 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .order-total-bar__label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--text);
    }

    .order-total-bar__label i {
        color: var(--gold);
    }

    .order-total-bar__amount {
        font-weight: 800;
        font-size: 1.25rem;
        color: var(--gold-dark);
    }

    /* Scrollable Box for Confirmed Items */
    .confirmed-scroll-box {
        max-height: 480px;
        overflow-y: auto;
        padding-right: 0.5rem;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        border-radius: 8px;
    }

    .confirmed-scroll-box::-webkit-scrollbar {
        width: 6px;
    }

    .confirmed-scroll-box::-webkit-scrollbar-track {
        background: var(--surface-2);
        border-radius: 10px;
    }

    .confirmed-scroll-box::-webkit-scrollbar-thumb {
        background: var(--border-gold);
        border-radius: 10px;
    }
</style>

<div class="page-content">

    <?php if (!$order): ?>
        <!-- Bàn chưa có order -->
        <div class="empty-state" style="margin-top: 3rem;">
            <i class="fas fa-receipt"></i>
            <h3>Bàn chưa có order</h3>
            <p>Mở bàn từ màn hình sơ đồ bàn để bắt đầu.</p>
            <a href="<?= BASE_URL ?>/tables" class="btn btn-gold" style="margin-top: 1rem;">
                <i class="fas fa-arrow-left"></i> Về Sơ đồ Bàn
            </a>
        </div>
    <?php else: ?>

        <?php if (!empty($mergeSuggestion)): ?>
            <div
                style="background: var(--warning-bg); color: var(--warning-text); padding: 1rem; border-left: 4px solid var(--warning); margin-bottom: 1.5rem; border-radius: 4px; display: flex; align-items: flex-start; gap: 0.75rem;">
                <i class="fas fa-exclamation-triangle" style="margin-top: 0.2rem;"></i>
                <div><?= $mergeSuggestion ?></div>
            </div>
        <?php endif; ?>

        <!-- Order header info -->
        <div class="order-header-card">
            <div class="order-header-card__info">
                <div class="order-meta">
                    <i class="fas fa-chair"></i>
                    <strong>
                        <?= e($table_display_name) ?>
                    </strong>
                    <span class="badge badge-gold">
                        <?= $order['guest_count'] ?> khách
                    </span>
                </div>
                <div class="order-meta order-meta--small">
                    <i class="fas fa-clock"></i>
                    <span>Mở:
                        <?= date('H:i', strtotime($order['opened_at'])) ?>
                    </span>
                    <span>·</span>
                    <i class="fas fa-user"></i>
                    <span>
                        <?= e($order['waiter_name']) ?>
                    </span>
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <?php if (empty($table['parent_id'])): ?>
                    <button type="button" class="btn btn-outline btn-sm" onclick="Aurora.openModal('modalSelectTarget')">
                        <i class="fas fa-link"></i> Ghép bàn
                    </button>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/menu?table_id=<?= $table['id'] ?>&order_id=<?= $order['id'] ?>"
                    class="btn btn-gold btn-sm">
                    <i class="fas fa-plus"></i> Thêm món
                </a>
            </div>
        </div>

        <!-- Order items list -->
        <div class="order-items-list" id="orderItemsList">
            <?php if (empty($items)): ?>
                <div class="empty-state" style="padding: 2rem 1rem;">
                    <i class="fas fa-utensils"></i>
                    <h3>Chưa có món nào</h3>
                    <p>Bấm "Thêm món" để ghi order.</p>
                </div>
            <?php else: ?>

                <!-- Confirmed items -->
                <?php if (!empty($confirmedItems)): ?>
                    <div class="order-section-title">Đã xác nhận (Đang làm/Đã lên món)</div>
                    <div class="confirmed-scroll-box">
                        <?php foreach ($confirmedItems as $item): ?>
                            <div class="order-item order-item--confirmed" id="row-<?= $item['id'] ?>"
                                style="opacity: 0.8; background: var(--surface-2);">
                                <div class="order-item__name">
                                    <?= e($item['item_name']) ?>
                                    <?php if ($item['note']): ?>
                                        <span class="order-item__note">
                                            <?= e($item['note']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="order-item__controls" style="width: auto; padding: 0 .5rem;">
                                    <span class="badge badge-success"><i class="fas fa-check"></i> x<?= $item['quantity'] ?></span>
                                </div>
                                <div class="order-item__price">
                                    <?= formatPrice($item['item_price'] * $item['quantity']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Draft items -->
                <?php if (!empty($draftItems)): ?>
                    <div class="order-section-title" style="margin-top: 1rem;">Món nháp (Đang chọn)</div>
                    <?php foreach ($draftItems as $item): ?>
                        <div class="order-item" id="row-<?= $item['id'] ?>">
                            <div class="order-item__name">
                                <?= e($item['item_name']) ?>
                                <?php if ($item['note']): ?>
                                    <span class="order-item__note">
                                        <?= e($item['note']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="order-item__controls">
                                <button class="qty-btn" onclick="changeQty(<?= $item['id'] ?>, <?= $order['id'] ?>, -1)"
                                    aria-label="Giảm">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="qty-val" id="qty-<?= $item['id'] ?>">
                                    <?= $item['quantity'] ?>
                                </span>
                                <button class="qty-btn" onclick="changeQty(<?= $item['id'] ?>, <?= $order['id'] ?>, 1)"
                                    aria-label="Tăng">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="order-item__price">
                                <?= formatPrice($item['item_price'] * $item['quantity']) ?>
                            </div>
                            <button class="order-item__del" onclick="removeItem(<?= $item['id'] ?>, <?= $order['id'] ?>)"
                                aria-label="Xóa món">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            <?php endif; ?>
        </div>

        <!-- Total bar -->
        <div class="order-total-bar" id="orderTotalBar">
            <div class="order-total-bar__label">
                <i class="fas fa-receipt"></i> Tổng tiền
            </div>
            <div class="order-total-bar__amount" id="orderTotal">
                <?= formatPrice($total) ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="padding: 0 0 1rem; display: flex; gap: .75rem; flex-direction: column;">
            <?php if ($hasDraft): ?>
                <form method="POST" action="<?= BASE_URL ?>/orders/confirm" style="display: block;">
                    <input type="hidden" name="table_id" value="<?= $table['id'] ?>">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <button type="submit" class="btn btn-gold btn-block btn-lg"
                        style="box-shadow: 0 4px 12px rgba(212,175,55,0.4);">
                        <i class="fas fa-concierge-bell"></i> Xác nhận gửi Bếp
                    </button>
                </form>
            <?php endif; ?>

            <div style="display: flex; gap: 0.75rem;">
                <button class="btn btn-danger-outline" style="flex: 1;"
                    onclick="confirmClose(<?= $table['id'] ?>, <?= $order['id'] ?>)">
                    <i class="fas fa-door-closed"></i> Đóng bàn
                </button>
                <a href="<?= BASE_URL ?>/orders/print?order_id=<?= $order['id'] ?>" target="_blank" class="btn"
                    style="flex: 1; background: #0ea5e9; color: #fff; border-color: #0ea5e9;">
                    <i class="fas fa-print"></i> In Hóa Đơn
                </a>
            </div>
        </div>

    <?php endif; ?>
</div>

<!-- Close confirm modal -->
<div class="modal-backdrop" id="modalClose">
    <div class="modal" style="max-width: 360px;">
        <div class="modal-header">
            <h3>Kết thúc phục vụ bàn?</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p style="color:var(--text-muted);margin-bottom:1rem;font-size:0.9rem;">
                Sau khi đóng, hóa đơn sẽ được cập nhật là Đã Thanh Toán và bàn sẽ về trạng thái <strong
                    style="color:var(--success)">Trống</strong>.
            </p>
            <form method="POST" action="<?= BASE_URL ?>/tables/close" id="formCloseTable">
                <input type="hidden" name="table_id" id="closeTableId">
                <input type="hidden" name="order_id" id="closeOrderId">

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label">Hình thức thanh toán</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="cash">Tiền mặt</option>
                        <option value="transfer">Chuyển khoản</option>
                        <option value="card">Thẻ ngân hàng</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="checkPaid" required style="width: 20px; height: 20px; cursor: pointer;">
                    <label for="checkPaid" style="font-weight: 500; cursor: pointer; user-select: none;">
                        Tôi xác nhận khách Đã Thanh Toán
                    </label>
                </div>

                <div style="display:flex;gap:.6rem;flex-direction:column;">
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="fas fa-door-closed"></i> Đóng Bàn
                    </button>
                    <button type="button" class="btn btn-ghost" data-modal-close>Quay lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Chọn bàn để Ghép -->
<div class="modal-backdrop" id="modalSelectTarget">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header">
            <h3 id="targetModalTitle"><i class="fas fa-link"></i> Ghép thêm bàn</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form id="targetForm" method="POST" action="<?= BASE_URL ?>/tables/merge" class="modal-body">
            <input type="hidden" name="parent_id" value="<?= $table['id'] ?? '' ?>">
            <input type="hidden" name="redirect"
                value="/orders?table_id=<?= $table['id'] ?? '' ?>&order_id=<?= $order['id'] ?? '' ?>">
            <p style="margin-bottom:1rem; font-size:0.9rem; color:var(--text-muted);">
                Chọn bàn trống để ghép chung với <?= isset($table['name']) ? e($table['name']) : '' ?>:
            </p>
            <div class="form-group">
                <label class="form-label">Chọn bàn trống</label>
                <select name="child_id" class="form-control" required>
                    <option value="">-- Chọn một bàn trống --</option>
                    <?php
                    if (!empty($grouped)):
                        foreach ($grouped as $area => $tables):
                            foreach ($tables as $t):
                                if ($t['status'] === 'available' && empty($t['parent_id'])):
                                    ?>
                                    <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (<?= e($area) ?>)</option>
                                    <?php
                                endif;
                            endforeach;
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-gold btn-block btn-lg">XÁC NHẬN GHÉP</button>
        </form>
    </div>
</div>

<script>
    function changeQty(itemId, orderId, delta) {
        const qtyEl = document.getElementById('qty-' + itemId);
        if (!qtyEl) return;

        const current = parseInt(qtyEl.textContent);
        const newQty = Math.max(0, current + delta);

        fetch('<?= BASE_URL ?>/orders/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ item_id: itemId, order_id: orderId, qty: newQty })
        })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    if (newQty === 0) {
                        document.getElementById('row-' + itemId)?.remove();
                        checkEmptyDrafts();
                    } else {
                        qtyEl.textContent = newQty;
                    }
                    document.getElementById('orderTotal').textContent = data.total_fmt;
                }
            });
    }

    function removeItem(itemId, orderId) {
        fetch('<?= BASE_URL ?>/orders/remove', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ item_id: itemId, order_id: orderId })
        })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    document.getElementById('row-' + itemId)?.remove();
                    document.getElementById('orderTotal').textContent = data.total_fmt;
                    checkEmptyDrafts();
                }
            });
    }

    function checkEmptyDrafts() {
        const drafts = document.querySelectorAll('.order-item:not(.order-item--confirmed)');
        if (drafts.length === 0) {
            // Tải lại trang mềm để ẩn nút gửi bếp nếu hết draft
            window.location.reload();
        }
    }

    function confirmClose(tableId, orderId) {
        document.getElementById('closeTableId').value = tableId;
        document.getElementById('closeOrderId').value = orderId;
        Aurora.openModal('modalClose');
    }
</script>