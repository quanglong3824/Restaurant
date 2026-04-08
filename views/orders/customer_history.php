<?php
// views/orders/customer_history.php — Customer Order History for Aurora Restaurant
// Hiển thị lịch sử order cho khách sau khi thanh toán
?>
<div class="customer-history-wrapper animate-fade-in">
    <div class="history-header-section">
        <div class="brand-logo">
            <h1 class="playfair">AURORA</h1>
            <span>LỊCH SỬ GỌI MÓN</span>
        </div>
        <div class="table-badge">
            <i class="fas <?= $isRoomService ? 'fa-bed' : 'fa-utensils' ?>"></i>
            <span><?= $isRoomService ? 'PHÒNG' : 'BÀN' ?> <?= e($table['name']) ?></span>
        </div>
    </div>

    <div class="history-content">
        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <h3>Chưa có lịch sử gọi món</h3>
                <p class="text-muted">Các đơn hàng đã thanh toán sẽ hiển thị tại đây</p>
                <button class="btn-back-menu" onclick="window.location.href='<?= BASE_URL ?>/qr/menu?table_id=<?= $table['id'] ?>&token=<?= $token ?>'">
                    <i class="fas fa-arrow-left me-2"></i> QUAY LẠI MENU
                </button>
            </div>
        <?php else: ?>
            <div class="orders-timeline">
                <?php foreach ($orders as $order): ?>
                    <div class="order-timeline-item <?= $order['id'] == $currentOrderId ? 'current' : '' ?>">
                        <div class="timeline-marker">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="order-card" onclick="showOrderDetail(<?= htmlspecialchars(json_encode($order)) ?>)">
                            <div class="order-header">
                                <div class="order-id">
                                    <span class="label">ĐƠN #<?= $order['id'] ?></span>
                                    <?php if ($order['id'] == $currentOrderId): ?>
                                        <span class="current-badge">ĐANG MỞ</span>
                                    <?php else: ?>
                                        <span class="closed-badge">ĐÃ THANH TOÁN</span>
                                    <?php endif; ?>
                                </div>
                                <div class="order-time">
                                    <i class="far fa-clock"></i>
                                    <span><?= date('H:i d/m/Y', strtotime($order['created_at'])) ?></span>
                                </div>
                            </div>
                            
                            <div class="order-items-preview">
                                <?php 
                                $items = $order['items'] ?? [];
                                $itemCount = count($items);
                                for ($i = 0; $i < min(3, $itemCount); $i++):
                                    $item = $items[$i];
                                ?>
                                    <div class="preview-item">
                                        <span class="qty"><?= $item['quantity'] ?>x</span>
                                        <span class="name"><?= e($item['item_name']) ?></span>
                                    </div>
                                <?php endfor; ?>
                                <?php if ($itemCount > 3): ?>
                                    <div class="more-items">+<?= $itemCount - 3 ?> món khác</div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="order-footer">
                                <div class="total-amount">
                                    <span class="label">Tổng cộng</span>
                                    <span class="amount"><?= formatPrice($order['total']) ?></span>
                                </div>
                                <div class="view-detail-btn">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="action-buttons">
                <button class="btn-back-menu" onclick="window.location.href='<?= BASE_URL ?>/qr/menu?table_id=<?= $table['id'] ?>&token=<?= $token ?>'">
                    <i class="fas fa-utensils me-2"></i> TIẾP TỤC ĐẶT MÓN
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- External CSS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/history.css">

<!-- Order Detail Modal -->
<div class="modal-backdrop" id="orderDetailModal">
    <div class="modal modal-bottom modal-premium">
        <div class="modal-header">
            <h3><i class="fas fa-file-invoice me-2"></i> Chi tiết đơn hàng</h3>
            <button class="modal-close" onclick="closeOrderDetail()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="orderDetailContent">
            <!-- Content will be populated by JS -->
        </div>
        <div class="modal-footer">
            <button class="btn-sheet-close" onclick="closeOrderDetail()">Đóng</button>
        </div>
    </div>
</div>


<script>
function showOrderDetail(order) {
    const content = document.getElementById('orderDetailContent');
    let html = '<div class="order-detail-items">';
    
    // Order info
    html += '<div style="background:#f8fafc;padding:12px;border-radius:12px;margin-bottom:1rem;">';
    html += '<div style="display:flex;justify-content:space-between;margin-bottom:8px;">';
    html += '<span style="color:#64748b;font-size:0.8rem;">Thời gian</span>';
    html += '<span style="font-weight:600;">' + new Date(order.created_at).toLocaleString('vi-VN') + '</span>';
    html += '</div>';
    html += '<div style="display:flex;justify-content:space-between;">';
    html += '<span style="color:#64748b;font-size:0.8rem;">Tổng cộng</span>';
    html += '<span style="font-weight:700;color:var(--gold,#d4af37);font-size:1.1rem;">' + order.total_formatted + '</span>';
    html += '</div>';
    html += '</div>';
    
    // Items
    html += '<h4 style="margin-bottom:1rem;font-size:0.9rem;color:#64748b;text-transform:uppercase;">Danh sách món</h4>';
    
    if (order.items && order.items.length > 0) {
        order.items.forEach(function(item) {
            html += '<div class="order-detail-item">';
            html += '<div class="detail-qty">' + item.quantity + '</div>';
            html += '<div class="detail-info">';
            html += '<div class="detail-name">' + item.item_name + '</div>';
            if (item.note) {
                html += '<div class="detail-note"><i class="fas fa-pen"></i> ' + item.note + '</div>';
            }
            html += '</div>';
            html += '<div class="detail-price">' + formatPrice(item.item_price * item.quantity) + '</div>';
            html += '</div>';
        });
    } else {
        html += '<p style="text-align:center;color:#94a3b8;padding:2rem;">Không có món nào</p>';
    }
    
    html += '</div>';
    content.innerHTML = html;
    
    document.getElementById('orderDetailModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeOrderDetail() {
    document.getElementById('orderDetailModal').style.display = 'none';
    document.body.style.overflow = '';
}

function formatPrice(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
}

// Animation on scroll
document.addEventListener('DOMContentLoaded', function() {
    const items = document.querySelectorAll('.order-timeline-item');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    items.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'all 0.4s ease';
        observer.observe(item);
    });
});
</script>