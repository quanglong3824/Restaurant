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

        <!-- Order header info -->
        <div class="order-header-card">
            <div class="order-header-card__info">
                <div class="order-meta">
                    <i class="fas fa-chair"></i>
                    <strong>
                        <?= e($table['name']) ?>
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
            <a href="<?= BASE_URL ?>/menu?table_id=<?= $table['id'] ?>&order_id=<?= $order['id'] ?>"
                class="btn btn-gold btn-sm">
                <i class="fas fa-plus"></i> Thêm món
            </a>
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
                <a href="<?= BASE_URL ?>/orders/print?order_id=<?= $order['id'] ?>" target="_blank" class="btn btn-outline" style="flex: 1;">
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