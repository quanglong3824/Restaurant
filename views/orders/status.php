<?php // views/orders/status.php — Order Status for Customers ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/status.css">

<div class="status-container">
    <div class="status-header">
        <div class="status-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Order đã được gửi!</h1>
        <p>Vui lòng đợi nhân viên xác nhận và phục vụ.</p>
    </div>

    <div class="order-summary-card">
        <div class="card-header">
            <h3>Chi tiết Order</h3>
            <span class="order-id">#<?= e($order['id']) ?></span>
        </div>
        <div class="card-body">
            <div class="items-list">
                <?php $total = 0; ?>
                <?php foreach ($items as $it): ?>
                    <div class="status-item-row">
                        <div class="item-info">
                            <span class="item-qty">x<?= $it['quantity'] ?></span>
                            <span class="item-name"><?= e($it['item_name']) ?></span>
                            <div class="item-status <?= e($it['status']) ?>">
                                <?php 
                                    $statusMap = [
                                        'pending' => 'Chờ xác nhận',
                                        'confirmed' => 'Đã xác nhận',
                                        'cooking' => 'Đang chế biến',
                                        'served' => 'Đã phục vụ',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                    echo $statusMap[$it['status']] ?? $it['status'];
                                ?>
                            </div>
                        </div>
                        <div class="item-price"><?= formatPrice($it['item_price'] * $it['quantity']) ?></div>
                    </div>
                    <?php if ($it['status'] !== 'cancelled') $total += $it['item_price'] * $it['quantity']; ?>
                <?php endforeach; ?>
            </div>
            <div class="summary-footer">
                <div class="total-row">
                    <span>Tổng cộng</span>
                    <strong><?= formatPrice($total) ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="<?= BASE_URL ?>/qr/menu?table_id=<?= $order['table_id'] ?>&token=<?= $_SESSION['qr_token'] ?? '' ?>" class="btn-gold">
            <i class="fas fa-plus me-2"></i> GỌI THÊM MÓN
        </a>
        <a href="<?= BASE_URL ?>/qr/menu?table_id=<?= $order['table_id'] ?>&token=<?= $_SESSION['qr_token'] ?? '' ?>&show_bill=1" class="btn-outline">
            <i class="fas fa-file-invoice-dollar me-2"></i> KIỂM TRA HÓA ĐƠN
        </a>
    </div>
</div>

<script>
    // Configuration for customer.js on status page
    const CUSTOMER_CONFIG = {
        tableId: <?= (int)$order['table_id'] ?>,
        baseUrl: '<?= BASE_URL ?>'
    };
</script>
<script src="<?= BASE_URL ?>/public/js/menu/customer.js?v=<?= time() ?>" defer></script>
