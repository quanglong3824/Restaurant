<?php // views/orders/index.php — Professional Order Detail View
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

<div class="page-content" style="padding-bottom: 2rem;">

    <?php if (!$order): ?>
        <div class="empty-state py-5 text-center">
            <i class="fas fa-receipt fa-4x mb-3 opacity-10"></i>
            <h4 class="fw-bold">Bàn chưa có order</h4>
            <p class="text-muted small">Quay lại sơ đồ bàn để bắt đầu phục vụ.</p>
            <a href="<?= BASE_URL ?>/tables" class="btn btn-gold px-5 py-3 mt-3">VỀ SƠ ĐỒ BÀN</a>
        </div>
    <?php else: ?>

        <!-- Table Identity Card -->
        <div class="card mb-4 identity-card shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="id-icon">
                    <span class="id-num"><?= e(str_replace('Bàn ', '', $table_display_name)) ?></span>
                </div>
                <div class="id-main">
                    <h2 class="playfair mb-1"><?= e($table_display_name) ?></h2>
                    <div class="d-flex gap-3 small text-muted">
                        <span><i class="fas fa-clock text-gold me-1"></i>
                            <?= date('H:i', strtotime($order['opened_at'])) ?></span>
                        <span><i class="fas fa-user-friends text-gold me-1"></i> <?= $order['guest_count'] ?> khách</span>
                        <span><i class="fas fa-user-circle text-gold me-1"></i> <?= e($order['waiter_name']) ?></span>
                    </div>
                </div>
                <div class="id-actions ms-auto d-flex gap-2">
                    <a href="<?= BASE_URL ?>/menu?table_id=<?= $table['id'] ?>&order_id=<?= $order['id'] ?>"
                        class="btn btn-gold btn-sm px-3">
                        <i class="fas fa-plus me-1"></i> THÊM MÓN
                    </a>
                </div>
            </div>
        </div>

        <!-- Items Stream -->
        <div class="order-stream">

            <!-- Confirmed Section -->
            <?php if (!empty($confirmedItems)): ?>
                <div class="section-label mb-3 mt-4">ĐÃ GỬI BẾP (XÁC NHẬN)</div>
                <div class="items-grid-confirmed">
                    <?php foreach ($confirmedItems as $item): ?>
                        <div class="item-plate plate-confirmed">
                            <div class="plate-info">
                                <div class="plate-name"><?= e($item['item_name']) ?></div>
                                <?php if ($item['note']): ?>
                                    <div class="plate-note"><i class="fas fa-comment-dots me-1"></i> <?= e($item['note']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="plate-qty">x<?= $item['quantity'] ?></div>
                            <div class="plate-price"><?= formatPrice($item['item_price'] * $item['quantity']) ?></div>
                            <div class="plate-status"><i class="fas fa-check-circle"></i></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Draft Section -->
            <?php if (!empty($draftItems)): ?>
                <div class="section-label mb-3 mt-4 text-warning">MÓN ĐANG CHỜ GỬI BẾP</div>
                <div class="items-grid-draft">
                    <?php foreach ($draftItems as $item): ?>
                        <div class="item-plate plate-draft mb-3 glass-card">
                            <div class="plate-info">
                                <div class="plate-name"><?= e($item['item_name']) ?></div>
                                <?php if ($item['note']): ?>
                                    <div class="plate-note"><?= e($item['note']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="plate-controls">
                                <button class="q-btn" onclick="changeQty(<?= $item['id'] ?>, <?= $order['id'] ?>, -1)"><i
                                        class="fas fa-minus"></i></button>
                                <span class="q-val" id="qty-<?= $item['id'] ?>"><?= $item['quantity'] ?></span>
                                <button class="q-btn" onclick="changeQty(<?= $item['id'] ?>, <?= $order['id'] ?>, 1)"><i
                                        class="fas fa-plus"></i></button>
                            </div>
                            <div class="plate-price-total"><?= formatPrice($item['item_price'] * $item['quantity']) ?></div>
                            <button class="plate-del" onclick="removeItem(<?= $item['id'] ?>, <?= $order['id'] ?>)"><i
                                    class="fas fa-trash-alt"></i></button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (empty($items)): ?>
                <div class="card text-center py-5 opacity-40 border-dashed">
                    <i class="fas fa-utensils fa-3x mb-3"></i>
                    <p class="fw-bold">Chưa chọn món nào</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Floating Bill Bar -->
        <div class="bill-dock mt-5">
            <div class="dock-summary glass-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="dock-label">TỔNG TẠM TÍNH (VAT INCL.)</div>
                    <div class="dock-amount" id="orderTotal"><?= formatPrice($total) ?></div>
                </div>

                <div class="d-grid gap-3">
                    <?php if ($hasDraft): ?>
                        <form method="POST" action="<?= BASE_URL ?>/orders/confirm">
                            <input type="hidden" name="table_id" value="<?= $table['id'] ?>">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn btn-gold w-100 py-3 shadow-lg pulse-animation">
                                <i class="fas fa-concierge-bell me-2"></i> XÁC NHẬN GỬI BẾP NGAY
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if ($total > 0): ?>
                        <button class="btn btn-success-luxury w-100 py-3"
                            onclick="confirmPayment(<?= $table['id'] ?>, <?= $order['id'] ?>, <?= $total ?>)">
                            <i class="fas fa-credit-card me-2"></i> THANH TOÁN & IN HÓA ĐƠN
                        </button>
                        <a href="<?= BASE_URL ?>/orders/print?order_id=<?= $order['id'] ?>" target="_blank"
                            class="btn btn-ghost w-100">
                            <i class="fas fa-print me-2"></i> XEM / IN BILL TẠM (TÍNH TIỀN)
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline-danger w-100 py-3"
                            onclick="confirmClose(<?= $table['id'] ?>, <?= $order['id'] ?>)">
                            <i class="fas fa-door-closed me-2"></i> HỦY BÀN / ĐÓNG BÀN TRỐNG
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<!-- Modal: Thanh toán -->
<div class="modal-backdrop" id="modalClose">
    <div class="modal modal-premium" style="max-width: 420px;">
        <div class="modal-header py-4" id="paymentModalHeader">
            <h3 class="playfair m-0"><i class="fas fa-check-circle me-2"></i> THANH TOÁN</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body p-4">
            <div id="paymentSummary" class="text-center mb-4">
                <div class="text-muted small fw-bold text-uppercase">Cần thanh toán</div>
                <div class="display-5 fw-bold text-gold py-2" id="modalTotalAmount"><?= formatPrice($total) ?></div>
            </div>

            <form method="POST" action="<?= BASE_URL ?>/tables/close" id="formCloseTable">
                <input type="hidden" name="table_id" id="closeTableId">
                <input type="hidden" name="order_id" id="closeOrderId">
                <input type="hidden" id="isQuickCancel" value="0">

                <div class="form-group payment-only">
                    <label class="form-label small fw-bold text-muted mb-3">PHƯƠNG THỨC THANH TOÁN</label>
                    <div class="payment-grid mb-4">
                        <label class="pay-btn">
                            <input type="radio" name="payment_method" value="cash" checked>
                            <span class="pay-cell"><i class="fas fa-money-bill-wave"></i> TIỀN MẶT</span>
                        </label>
                        <label class="pay-btn">
                            <input type="radio" name="payment_method" value="transfer">
                            <span class="pay-cell"><i class="fas fa-university"></i> CHUYỂN KHOẢN</span>
                        </label>
                    </div>
                </div>

                <div class="payment-only mb-4 p-3 rounded-pill bg-light d-flex align-items-center gap-2">
                    <input type="checkbox" id="checkPaid" required>
                    <label for="checkPaid" class="small fw-bold m-0">Đã nhận đủ tiền từ khách hàng</label>
                </div>

                <button type="button" id="btnSubmitPayment" class="btn btn-gold w-100 py-3 shadow-lg"
                    onclick="handleSubmitPayment(event)">
                    HOÀN TẤT VÀ IN BILL
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* Order Premium View Styles */
    .identity-card {
        border-left: 5px solid var(--gold);
        border-radius: var(--radius) !important;
        padding: 1.5rem !important;
    }

    .id-icon {
        width: 56px;
        height: 56px;
        background: var(--gold-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .id-num {
        font-size: 1.75rem;
        font-weight: 900;
        color: var(--gold-dark);
        font-family: 'Outfit', sans-serif;
    }

    .section-label {
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        opacity: 0.5;
    }

    .items-grid-confirmed {
        border-radius: var(--radius);
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .item-plate {
        display: flex;
        align-items: center;
        padding: 1.25rem;
        background: white;
        border-bottom: 1px solid var(--border);
        gap: 1rem;
    }

    .plate-confirmed {
        background: #fdfdfd;
        opacity: 0.7;
    }

    .plate-confirmed:last-child {
        border-bottom: none;
    }

    .plate-info {
        flex: 1;
    }

    .plate-name {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text);
    }

    .plate-note {
        font-size: 0.75rem;
        color: var(--danger);
        font-style: italic;
        margin-top: 2px;
    }

    .plate-qty {
        font-weight: 800;
        color: var(--gold-dark);
        width: 40px;
        text-align: center;
    }

    .plate-price {
        font-weight: 700;
        color: var(--text-dim);
        min-width: 100px;
        text-align: right;
        font-size: 0.9rem;
    }

    .plate-status {
        color: var(--success);
    }

    .plate-draft {
        border-radius: var(--radius);
        border: 1px solid var(--border-gold);
        background: #fffaf0 !important;
        box-shadow: var(--shadow);
    }

    .plate-controls {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .q-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1px solid var(--gold);
        background: white;
        color: var(--gold-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .q-btn:active {
        background: var(--gold);
        color: white;
    }

    .q-val {
        font-weight: 900;
        width: 24px;
        text-align: center;
    }

    .plate-price-total {
        font-weight: 800;
        color: var(--gold-dark);
        flex: 1;
        text-align: right;
        padding-right: 1.5rem;
    }

    .plate-del {
        color: var(--text-dim);
        background: none;
        border: none;
        font-size: 1.1rem;
        transition: color 0.2s;
    }

    .plate-del:hover {
        color: var(--danger);
    }

    .bill-dock {
        position: sticky;
        bottom: 0;
        margin-inline: -1rem;
        padding: 1rem;
        background: linear-gradient(to top, var(--bg) 80%, transparent);
    }

    .dock-summary {
        padding: 1.5rem;
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border);
    }

    .dock-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--text-dim);
        letter-spacing: 1px;
    }

    .dock-amount {
        font-size: 2rem;
        font-weight: 900;
        color: var(--gold-dark);
    }

    .btn-success-luxury {
        background: #15803d;
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(21, 128, 61, 0.4);
    }

    .btn-success-luxury:active {
        transform: scale(0.96);
    }

    .payment-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .pay-btn input {
        display: none;
    }

    .pay-cell {
        border: 2px solid var(--border);
        padding: 1rem;
        border-radius: var(--radius);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        font-weight: 800;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pay-btn input:checked+.pay-cell {
        border-color: var(--gold);
        background: var(--gold-light);
        color: var(--gold-dark);
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(184, 155, 94, 0.4);
        }

        70% {
            box-shadow: 0 0 0 15px rgba(184, 155, 94, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(184, 155, 94, 0);
        }
    }
</style>

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
                    if (newQty === 0) location.reload();
                    else {
                        qtyEl.textContent = newQty;
                        document.getElementById('orderTotal').textContent = data.total_fmt;
                    }
                }
            });
    }

    function removeItem(itemId, orderId) {
        if (!confirm('Xóa món này khỏi order?')) return;
        fetch('<?= BASE_URL ?>/orders/remove', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ item_id: itemId, order_id: orderId })
        })
            .then(r => r.json())
            .then(data => { if (data.ok) location.reload(); });
    }

    function confirmClose(tableId, orderId) {
        if (!confirm('Bạn có chắc chắn muốn hủy bàn này?')) return;
        document.getElementById('isQuickCancel').value = "1";
        handleSubmitPayment({ preventDefault: () => { } });
    }

    function confirmPayment(tableId, orderId, total) {
        document.getElementById('closeTableId').value = tableId;
        document.getElementById('closeOrderId').value = orderId;
        document.getElementById('modalTotalAmount').textContent = new Intl.NumberFormat('vi-VN').format(total) + '₫';
        Aurora.openModal('modalClose');
    }

    function handleSubmitPayment(e) {
        const isQuickCancel = document.getElementById('isQuickCancel').value === "1";
        const form = document.getElementById('formCloseTable');
        const checkPaid = document.getElementById('checkPaid');
        if (!isQuickCancel && !checkPaid.checked) { alert('Vui lòng xác nhận đã nhận đủ tiền!'); return; }
        const btn = document.getElementById('btnSubmitPayment');
        btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG LƯU...';
        const params = new URLSearchParams(new FormData(form));
        params.append('ajax', '1');
        fetch('<?= BASE_URL ?>/tables/close', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: params
        })
            .then(r => r.json())
            .then(res => {
                if (res.ok) {
                    if (!isQuickCancel) window.open('<?= BASE_URL ?>/orders/print?order_id=' + params.get('order_id') + '&payment_method=' + params.get('payment_method'), '_blank');
                    location.href = '<?= BASE_URL ?>/tables';
                } else { alert(res.message); btn.disabled = false; btn.innerHTML = 'THỬ LẠI'; }
            });
    }
</script>