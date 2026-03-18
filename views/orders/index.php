<?php // views/orders/index.php — Clean White Theme Order Detail View
$draftItems = [];
$confirmedItems = [];
$hasDraft = false;
$isSplitAction = (isset($_GET['action']) && $_GET['action'] === 'split');

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
        <!-- Empty State: No Order -->
        <div class="empty-state py-5 text-center">
            <i class="fas fa-receipt fa-4x mb-3 opacity-40"></i>
            <h4 class="fw-bold mt-3">Bàn chưa có order</h4>
            <p class="text-muted small mt-2">Quay lại sơ đồ bàn để bắt đầu phục vụ.</p>
            <a href="<?= BASE_URL ?>/tables" class="btn btn-gold px-5 py-3 mt-4 shadow-lg">
                <i class="fas fa-arrow-left me-2"></i>VỀ SƠ ĐỒ BÀN
            </a>
        </div>
    <?php else: ?>

        <!-- Table Identity Card -->
        <div class="identity-card shadow-sm">
            <div class="d-flex align-items-center">
                <div class="id-icon">
                    <span class="id-num"><?= e(str_replace('Bàn ', '', $table['name'])) ?></span>
                </div>
                <div class="id-main ms-3">
                    <h2 class="mb-1"><?= e($table_display_name) ?></h2>
                    <?php if (!empty($order['note'])): ?>
                        <div class="badge bg-gold-light text-gold-dark mb-2 py-2 px-3 rounded-pill" style="font-size:0.75rem; border:1px solid var(--gold-light);">
                            <i class="fas fa-info-circle me-1"></i> <?= e($order['note']) ?>
                        </div>
                    <?php endif; ?>
                    <div class="d-flex gap-2 small">
                        <span>
                            <i class="fas fa-clock text-gold me-1"></i>
                            <?= date('H:i', strtotime($order['opened_at'])) ?>
                        </span>
                        <span class="clickable-update-guest" onclick="Aurora.openModal('modalUpdateGuestCount')" title="Cập nhật số khách">
                            <i class="fas fa-user-friends text-gold me-1"></i>
                            <span id="displayGuestCount"><?= $order['guest_count'] ?> khách</span>
                            <i class="fas fa-edit ms-1 opacity-50"></i>
                        </span>
                        <span>
                            <i class="fas fa-user-circle text-gold me-1"></i>
                            <?= e($order['waiter_name']) ?>
                        </span>
                    </div>
                </div>
                <div class="id-actions ms-auto">
                    <a href="<?= BASE_URL ?>/menu?table_id=<?= $table['id'] ?>&order_id=<?= $order['id'] ?>"
                        class="btn btn-gold btn-sm">
                        <i class="fas fa-plus me-1"></i> THÊM MÓN
                    </a>
                </div>
            </div>
        </div>

        <!-- Merge Suggestion Alert -->
        <?php if (!empty($mergeSuggestion)): ?>
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle fa-lg"></i>
                <div><?= $mergeSuggestion ?></div>
                <button type="button" class="btn btn-sm btn-ghost"
                    onclick="Aurora.openModal('modalMergeAreaFromOrder')">
                    <i class="fas fa-object-group me-1"></i> Ghép bàn
                </button>
            </div>
        <?php endif; ?>

        <!-- Order Items Stream -->
        <div class="order-stream">
            <?php if ($isSplitAction): ?>
                <div class="alert alert-info py-3 mb-4 shadow-sm border-0" style="border-radius: 12px; background: #e0f2fe; color: #0369a1;">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-cut fa-2x opacity-50"></i>
                        <div>
                            <h5 class="fw-bold mb-1">CHẾ ĐỘ TÁCH BÀN / CHUYỂN MÓN</h5>
                            <p class="small mb-0">Vui lòng chọn các món muốn tách sang bàn mới hoặc chuyển sang bàn khác.</p>
                        </div>
                        <a href="<?= BASE_URL ?>/orders?table_id=<?= $table['id'] ?>&order_id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-secondary ms-auto">
                            HỦY
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Confirmed Items Section -->
            <?php if (!empty($confirmedItems)): ?>
                <div class="section-label">
                    <i class="fas fa-check-circle me-1"></i> ĐÃ XÁC NHẬN
                </div>
                <div class="items-grid-confirmed">
                    <?php
                    // Group confirmed items by set
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
                            <div class="set-label">
                                <span class="badge-gold">
                                    <i class="fas fa-layer-group me-1"></i> <?= e($setNote) ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($itemsInSet as $item): ?>
                            <div class="item-plate plate-confirmed <?= $isSplitAction ? 'split-selectable' : '' ?>" 
                                 onclick="<?= $isSplitAction ? 'toggleSplitItem(' . $item['id'] . ')' : '' ?>">
                                <?php if ($isSplitAction): ?>
                                    <div class="split-checkbox">
                                        <input type="checkbox" name="split_items[]" value="<?= $item['id'] ?>" id="chk-<?= $item['id'] ?>" onclick="event.stopPropagation(); updateSplitCount();">
                                    </div>
                                <?php endif; ?>
                                <div class="plate-info">
                                    <div class="plate-name"><?= e($item['item_name']) ?></div>
                                    <?php if ($item['note'] && !preg_match('/^Set:\s*.+$/', $item['note'])): ?>
                                        <div class="plate-note">
                                            <i class="fas fa-comment-dots me-1"></i> <?= e($item['note']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="plate-qty">x<?= $item['quantity'] ?></div>
                                <div class="plate-price"><?= formatPrice($item['item_price'] * $item['quantity']) ?></div>
                                <div class="plate-status">
                                    <i class="fas fa-check-circle" title="Đã xác nhận"></i>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Draft Items Section -->
            <?php if (!empty($draftItems)): ?>
                <div class="section-label text-warning mt-4">
                    <i class="fas fa-clock me-1"></i> CHỜ GỬI BẾP
                </div>
                <div class="items-grid-draft">
                    <?php
                    // Group draft items by set
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
                            <div class="set-label">
                                <span class="badge-gold">
                                    <i class="fas fa-layer-group me-1"></i> <?= e($setNote) ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($itemsInSet as $item): ?>
                            <div class="item-plate plate-draft <?= $isSplitAction ? 'split-selectable' : '' ?>"
                                 onclick="<?= $isSplitAction ? 'toggleSplitItem(' . $item['id'] . ')' : '' ?>">
                                <?php if ($isSplitAction): ?>
                                    <div class="split-checkbox">
                                        <input type="checkbox" name="split_items[]" value="<?= $item['id'] ?>" id="chk-<?= $item['id'] ?>" onclick="event.stopPropagation(); updateSplitCount();">
                                    </div>
                                <?php endif; ?>
                                <div class="plate-info">
                                    <div class="plate-name"><?= e($item['item_name']) ?></div>
                                    <?php if ($item['note'] && !preg_match('/^Set:\s*.+$/', $item['note'])): ?>
                                        <div class="plate-note">
                                            <i class="fas fa-comment-dots me-1"></i> <?= e($item['note']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if (!$isSplitAction): ?>
                                <div class="plate-controls">
                                    <button class="q-btn" onclick="changeQty(<?= $item['id'] ?>, <?= $order['id'] ?>, -1)" title="Giảm">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="q-val" id="qty-<?= $item['id'] ?>"><?= $item['quantity'] ?></span>
                                    <button class="q-btn" onclick="changeQty(<?= $item['id'] ?>, <?= $order['id'] ?>, 1)" title="Tăng">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <?php else: ?>
                                    <div class="plate-qty">x<?= $item['quantity'] ?></div>
                                <?php endif; ?>
                                <div class="plate-price-total"><?= formatPrice($item['item_price'] * $item['quantity']) ?></div>
                                <?php if (!$isSplitAction): ?>
                                <button class="plate-del" onclick="removeItem(<?= $item['id'] ?>, <?= $order['id'] ?>)" title="Xóa món">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Empty Items State -->
            <?php if (empty($items)): ?>
                <div class="card text-center py-5 opacity-40 border-dashed">
                    <i class="fas fa-utensils fa-3x mb-3"></i>
                    <p class="fw-bold">Chưa chọn món nào</p>
                    <a href="<?= BASE_URL ?>/menu?table_id=<?= $table['id'] ?>&order_id=<?= $order['id'] ?>"
                        class="btn btn-gold mt-3">
                        <i class="fas fa-plus me-1"></i> CHỌN MÓN NGAY
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Floating Bill Bar -->
        <div class="bill-dock">
            <div class="dock-summary">
                <?php if ($isSplitAction): ?>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="dock-label">ĐÃ CHỌN ĐỂ TÁCH</div>
                            <div class="dock-amount" id="splitCount">0 món</div>
                        </div>
                        <button type="button" class="btn btn-gold w-100 py-3 shadow-lg" onclick="openConfirmSplitModal()">
                            <i class="fas fa-cut me-2"></i> XÁC NHẬN TÁCH MÓN
                        </button>
                    </div>
                <?php else: ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="dock-label">TỔNG TẠM TÍNH</div>
                            <div class="dock-amount" id="orderTotal"><?= formatPrice($total) ?></div>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">Đã bao gồm VAT</div>
                        </div>
                    </div>

                    <div class="actions-container">
                        <?php if ($hasDraft): ?>
                            <form method="POST" action="<?= BASE_URL ?>/orders/confirm">
                                <input type="hidden" name="table_id" value="<?= $table['id'] ?>">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <button type="submit" class="btn btn-gold w-100 py-3 shadow-lg pulse-animation">
                                    <i class="fas fa-concierge-bell me-2"></i> XÁC NHẬN ORDER
                                </button>
                            </form>
                        <?php endif; ?>

                        <div class="d-flex gap-2">
                            <?php if ($total > 0): ?>
                                <button class="btn btn-success-luxury w-100 py-3"
                                    onclick="confirmPayment(<?= $table['id'] ?>, <?= $order['id'] ?>, <?= $total ?>)">
                                    <i class="fas fa-credit-card me-2"></i> THANH TOÁN
                                </button>
                                <a href="<?= BASE_URL ?>/orders/print?order_id=<?= $order['id'] ?>" target="_blank"
                                    class="btn btn-ghost py-3" style="min-width: 120px;">
                                    <i class="fas fa-print me-1"></i> IN BILL
                                </a>
                            <?php else: ?>
                                <button class="btn btn-outline-danger w-100 py-3"
                                    onclick="confirmClose(<?= $table['id'] ?>, <?= $order['id'] ?>)">
                                    <i class="fas fa-door-closed me-2"></i> ĐÓNG BÀN
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($total > 0): ?>
                            <div class="mt-2 text-center">
                                <a href="<?= BASE_URL ?>/orders?table_id=<?= $table['id'] ?>&order_id=<?= $order['id'] ?>&action=split" class="text-gold small fw-bold" style="text-decoration: none;">
                                    <i class="fas fa-cut"></i> TÁCH BÀN / CHUYỂN MÓN
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php endif; ?>
</div>

<!-- ==================== MODALS ==================== -->

<!-- Modal: Payment -->
<div class="modal-backdrop" id="modalClose">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-check-circle me-2"></i> THANH TOÁN</h3>
            <button class="modal-close" data-modal-close type="button">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="paymentSummary" class="text-center mb-4">
                <div class="text-muted small fw-bold text-uppercase">Cần thanh toán</div>
                <div class="display-5 fw-bold text-gold py-2" id="modalTotalAmount"><?= formatPrice($total) ?></div>
            </div>

            <form method="POST" action="<?= BASE_URL ?>/tables/close" id="formCloseTable">
                <input type="hidden" name="table_id" id="closeTableId">
                <input type="hidden" name="order_id" id="closeOrderId">
                <input type="hidden" id="isQuickCancel" value="0">

                <div class="form-group">
                    <label class="form-label">PHƯƠNG THỨC THANH TOÁN</label>
                    <div class="payment-grid mb-4">
                        <label class="pay-btn">
                            <input type="radio" name="payment_method" value="cash" checked>
                            <span class="pay-cell">
                                <i class="fas fa-money-bill-wave"></i>
                                <span class="ms-1">TIỀN MẶT</span>
                            </span>
                        </label>
                        <label class="pay-btn">
                            <input type="radio" name="payment_method" value="transfer">
                            <span class="pay-cell">
                                <i class="fas fa-university"></i>
                                <span class="ms-1">CHUYỂN KHOẢN</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="payment-options-grid mb-4">
                    <div class="payment-option-card" id="cardPaid" onclick="togglePaymentCheck('checkPaid')">
                        <div class="option-check-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="option-text">
                            <strong>XÁC NHẬN NHẬN TIỀN</strong>
                            <span>Đã nhận đủ tiền từ khách</span>
                        </div>
                        <input type="checkbox" id="checkPaid" required style="display:none">
                    </div>

                    <div class="payment-option-card" id="cardPrint" onclick="togglePaymentCheck('checkPrintBill')">
                        <div class="option-check-icon">
                            <i class="fas fa-print"></i>
                        </div>
                        <div class="option-text">
                            <strong>IN HOÁ ĐƠN</strong>
                            <span>Xem hoá đơn sau thanh toán</span>
                        </div>
                        <input type="checkbox" id="checkPrintBill" style="display:none">
                    </div>
                </div>

                <style>
                    .payment-options-grid {
                        display: grid;
                        grid-template-columns: 1fr;
                        gap: 0.75rem;
                    }
                    .payment-option-card {
                        background: #f8fafc;
                        border: 2px solid #e2e8f0;
                        border-radius: 16px;
                        padding: 1.25rem;
                        display: flex;
                        align-items: center;
                        gap: 1rem;
                        cursor: pointer;
                        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                        position: relative;
                        overflow: hidden;
                    }
                    .payment-option-card:hover {
                        border-color: #cbd5e1;
                        background: #f1f5f9;
                    }
                    .option-check-icon {
                        width: 44px;
                        height: 44px;
                        background: #fff;
                        border: 1px solid #e2e8f0;
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1.25rem;
                        color: #94a3b8;
                        transition: all 0.3s;
                        flex-shrink: 0;
                    }
                    .option-text {
                        display: flex;
                        flex-direction: column;
                        gap: 2px;
                    }
                    .option-text strong {
                        font-size: 0.85rem;
                        font-weight: 800;
                        color: #334155;
                        letter-spacing: 0.5px;
                    }
                    .option-text span {
                        font-size: 0.75rem;
                        color: #64748b;
                    }

                    /* Active States */
                    .payment-option-card.active {
                        border-color: var(--gold);
                        background: rgba(212, 175, 55, 0.05);
                    }
                    .payment-option-card.active .option-check-icon {
                        background: var(--gold);
                        color: #fff;
                        border-color: var(--gold);
                        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
                        transform: scale(1.05);
                    }
                    .payment-option-card.active .option-text strong {
                        color: var(--gold-dark);
                    }

                    /* Paid confirmation specifically */
                    #cardPaid.active {
                        background: #f0fdf4;
                        border-color: #22c55e;
                    }
                    #cardPaid.active .option-check-icon {
                        background: #22c55e;
                        border-color: #22c55e;
                        color: #fff;
                        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
                    }
                    #cardPaid.active .option-text strong {
                        color: #15803d;
                    }

                    /* Print button specific active state as requested (bright) */
                    #cardPrint.active {
                        background: rgba(212, 175, 55, 0.1);
                        border-color: var(--gold);
                    }
                </style>

                <script>
                    function togglePaymentCheck(id) {
                        const checkbox = document.getElementById(id);
                        const card = id === 'checkPaid' ? document.getElementById('cardPaid') : document.getElementById('cardPrint');
                        
                        checkbox.checked = !checkbox.checked;
                        
                        if (checkbox.checked) {
                            card.classList.add('active');
                            // Add a little haptic-like scale effect
                            card.style.transform = 'scale(0.98)';
                            setTimeout(() => card.style.transform = 'scale(1)', 100);
                        } else {
                            card.classList.remove('active');
                        }
                    }
                </script>

                <button type="button" id="btnSubmitPayment" class="btn btn-gold w-100 py-3 shadow-lg"
                    onclick="handleSubmitPayment(event)">
                    <i class="fas fa-check-circle me-2"></i> HOÀN TẤT THANH TOÁN
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Update Guest Count -->
<div class="modal-backdrop" id="modalUpdateGuestCount">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-friends me-2"></i> Cập nhật số khách</h3>
            <button class="modal-close" data-modal-close type="button">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="formUpdateGuestCount" class="modal-body">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?? '' ?>">
            <p class="small text-muted mb-3">Chọn hoặc nhập số lượng khách thực tế tại bàn.</p>
            
            <div class="form-group mb-4">
                <label class="form-label">SỐ LƯỢNG KHÁCH</label>
                <div class="guest-selector-grid">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <label class="guest-option">
                            <input type="radio" name="guest_count_radio" value="<?= $i ?>" 
                                <?= $i == ($order['guest_count'] ?? 1) ? 'checked' : '' ?>>
                            <span class="guest-option-span"><?= $i ?></span>
                        </label>
                    <?php endfor; ?>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <label class="me-3 small fw-bold">Hoặc nhập:</label>
                    <input type="number" name="guest_count_input" class="form-control flex-grow-1" 
                        min="1" value="<?= $order['guest_count'] ?? 1 ?>">
                </div>
            </div>
            
            <button type="button" onclick="submitGuestCountUpdate()" class="btn btn-gold w-100 py-3 fw-bold">
                <i class="fas fa-save me-2"></i> LƯU THAY ĐỔI
            </button>
        </form>
    </div>
</div>

<!-- Modal: Confirm Split -->
<div class="modal-backdrop" id="modalConfirmSplit">
    <div class="modal modal-premium" style="max-width: 450px;">
        <div class="modal-header">
            <h3><i class="fas fa-cut me-2"></i> XÁC NHẬN TÁCH</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="mb-4 text-center">
                <div class="small text-muted mb-1">MÓN ĐÃ CHỌN</div>
                <div class="fw-bold h4" id="modalSplitCountText">0 món</div>
            </div>

            <div class="form-group mb-4">
                <label class="form-label">CHỌN BÀN ĐÍCH</label>
                <select id="splitTargetTableId" class="form-control" required>
                    <option value="">-- Chọn bàn trống --</option>
                    <?php foreach ($grouped as $area => $tbls): ?>
                        <optgroup label="<?= e($area) ?>">
                            <?php foreach ($tbls as $t): ?>
                                <?php if ($t['status'] === 'available' && empty($t['parent_id']) && $t['id'] != ($table['id'] ?? 0)): ?>
                                    <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (Sức chứa: <?= $t['capacity'] ?>)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                    <option value="0">-- Tách ra bàn mới (tự động) --</option>
                </select>
            </div>

            <div class="form-group mb-4">
                <label class="form-label">SỐ LƯỢNG KHÁCH (BÀN MỚI)</label>
                <div class="guest-selector-grid">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <label class="guest-option">
                            <input type="radio" name="split_guest_count" value="<?= $i ?>" <?= $i == 2 ? 'checked' : '' ?>>
                            <span class="guest-option-span"><?= $i ?></span>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="button" class="btn btn-gold py-3 fw-bold" onclick="submitSplitOrder()">XÁC NHẬN TÁCH BÀN</button>
                <button type="button" class="btn btn-ghost" data-modal-close>HỦY</button>
            </div>
        </div>
    </div>
</div>

<style>
    .split-selectable { cursor: pointer; position: relative; }
    .split-selectable:hover { background: #f0f9ff !important; border-color: #7dd3fc !important; }
    .split-checkbox { padding-right: 15px; display: flex; align-items: center; }
    .split-checkbox input { width: 22px; height: 22px; cursor: pointer; accent-color: var(--gold); }
    .item-plate.selected-for-split { background: #fefce8 !important; border-color: #fde047 !important; }
</style>

<!-- Modal: Merge Tables -->
<div class="modal-backdrop" id="modalMergeAreaFromOrder">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-object-group me-2"></i> Ghép bàn</h3>
            <button class="modal-close" data-modal-close type="button">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="targetForm" method="POST" action="<?= BASE_URL ?>/tables/merge" class="modal-body">
            <input type="hidden" name="parent_id" value="<?= $table['id'] ?? '' ?>">
            <input type="hidden" name="redirect"
                value="/orders?table_id=<?= $table['id'] ?? '' ?>&order_id=<?= $order['id'] ?? '' ?>">
            
            <p class="merge-message">
                Chọn bàn trống cùng khu vực để ghép với <strong><?= e($table['name']) ?></strong>:
            </p>
            
            <div class="form-group mb-4">
                <label class="form-label">CHỌN BÀN TRỐNG</label>
                <select name="child_id" class="form-control" required>
                    <option value="">-- Chọn bàn --</option>
                    <?php
                    if (!empty($grouped)):
                        $currentArea = $table['area'] ?? '';
                        if (isset($grouped[$currentArea])):
                            $tables = $grouped[$currentArea];
                            foreach ($tables as $t):
                                if ($t['status'] === 'available' && empty($t['parent_id']) && $t['id'] != ($table['id'] ?? 0)):
                                    ?>
                                    <option value="<?= $t['id'] ?>">
                                        <?= e($t['name']) ?> (Sức chứa: <?= $t['capacity'] ?> ghế)
                                    </option>
                                    <?php
                                endif;
                            endforeach;
                        endif;
                    endif;
                    ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-gold w-100 py-3 fw-bold">
                <i class="fas fa-link me-2"></i> GHÉP BÀN NGAY
            </button>
        </form>
    </div>
</div>

<!-- External CSS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/index.css">

<!-- Config -->
<script>
const ORDERS_CONFIG = {
    baseUrl: '<?= BASE_URL ?>',
    tableId: <?= $table['id'] ?? 0 ?>,
    orderId: <?= $order['id'] ?? 0 ?>
};
</script>

<!-- External JavaScript -->
<script src="<?= BASE_URL ?>/public/js/orders/index.js"></script>
