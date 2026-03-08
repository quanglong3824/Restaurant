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
                        <span class="clickable-update-guest" onclick="Aurora.openModal('modalUpdateGuestCount')"
                            title="Bấm để cập nhật số lượng khách">
                            <i class="fas fa-user-friends text-gold me-1"></i>
                            <span id="displayGuestCount"><?= $order['guest_count'] ?> khách</span>
                            <i class="fas fa-edit ms-1"></i>
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
                <div class="guest-selector-grid">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <label class="guest-option">
                            <input type="radio" name="guest_count_radio" value="<?= $i ?>" <?= $i == ($order['guest_count'] ?? 1) ? 'checked' : '' ?>>
                            <span class="guest-option-span"><?= $i ?></span>
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
            <p class="merge-message">
                Chọn bàn trống cùng khu vực để phục vụ số khách lớn hơn cho
                <?= isset($table['name']) ? e($table['name']) : '' ?>:
            </p>
            <div class="form-group mb-4">
                <label class="form-label text-gold small fw-bold text-uppercase">Chọn bàn trống</label>
                <select name="child_id" class="form-control" required>
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

</div> <!-- Close page-content -->

<!-- External CSS for Order Index View -->
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/index.css">

<!-- External JavaScript for Order Index View -->
<script src="<?= BASE_URL ?>/public/js/orders/index.js"></script>
