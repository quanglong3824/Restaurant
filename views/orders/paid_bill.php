<?php // views/orders/paid_bill.php — Premium Paid Bill View (Gogi Style) ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/paid_bill.css">

<script>
const baseUrl = '<?= BASE_URL ?>';
const orderId = '<?= $order['id'] ?>';
const tableId = '<?= $table['id'] ?>';
const token = '<?= $token ?? "" ?>';
</script>

<div class="paid-bill-container">
    <div class="success-banner">
        <div class="success-check">
            <i class="fas fa-check"></i>
        </div>
        <h2 class="status-title">THANH TOÁN THÀNH CÔNG</h2>
        <p class="status-subtitle">Cảm ơn Quý khách đã ủng hộ Aurora Restaurant</p>
    </div>

    <div class="receipt-paper">
        <div class="receipt-header">
            <h1 class="brand-name">AURORA HOTEL PLAZA</h1>
            <p class="brand-address">253 Phạm Văn Thuận, KP2, P. Tam Hiệp, Biên Hòa, Đồng Nai</p>
            <div class="receipt-divider"></div>
            <div class="receipt-meta">
                <div class="meta-item">
                    <span>Bàn:</span>
                    <strong><?= e($table['name']) ?></strong>
                </div>
                <div class="meta-item">
                    <span>Mã HĐ:</span>
                    <strong>#<?= $order['id'] ?></strong>
                </div>
                <div class="meta-item">
                    <span>Thời gian:</span>
                    <strong><?= date('d/m/Y H:i', strtotime($order['closed_at'] ?? $order['updated_at'])) ?></strong>
                </div>
            </div>
        </div>

        <div class="receipt-body">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Món ăn</th>
                        <th class="text-center">SL</th>
                        <th class="text-right">T.Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($items as $item): ?>
                        <?php 
                            if ($item['status'] === 'cancelled') continue;
                            $itemTotal = $item['item_price'] * $item['quantity'];
                            $total += $itemTotal;
                        ?>
                        <tr>
                            <td>
                                <div class="item-name"><?= e($item['item_name']) ?></div>
                                <div class="item-unit-price"><?= formatPrice($item['item_price']) ?></div>
                            </td>
                            <td class="text-center"><?= $item['quantity'] ?></td>
                            <td class="text-right"><?= formatPrice($itemTotal) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="receipt-footer">
            <div class="receipt-divider"></div>
            <div class="total-row">
                <span>TỔNG CỘNG</span>
                <span class="total-amount"><?= formatPrice($total) ?></span>
            </div>
            <div class="payment-info">
                <span>Hình thức:</span>
                <strong><?= ($order['payment_method'] ?? 'cash') === 'cash' ? 'Tiền mặt' : 'Chuyển khoản' ?></strong>
            </div>
            <div class="receipt-barcode">
                <i class="fas fa-barcode"></i>
                <p>Hẹn gặp lại Quý khách!</p>
            </div>
        </div>
        
        <!-- Decorative receipt cut edge -->
        <div class="receipt-cut"></div>
    </div>

    <div class="beta-notice">
        <i class="fas fa-info-circle"></i>
        <div class="notice-content">
            <strong>KHUYẾN NGHỊ:</strong> Hệ thống đang trong giai đoạn nâng cấp thử nghiệm. Quý khách vui lòng <b>Lưu ảnh hóa đơn</b> để đối chiếu trong trường hợp cần thiết. Xin cảm ơn!
        </div>
    </div>

    <div class="action-buttons">
        <button class="btn-save-img" onclick="captureReceipt()">
            <i class="fas fa-camera"></i> LƯU ẢNH HÓA ĐƠN
        </button>
        <p class="button-tip">* Ảnh hóa đơn sẽ được lưu trực tiếp vào thư viện ảnh của bạn.</p>
        <button class="btn-new-order" onclick="startNewOrder()">
            <i class="fas fa-plus-circle"></i> TẠO LƯỢT MỚI
        </button>
        <button class="btn-exit" onclick="exitSession()">
            <i class="fas fa-sign-out-alt"></i> RỜI BÀN (THOÁT)
        </button>
    </div>
</div>

<!-- Load html2canvas for screenshot feature -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="<?= BASE_URL ?>/public/js/orders/paid_bill.js"></script>
