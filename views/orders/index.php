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
                    <span class="id-num"
                        style="font-size: <?= strlen($table['name']) > 5 ? '1rem' : '1.75rem' ?>"><?= e(str_replace('Bàn ', '', $table['name'])) ?></span>
                </div>
                <div class="id-main">
                    <h2 class="playfair mb-1"><?= e($table_display_name) ?></h2>
                    <div class="d-flex gap-3 small text-muted">
                        <span><i class="fas fa-clock text-gold me-1"></i>
                            <?= date('H:i', strtotime($order['opened_at'])) ?></span>
                        <span onclick="Aurora.openModal('modalUpdateGuestCount')"
                            style="cursor: pointer; position: relative; transition: all 0.2s;"
                            onmouseover="this.style.color='var(--gold)'; this.style.transform='scale(1.05)'"
                            onmouseout="this.style.color=''; this.style.transform='none'"
                            title="Bấm để cập nhật số lượng khách">
                            <i class="fas fa-user-friends text-gold me-1"></i>
                            <span id="displayGuestCount"><?= $order['guest_count'] ?> khách</span>
                            <i class="fas fa-edit ms-1" style="font-size: 0.75rem; opacity: 0.7; color: var(--gold);"></i>
                        </span>
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

        <?php if (!empty($mergeSuggestion)): ?>
            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert"
                style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba;">
                <i class="fas fa-exclamation-triangle me-3 fa-2x"></i>
                <div>
                    <?= $mergeSuggestion ?>
                </div>
                <button type="button" class="btn btn-sm btn-outline-warning ms-auto"
                    onclick="Aurora.openModal('modalMergeAreaFromOrder')">Ghép bàn ngay</button>
            </div>
        <?php endif; ?>

        <!-- Items Stream -->
        <div class="order-stream">

            <!-- Confirmed Section -->
            <?php if (!empty($confirmedItems)): ?>
                <div class="section-label mb-3 mt-4">ĐÃ GỬI BẾP (XÁC NHẬN)</div>
                <div class="items-grid-confirmed">
                    <?php
                    // Group items by set/note to show sets together
                    $groupedConfirmedItems = [];
                    foreach ($confirmedItems as $item) {
                        $setNote = '';
                        if (preg_match('/^Set:\s*(.+)$/', $item['note'] ?? '', $matches)) {
                            $setNote = $matches[1];
                        }
                        $groupedConfirmedItems[$setNote][] = $item;
                    }

                    foreach ($groupedConfirmedItems as $setNote => $itemsInSet):
                        ?>
                        <?php if ($setNote): ?>
                            <div class="set-label mb-2">
                                <span class="badge badge-gold"><i class="fas fa-layer-group me-1"></i> Combo: <?= e($setNote) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($itemsInSet as $item): ?>
                            <div class="item-plate plate-confirmed">
                                <div class="plate-info">
                                    <div class="plate-name"><?= e($item['item_name']) ?></div>
                                    <?php if ($item['note'] && !preg_match('/^Set:\s*.+$/', $item['note'])): ?>
                                        <div class="plate-note"><i class="fas fa-comment-dots me-1"></i> <?= e($item['note']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="plate-qty">x<?= $item['quantity'] ?></div>
                                <div class="plate-price"><?= formatPrice($item['item_price'] * $item['quantity']) ?></div>
                                <div class="plate-status"><i class="fas fa-check-circle"></i></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Draft Section -->
            <?php if (!empty($draftItems)): ?>
                <div class="section-label mb-3 mt-4 text-warning">MÓN ĐANG CHỜ GỬI BẾP</div>
                <div class="items-grid-draft">
                    <?php
                    // Group draft items by set/note to show sets together
                    $groupedDraftItems = [];
                    foreach ($draftItems as $item) {
                        $setNote = '';
                        if (preg_match('/^Set:\s*(.+)$/', $item['note'] ?? '', $matches)) {
                            $setNote = $matches[1];
                        }
                        $groupedDraftItems[$setNote][] = $item;
                    }

                    foreach ($groupedDraftItems as $setNote => $itemsInSet):
                        ?>
                        <?php if ($setNote): ?>
                            <div class="set-label mb-2">
                                <span class="badge badge-gold"><i class="fas fa-layer-group me-1"></i> Combo: <?= e($setNote) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($itemsInSet as $item): ?>
                            <div class="item-plate plate-draft mb-3 glass-card">
                                <div class="plate-info">
                                    <div class="plate-name"><?= e($item['item_name']) ?></div>
                                    <?php if ($item['note'] && !preg_match('/^Set:\s*.+$/', $item['note'])): ?>
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

                <div class="actions-container">
                    <?php if ($hasDraft): ?>
                        <form method="POST" action="<?= BASE_URL ?>/orders/confirm">
                            <input type="hidden" name="table_id" value="<?= $table['id'] ?>">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn btn-gold w-100 py-3 shadow-lg pulse-animation mb-3">
                                <i class="fas fa-concierge-bell me-2"></i> XÁC NHẬN GỬI BẾP NGAY
                            </button>
                        </form>
                    <?php endif; ?>

                    <div class="d-grid gap-3">
                        <?php if ($total > 0): ?>
                            <button class="btn btn-success-luxury w-100 py-3"
                                onclick="confirmPayment(<?= $table['id'] ?>, <?= $order['id'] ?>, <?= $total ?>)">
                                <i class="fas fa-credit-card me-2"></i> THANH TOÁN & IN HÓA ĐƠN
                            </button>
                            <a href="<?= BASE_URL ?>/orders/print?order_id=<?= $order['id'] ?>" target="_blank"
                                class="btn btn-ghost w-100 py-3">
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

    /* Improved layout consistency with the rest of the application */
    .page-content {
        padding-top: 1rem;
    }

    .order-stream {
        margin-bottom: 1rem;
    }

    .section-label {
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--text-muted);
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
    }

    .items-grid-confirmed,
    .items-grid-draft {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .glass-card {
        background: var(--surface-glass);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    .border-dashed {
        border: 2px dashed var(--border);
    }

    .fw-bold {
        font-weight: 700 !important;
    }

    .fw-800 {
        font-weight: 800 !important;
    }

    .text-warning {
        color: var(--warning) !important;
    }

    .opacity-10 {
        opacity: 0.1;
    }

    .opacity-40 {
        opacity: 0.4;
    }

    .d-flex {
        display: flex;
    }

    .flex-column {
        flex-direction: column;
    }

    .align-items-center {
        align-items: center;
    }

    .justify-content-center {
        justify-content: center;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .gap-0 {
        gap: 0;
    }

    .gap-1 {
        gap: 0.25rem;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .gap-3 {
        gap: 1rem;
    }

    .gap-4 {
        gap: 1.5rem;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .mb-1 {
        margin-bottom: 0.25rem;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mt-1 {
        margin-top: 0.25rem;
    }

    .mt-2 {
        margin-top: 0.5rem;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .me-1 {
        margin-right: 0.25rem;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .ms-auto {
        margin-left: auto;
    }

    .p-3 {
        padding: 1rem;
    }

    .p-4 {
        padding: 1.5rem;
    }

    .py-2 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .py-3 {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .py-5 {
        padding-top: 2rem;
        padding-bottom: 2rem;
    }

    .text-center {
        text-align: center;
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    .text-gold {
        color: var(--gold) !important;
    }

    .small {
        font-size: 0.875rem;
    }

    .display-5 {
        font-size: 2.5rem;
        font-weight: 900;
    }

    .rounded-pill {
        border-radius: 50px;
    }

    .bg-light {
        background-color: var(--bg);
    }

    .shadow-sm {
        box-shadow: var(--shadow);
    }

    .shadow-lg {
        box-shadow: var(--shadow-high);
    }

    .card {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 1.25rem;
        box-shadow: var(--shadow);
        border: 1px solid transparent;
        transition: var(--transition);
    }

    .card:hover {
        border-color: var(--border-gold);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-weight: 700;
        padding: 0.8rem 1.5rem;
        border-radius: var(--radius-sm);
        transition: all 0.2s;
        border: none;
        font-size: 0.95rem;
        cursor: pointer;
    }

    .btn-gold {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        color: white;
        box-shadow: 0 4px 15px var(--gold-glow);
    }

    .btn-gold:active {
        transform: scale(0.96);
    }

    .btn-ghost {
        background: var(--bg);
        color: var(--text-muted);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn-outline-danger {
        background: transparent;
        border: 2px solid var(--danger);
        color: var(--danger);
    }

    .btn-outline-danger:hover {
        background: var(--danger);
        color: white;
    }

    .form-control {
        width: 100%;
        padding: 0.85rem 1rem;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--text);
        font-family: inherit;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-glow);
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .playfair {
        font-family: 'Playfair Display', serif;
    }

    .fw-bold {
        font-weight: 800;
    }

    .fw-800 {
        font-weight: 800;
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    .letter-spacing {
        letter-spacing: 1px;
    }

    .opacity-40 {
        opacity: 0.4;
    }

    .border-dashed {
        border: 2px dashed var(--border);
    }

    /* Remove underlines from links and icons */
    a,
    a:link,
    a:visited,
    a:hover,
    a:active {
        text-decoration: none;
    }

    i,
    .fas,
    .far,
    .fab {
        text-decoration: none;
    }

    /* Improved button layout */
    .actions-container {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .btn {
        margin-bottom: 0.5rem;
    }

    .btn:last-child {
        margin-bottom: 0;
    }

    .d-grid {
        display: grid;
        gap: 0.75rem;
    }

    /* Badge styles for set labels */
    .badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.375rem;
    }

    .badge-gold {
        background-color: var(--gold-light);
        color: var(--gold-dark);
        border: 1px solid var(--border-gold);
    }

    .set-label {
        padding: 0.5rem 1rem;
        background-color: var(--gold-light);
        border-left: 3px solid var(--gold);
        border-radius: 0 var(--radius) var(--radius) 0;
    }
</style>

<!-- Modal: Cập nhật số khách -->
<div class="modal-backdrop" id="modalUpdateGuestCount">
    <div class="modal modal-premium" style="max-width: 400px;">
        <div class="modal-header">
            <h3 class="playfair"><i class="fas fa-user-edit me-2"></i> Cập nhật số khách</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" id="formUpdateGuestCount" class="modal-body">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?? '' ?>">
            <p class="small text-muted mb-3">Vui lòng chọn hoặc nhập số lượng thực tế khách đang có tại bàn.</p>
            <div class="form-group mb-4">
                <label class="form-label text-gold small fw-bold text-uppercase">Số lượng khách hàng</label>
                <div class="guest-selector-grid"
                    style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 1rem;">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <label class="guest-option">
                            <input type="radio" name="guest_count_radio" value="<?= $i ?>" <?= $i == ($order['guest_count'] ?? 1) ? 'checked' : '' ?>>
                            <span
                                style="display: flex; align-items: center; justify-content: center; height: 48px; background: #f1f5f9; border-radius: var(--radius-sm); font-weight: 800; cursor: pointer; transition: all 0.2s;"><?= $i ?></span>
                        </label>
                    <?php endfor; ?>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <label class="me-3 small fw-bold">Hoặc nhập thủ công:</label>
                    <input type="number" name="guest_count_input" class="form-control flex-grow-1" min="1"
                        value="<?= $order['guest_count'] ?? 1 ?>">
                </div>
            </div>
            <button type="button" onclick="submitGuestCountUpdate()" class="btn btn-gold w-100 py-3 fw-bold">
                <i class="fas fa-save me-2"></i> LƯU CẬP NHẬT
            </button>
        </form>
    </div>
</div>

<style>
    /* Add inline styles for the radio buttons since it's locally used */
    #modalUpdateGuestCount .guest-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    #modalUpdateGuestCount .guest-option input[type="radio"]:checked+span {
        background: var(--gold) !important;
        color: white;
        transform: scale(1.05);
    }
</style>

<!-- Modal: Chọn bàn để Ghép -->
<div class="modal-backdrop" id="modalMergeAreaFromOrder">
    <div class="modal modal-premium" style="max-width: 400px;">
        <div class="modal-header">
            <h3 class="playfair" id="targetModalTitle"><i class="fas fa-link me-2"></i> Ghép thêm bàn</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form id="targetForm" method="POST" action="<?= BASE_URL ?>/tables/merge" class="modal-body">
            <input type="hidden" name="parent_id" value="<?= $table['id'] ?? '' ?>">
            <input type="hidden" name="redirect"
                value="/orders?table_id=<?= $table['id'] ?? '' ?>&order_id=<?= $order['id'] ?? '' ?>">
            <p style="margin-bottom:1rem; font-size:0.9rem; color:var(--text-muted);">
                Chọn bàn trống cùng khu vực để phục vụ số khách lớn hơn cho
                <?= isset($table['name']) ? e($table['name']) : '' ?>:
            </p>
            <div class="form-group mb-4">
                <label class="form-label text-gold small fw-bold text-uppercase">Chọn bàn trống</label>
                <select name="child_id" class="form-control" required
                    style="padding: 0.75rem; border-color: var(--border-gold); background-color: var(--surface-1); border-radius: var(--radius-sm); font-size: 1rem;">
                    <option value="">-- Chọn một bàn đang trống --</option>
                    <?php
                    if (!empty($grouped)):
                        // Chỉ hiển thị các bàn trùng khu vực với bàn gốc
                        $currentArea = $table['area'] ?? '';
                        if (isset($grouped[$currentArea])):
                            $tables = $grouped[$currentArea];
                            foreach ($tables as $t):
                                if ($t['status'] === 'available' && empty($t['parent_id']) && $t['id'] != ($table['id'] ?? 0)):
                                    ?>
                                    <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (Sức chứa: <?= $t['capacity'] ?> ghế)</option>
                                    <?php
                                endif;
                            endforeach;
                        endif;
                    endif;
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-gold w-100 py-3 fw-bold">
                <i class="fas fa-object-group me-2"></i> TIẾN HÀNH GHÉP
            </button>
        </form>
    </div>
</div>

<script>
    function submitGuestCountUpdate() {
        const form = document.getElementById('formUpdateGuestCount');

        const formData = new FormData(form);
        let guestCount = formData.get('guest_count_input');

        if (!guestCount || guestCount === "" || parseInt(guestCount) <= 0) {
            guestCount = formData.get('guest_count_radio') || 1;
        }

        const btn = form.querySelector('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG LƯU...';

        fetch('<?= BASE_URL ?>/orders/update-guest-count', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                order_id: form.querySelector('input[name="order_id"]').value,
                guest_count: guestCount
            })
        })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(err => {
                console.error(err);
                alert('Có lỗi xảy ra!');
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
    }

    // Sync radio and input
    document.querySelectorAll('input[name="guest_count_radio"]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            document.querySelector('input[name="guest_count_input"]').value = e.target.value;
        });
    });
    document.querySelector('input[name="guest_count_input"]').addEventListener('input', (e) => {
        const val = e.target.value;
        document.querySelectorAll('input[name="guest_count_radio"]').forEach(radio => {
            if (radio.value === val) radio.checked = true;
            else radio.checked = false;
        });
    });

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

        // Directly close the table without payment processing
        fetch('<?= BASE_URL ?>/tables/close', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                table_id: tableId,
                order_id: orderId,
                ajax: '1',
                redirect_to_order: '1'  // Add flag to stay on order page
            })
        })
            .then(r => r.json())
            .then(res => {
                if (res.ok) {
                    // Redirect to the same orders page to show the empty state
                    location.href = '<?= BASE_URL ?>/orders?table_id=' + tableId + '&order_id=' + orderId;
                } else {
                    alert(res.message || 'Có lỗi xảy ra khi đóng bàn');
                }
            })
            .catch(error => {
                alert('Có lỗi xảy ra khi đóng bàn');
            });
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
        params.append('redirect_to_order', '1'); // Add flag to stay on order page
        fetch('<?= BASE_URL ?>/tables/close', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: params
        })
            .then(r => r.json())
            .then(res => {
                if (res.ok) {
                    if (!isQuickCancel) window.open('<?= BASE_URL ?>/orders/print?order_id=' + params.get('order_id') + '&payment_method=' + params.get('payment_method'), '_blank');
                    // Redirect to the same orders page to show the empty state
                    location.href = '<?= BASE_URL ?>/orders?table_id=' + params.get('table_id') + '&order_id=' + params.get('order_id');
                } else { alert(res.message); btn.disabled = false; btn.innerHTML = 'THỬ LẠI'; }
            });
    }

    // Handle the confirm order form submission via AJAX
    document.addEventListener('DOMContentLoaded', function () {
        const confirmForms = document.querySelectorAll('form[action$="/orders/confirm"]');
        confirmForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Prevent normal form submission

                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');

                // Show loading state
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ĐANG XỬ LÝ...';

                fetch(form.getAttribute('action'), {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok) {
                            // Show success message
                            showAlert(data.message || 'Đã gửi bếp thành công!', 'success');

                            // Reload the page to update the UI
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            // Show error message
                            showAlert(data.message || 'Có lỗi xảy ra!', 'danger');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Có lỗi xảy ra khi gửi bếp!', 'danger');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
            });
        });
    });

    // Function to show alerts similar to the flash messages
    function showAlert(message, type) {
        // Remove any existing alerts
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}" aria-hidden="true"></i>
            ${message}
        `;

        document.body.appendChild(alertDiv);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.style.transition = 'opacity 0.4s ease';
                alertDiv.style.opacity = '0';
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 400);
            }
        }, 3000);
    }
</script>