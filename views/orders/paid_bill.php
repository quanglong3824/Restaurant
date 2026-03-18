<?php // views/orders/paid_bill.php — Paid Bill View for Customers ?>
<div class="paid-bill-wrapper">
    <div class="bill-success-header">
        <div class="success-icon <?= ($order['payment_status'] ?? '') === 'canceled' ? 'text-warning' : '' ?>">
            <i class="fas <?= ($order['payment_status'] ?? '') === 'canceled' ? 'fa-info-circle' : 'fa-check-circle' ?>"></i>
        </div>
        <h2 class="playfair">
            <?= ($order['payment_status'] ?? '') === 'canceled' ? 'Bàn đã đóng' : 'Cảm ơn Quý khách!' ?>
        </h2>
        <p>
            <?= ($order['payment_status'] ?? '') === 'canceled' 
                ? 'Nhân viên đã đóng bàn này. Vui lòng quét lại mã QR nếu Quý khách muốn đặt món mới.' 
                : 'Hóa đơn của bạn đã được thanh toán hoàn tất.' ?>
        </p>
    </div>

    <div class="bill-details-card">
        <div class="bill-info-header">
            <div class="bill-brand">AURORA RESTAURANT</div>
            <div class="bill-meta">
                <span>Bàn: <strong><?= e($table['name']) ?></strong></span>
                <span>Ngày: <?= date('d/m/Y H:i', strtotime($order['closed_at'] ?? $order['updated_at'])) ?></span>
            </div>
            <div class="bill-id">Mã HĐ: #<?= $order['id'] ?></div>
        </div>

        <?php if (!empty($items)): ?>
        <div class="bill-items">
            <?php $total = 0; ?>
            <?php foreach ($items as $item): ?>
                <?php 
                    if ($item['status'] === 'cancelled') continue;
                    $itemTotal = $item['item_price'] * $item['quantity'];
                    $total += $itemTotal;
                ?>
                <div class="bill-row">
                    <div class="row-qty"><?= $item['quantity'] ?>x</div>
                    <div class="row-name"><?= e($item['item_name']) ?></div>
                    <div class="row-price"><?= formatPrice($itemTotal) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="bill-divider"></div>

        <div class="bill-total-section">
            <div class="total-row">
                <span>Tổng cộng</span>
                <span class="total-val"><?= formatPrice($total) ?></span>
            </div>
            <?php if (($order['payment_status'] ?? '') !== 'canceled'): ?>
            <div class="payment-method">
                <i class="fas fa-credit-card"></i> 
                Hình thức: <?= ($order['payment_method'] ?? 'cash') === 'cash' ? 'Tiền mặt' : 'Chuyển khoản' ?>
            </div>
            <?php else: ?>
            <div class="payment-method text-warning">
                <i class="fas fa-times-circle"></i> Trạng thái: Đã hủy bàn
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
            <p class="text-center text-muted my-4">Không có thông tin món ăn.</p>
        <?php endif; ?>
    </div>
<div class="bill-actions">
    <p class="text-muted small mb-4">Màn hình gọi món sẽ được mở lại khi có khách mới vào bàn.</p>
    <div class="d-grid gap-2">
        <button class="btn-gold w-100" onclick="startNewOrder()">
            <i class="fas fa-plus-circle me-2"></i> BẮT ĐẦU LƯỢT MỚI
        </button>
        <button class="btn-gold-outline w-100" onclick="window.location.reload()">
            <i class="fas fa-sync-alt me-2"></i> LÀM MỚI TRANG
        </button>
    </div>
</div>
</div>

<script>
function startNewOrder() {
if (!confirm('Bạn muốn bắt đầu lượt gọi món mới cho bàn này?')) return;

fetch('<?= BASE_URL ?>/qr/session/clear', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
})
.then(r => r.json())
.then(data => {
    if (data.success) {
        window.location.href = '<?= BASE_URL ?>/qr/menu?table_id=<?= $table['id'] ?>&token=<?= $_GET['token'] ?? "" ?>';
    }
})
.catch(err => {
    console.error('Error clearing session:', err);
    window.location.reload();
});
}
</script>

<style>
    .paid-bill-wrapper { 
        padding: 40px 20px; 
        max-width: 500px; 
        margin: 0 auto; 
        text-align: center;
        animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .bill-success-header { margin-bottom: 35px; }
    
    .success-icon { 
        font-size: 5rem; 
        color: #10b981; 
        margin-bottom: 20px;
        filter: drop-shadow(0 10px 15px rgba(16, 185, 129, 0.2));
    }
    .success-icon.text-warning { 
        color: #f59e0b; 
        filter: drop-shadow(0 10px 15px rgba(245, 158, 11, 0.2));
    }
    
    .bill-success-header h2 { 
        font-size: 2rem; 
        color: var(--text-dark); 
        margin-bottom: 10px;
        font-weight: 800;
    }
    .bill-success-header p { 
        color: var(--text-med); 
        line-height: 1.6;
        font-size: 1rem;
    }

    .bill-details-card {
        background: white;
        border-radius: 24px;
        padding: 30px;
        box-shadow: var(--shadow-lg);
        text-align: left;
        border: 1px solid var(--border);
        margin-bottom: 35px;
        position: relative;
        overflow: hidden;
    }
    
    .bill-details-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 6px;
        background: linear-gradient(90deg, var(--gold) 0%, var(--gold-dark) 100%);
    }

    .bill-info-header { text-align: center; margin-bottom: 25px; }
    .bill-brand { 
        font-family: 'Playfair Display', serif; 
        font-size: 1.4rem; 
        font-weight: 900; 
        color: var(--gold-dark); 
        letter-spacing: 3px;
        text-transform: uppercase;
    }
    .bill-meta { 
        display: flex; 
        justify-content: space-between; 
        font-size: 0.9rem; 
        color: var(--text-med); 
        margin-top: 15px;
        padding: 10px 0;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
    }
    .bill-id { 
        font-size: 0.8rem; 
        color: var(--text-light); 
        margin-top: 10px;
        font-weight: 600;
    }

    .bill-items { margin-bottom: 20px; }
    .bill-row { 
        display: flex; 
        gap: 12px; 
        padding: 14px 0; 
        border-bottom: 1px dashed #f1f5f9; 
        font-size: 1rem;
    }
    .row-qty { color: var(--gold-dark); font-weight: 800; width: 35px; }
    .row-name { flex: 1; color: var(--text-dark); font-weight: 500; }
    .row-price { font-weight: 700; color: var(--text-dark); }

    .bill-divider { 
        border-top: 2px dashed #e2e8f0; 
        margin: 20px 0; 
        position: relative;
    }
    .bill-divider::before, .bill-divider::after {
        content: '';
        position: absolute;
        width: 20px; height: 20px;
        background: var(--bg);
        border-radius: 50%;
        top: -10px;
    }
    .bill-divider::before { left: -40px; }
    .bill-divider::after { right: -40px; }

    .bill-total-section { padding-top: 5px; }
    .total-row { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 15px; 
    }
    .total-row span:first-child { 
        font-size: 1.2rem; 
        font-weight: 800; 
        color: var(--text-dark); 
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .total-val { 
        font-size: 1.6rem; 
        font-weight: 900; 
        color: var(--gold-dark); 
    }
    .payment-method { 
        font-size: 0.85rem; 
        color: var(--text-med); 
        text-align: center; 
        margin-top: 20px;
        padding: 8px 15px;
        background: #f8fafc;
        border-radius: 10px;
        display: inline-block;
        width: 100%;
    }

    .bill-actions { margin-top: 10px; }
    
    .btn-gold {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        color: white;
        border: none;
        padding: 16px 25px;
        border-radius: 16px;
        font-weight: 800;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 8px 25px var(--gold-glow);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-gold:hover { transform: translateY(-3px); box-shadow: 0 12px 30px var(--gold-glow); }
    .btn-gold:active { transform: scale(0.97); }

    .btn-gold-outline {
        background: white;
        border: 2px solid var(--gold);
        color: var(--gold-dark);
        padding: 14px 25px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-gold-outline:hover { background: var(--gold-light); }
    .btn-gold-outline:active { transform: scale(0.97); }
    
    .d-grid { display: grid; }
    .gap-2 { gap: 12px; }
</style>
