<?php 
// views/orders/status.php — Order Status for Customers
// Xác định ngôn ngữ hiện tại
$currentLang = $_COOKIE['aurora_lang'] ?? $_SESSION['lang'] ?? 'vi';
$isEn = $currentLang === 'en';

// Text translations
$t = [
    'order_sent' => $isEn ? 'Order Sent!' : 'Order đã được gửi!',
    'wait_message' => $isEn ? 'Please wait for staff confirmation and service.' : 'Vui lòng đợi nhân viên xác nhận và phục vụ.',
    'order_details' => $isEn ? 'Order Details' : 'Chi tiết Order',
    'qty' => $isEn ? 'x' : 'x',
    'total' => $isEn ? 'Total' : 'Tổng cộng',
    'add_more' => $isEn ? 'ORDER MORE' : 'GỌI THÊM MÓN',
    'check_bill' => $isEn ? 'CHECK BILL' : 'KIỂM TRA HÓA ĐƠN',
    'pending' => $isEn ? 'Pending' : 'Chờ xác nhận',
    'confirmed' => $isEn ? 'Confirmed' : 'Đã xác nhận',
    'cooking' => $isEn ? 'Cooking' : 'Đang chế biến',
    'served' => $isEn ? 'Served' : 'Đã phục vụ',
    'cancelled' => $isEn ? 'Cancelled' : 'Đã hủy',
];
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/status.css">

<div class="status-container">
    <div class="status-header">
        <div class="status-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1><?= e($t['order_sent']) ?></h1>
        <p><?= e($t['wait_message']) ?></p>
    </div>

    <div class="order-summary-card">
        <div class="card-header">
            <h3><?= e($t['order_details']) ?></h3>
            <span class="order-id">#<?= e($order['id']) ?></span>
        </div>
        <div class="card-body">
            <div class="items-list">
                <?php $total = 0; ?>
                <?php foreach ($items as $it): ?>
                    <div class="status-item-row">
                        <div class="item-main">
                            <div class="item-top-row">
                                <span class="item-qty"><?= $t['qty'] ?><?= $it['quantity'] ?></span>
                                <span class="item-name"><?= e($it['item_name']) ?></span>
                            </div>
                            <div class="item-status <?= e($it['status']) ?>">
                                <?php 
                                    echo $t[$it['status']] ?? $it['status'];
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
                    <span><?= e($t['total']) ?></span>
                    <strong><?= formatPrice($total) ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="<?= BASE_URL ?>/qr/menu?table_id=<?= $order['table_id'] ?>&token=<?= $_SESSION['qr_token'] ?? '' ?>" class="btn-gold">
            <i class="fas fa-plus-circle me-2"></i> <?= e($t['add_more']) ?>
        </a>
        <a href="<?= BASE_URL ?>/qr/menu?table_id=<?= $order['table_id'] ?>&token=<?= $_SESSION['qr_token'] ?? '' ?>&show_bill=1" class="btn-outline">
            <i class="fas fa-file-invoice-dollar me-2"></i> <?= e($t['check_bill']) ?>
        </a>
    </div>
    
    <!-- Language switcher -->
    <div class="lang-switcher">
        <button onclick="toggleStatusLang()" class="lang-btn">
            <i class="fas fa-globe"></i>
            <span><?= $isEn ? 'TIẾNG VIỆT' : 'ENGLISH' ?></span>
        </button>
    </div>
</div>

<script>
    // Configuration for customer.js on status page
    const CUSTOMER_CONFIG = {
        tableId: <?= (int)$order['table_id'] ?>,
        baseUrl: '<?= BASE_URL ?>'
    };
    
    // Language toggle function
    function toggleStatusLang() {
        const currentLang = '<?= $currentLang ?>';
        const newLang = currentLang === 'vi' ? 'en' : 'vi';
        document.cookie = 'aurora_lang=' + newLang + '; path=/; max-age=31536000';
        localStorage.setItem('aurora_lang', newLang);
        window.location.reload();
    }
</script>
<script src="<?= BASE_URL ?>/public/js/menu/customer.js?v=<?= time() ?>" defer></script>