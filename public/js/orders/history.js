// ============================================================
// orders-history.js — Order History Page Logic
// ============================================================

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('modalOrderDetails');
    if (modal && modal !== e.target && !modal.contains(e.target)) {
        const backdrop = document.querySelector('.modal-backdrop.show');
        if (backdrop) Aurora.closeModal(backdrop.id);
    }
});

// Handle view details button click
document.querySelectorAll('.view-details-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const orderId = this.getAttribute('data-order-id');
        const orderData = JSON.parse(this.getAttribute('data-order-data'));

        const modalBody = document.getElementById('modalOrderBody');
        const printBtn = document.getElementById('btnPrintOrder');
        const subtitle = document.getElementById('modalOrderSubtitle');

        printBtn.href = '<?= BASE_URL ?>/orders/print?order_id=' + orderId;
        
        const date = new Date(orderData.closed_at);
        subtitle.innerHTML = `<i class="fas fa-tag me-1" style="color: var(--gold)"></i> Bàn: <strong>${orderData.table_name}</strong> | <i class="far fa-clock ms-2 me-1" style="color: var(--text-muted)"></i> ${date.toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}`;

        const html = `
            <div class="modal-pricing-header" style="background: #f8fafc; border-radius: 16px; padding: 1rem; margin-bottom: 1.5rem; border: 1px solid rgba(212, 175, 55, 0.1);">
                <div>
                    <span class="d-block text-muted small fw-800" style="font-size: 0.65rem; letter-spacing: 1px;">TỔNG THANH TOÁN</span>
                    <h2 class="m-0 fw-900 text-gold" style="font-size: 1.8rem; font-family: 'Outfit'; color: var(--gold-primary);">${formatPrice(orderData.total || 0)}</h2>
                </div>
                <div class="text-end">
                    <span class="badge" style="background: white; color: var(--text-dark); border: 1px solid #e2e8f0; padding: 0.3rem 0.8rem; border-radius: 10px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">${orderData.payment_method.toUpperCase()}</span>
                </div>
            </div>

            <table class="modal-table-premium w-100">
                <thead>
                    <tr>
                        <th width="15%">SL</th>
                        <th width="55%">MÓN ĂN</th>
                        <th width="30%" class="text-end">TỔNG</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="3" class="text-center py-4"><div class="spinner-premium" style="width: 30px; height: 30px; border-width: 2px;"></div></td></tr>
                </tbody>
            </table>

            <div class="modal-summary-footer mt-3" style="background: white; border: 1px solid #f1f5f9; border-radius: 16px; padding: 1rem;">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted fw-bold" style="font-size: 0.75rem;">Tổng số lượng</span>
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;"><?= $orderData['item_count'] ?> món</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted fw-bold" style="font-size: 0.75rem;">Nhân viên</span>
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;"><?= e($orderData['waiter_name'] ?: 'Hệ thống') ?></span>
                </div>
            </div>
        `;
        modalBody.innerHTML = html;

        fetch('<?= BASE_URL ?>/orders/get-detail/' + orderId)
            .then(res => res.json())
            .then(data => {
                if (data.ok && data.items) {
                    let itemsHtml = '';
                    data.items.forEach(item => {
                        itemsHtml += `
                            <tr>
                                <td class="fw-bold text-dark" style="font-size: 1rem; color: var(--gold-primary);">x${item.quantity}</td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;"><?= e($item['item_name']) ?></div>
                                    <div class="text-muted small" style="font-size: 0.8rem;"><?= formatPrice($item['price']) ?></div>
                                </td>
                                <td class="text-end fw-bold text-gold" style="font-size: 1rem; color: var(--gold-primary);"><?= formatPrice($item['subtotal']) ?></td>
                            </tr>
                        `;
                    });
                    document.querySelector('.modal-table-premium tbody').innerHTML = itemsHtml;
                }
            })
            .catch(err => console.error('Lỗi tải chi tiết hóa đơn:', err));

        Aurora.openModal('modalOrderDetails');
    });
});

function setFilter(type) {
    document.getElementById('filter_type').value = type;
    document.getElementById('historyFilterForm').submit();
}

function formatPrice(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}
