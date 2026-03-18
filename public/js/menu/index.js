// ============================================================
// menu-index.js — Digital Menu Logic (Aurora Restaurant)
// ============================================================

let currentItem = null;

// Chuyển đổi trạng thái đóng/mở giỏ hàng trên Mobile
function toggleMobileCart() {
    if (window.innerWidth > 900) return;
    const col = document.getElementById('posCartCol');
    const chevron = document.getElementById('cartChevron');
    col.classList.toggle('is-open');
    if (chevron) {
        chevron.classList.toggle('fa-chevron-up');
        chevron.classList.toggle('fa-chevron-down');
    }
}

// Xử lý chọn bàn trực tiếp trong giỏ hàng
function handleTableSelect(tableId) {
    if (!tableId) return;
    window.location.href = `${MENU_CONFIG.baseUrl}/menu?table_id=${tableId}`;
}

// Định dạng tiền tệ VND
function formatMoney(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
}

// Cập nhật giao diện giỏ hàng sau khi thêm/sửa món
function updateCartUI(data) {
    if (!data.ok) return;
    const body = document.querySelector('.cart-body');
    const totalEl = document.getElementById('orderTotal');
    const btnContainer = document.getElementById('cartActionBtn');

    if (totalEl) totalEl.textContent = data.total_fmt;

    if (body) {
        if (!data.items || data.items.length === 0) {
            body.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Chưa có món nào</p>
                </div>
            `;
            if (btnContainer) {
                btnContainer.innerHTML = `
                    <a href="${MENU_CONFIG.baseUrl}/orders?table_id=${MENU_CONFIG.tableId}&order_id=${MENU_CONFIG.orderId}" class="cart-action-btn btn-gold w-100" style="background:var(--surface); border:1px solid var(--border); color:var(--text);">
                        <i class="fas fa-file-invoice me-2"></i> CHI TIẾT
                    </a>
                `;
            }
        } else {
            let draftsHtml = '';
            let pendingHtml = '';
            let confirmedHtml = '';
            let draftCount = 0;

            data.items.forEach(it => {
                const itemHtml = `
                    <div class="cart-item-row" data-item-id="${it.id}">
                        <div style="flex:1;">
                            <div class="cart-item-name">${it.item_name}</div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="cart-item-price">${it.price_fmt}</span>
                                ${it.status === 'draft' ? `
                                <div class="qty-control">
                                    <button onclick="changeCartQty(${it.id}, -1)"><i class="fas fa-minus"></i></button>
                                    <span>${it.quantity}</span>
                                    <button onclick="changeCartQty(${it.id}, 1)"><i class="fas fa-plus"></i></button>
                                </div>
                                ` : `
                                <span class="cart-item-qty">x${it.quantity}</span>
                                `}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="cart-item-price">${it.subtotal_fmt}</div>
                        </div>
                    </div>
                `;

                if (it.status === 'draft') {
                    draftsHtml += itemHtml;
                    draftCount++;
                } else if (it.status === 'pending') {
                    pendingHtml += itemHtml;
                } else {
                    confirmedHtml += itemHtml;
                }
            });

            let finalHtml = '';
            if (draftsHtml) finalHtml += `<div class="section-label"><i class="fas fa-edit"></i> Món nháp</div>${draftsHtml}`;
            if (pendingHtml) finalHtml += `<div class="section-label text-warning"><i class="fas fa-clock"></i> Chờ xác nhận</div>${pendingHtml}`;
            if (confirmedHtml) finalHtml += `<div class="section-label text-success"><i class="fas fa-check-circle"></i> Đã gửi bếp</div>${confirmedHtml}`;
            
            body.innerHTML = finalHtml;

            if (btnContainer) {
                if (draftCount > 0) {
                    btnContainer.innerHTML = `
                        <button type="button" onclick="confirmOrderAjax(${MENU_CONFIG.orderId})" class="cart-action-btn btn-gold w-100" style="background:var(--danger)">
                            <i class="fas fa-concierge-bell me-2"></i> GỬI BẾP (${draftCount})
                        </button>
                    `;
                } else {
                    btnContainer.innerHTML = `
                        <a href="${MENU_CONFIG.baseUrl}/orders?table_id=${MENU_CONFIG.tableId}&order_id=${MENU_CONFIG.orderId}" class="cart-action-btn btn-gold w-100" style="background:var(--success)">
                            <i class="fas fa-file-invoice me-2"></i> XEM HÓA ĐƠN
                        </a>
                    `;
                }
            }
        }
    }
}

// Thay đổi số lượng món trong giỏ
function changeCartQty(itemId, delta) {
    fetch(`${MENU_CONFIG.baseUrl}/orders/update`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ item_id: itemId, order_id: MENU_CONFIG.orderId, qty: 'delta:' + delta })
    })
    .then(r => r.json())
    .then(data => { if (data.ok) updateCartUI(data); });
}

// Gửi bếp
function confirmOrderAjax(orderId) {
    if (!confirm('Xác nhận gửi các món nháp này xuống bếp?')) return;
    fetch(`${MENU_CONFIG.baseUrl}/orders/confirm`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ order_id: orderId, table_id: MENU_CONFIG.tableId })
    })
    .then(r => r.json())
    .then(res => { 
        if (res.ok) { 
            showToast('Đã gửi bếp thành công!'); 
            updateCartUI(res); 
        } 
    });
}

// Mở modal chi tiết món
function handleOpenItemModal(el) {
    const d = el.dataset;
    currentItem = { id: d.id, name: d.name, price: parseFloat(d.price), orderId: d.order, qty: 1 };
    
    document.getElementById('modalItemName').textContent = d.name;
    document.getElementById('modalItemPrice').textContent = formatMoney(d.price);
    document.getElementById('modalItemDesc').textContent = d.desc || '';
    
    const imgContainer = document.getElementById('modalItemImgContainer');
    imgContainer.innerHTML = d.img ? `<img src="${d.img}" style="width:100%; height:100%; object-fit:cover;">` : `<div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted"><i class="fas fa-image fa-2x"></i></div>`;
    
    document.getElementById('orderControlsSection').style.display = MENU_CONFIG.orderId > 0 ? 'block' : 'none';
    if (MENU_CONFIG.orderId > 0) updateModalUI();
    
    Aurora.openModal('modalItemDetail');
}

function changeModalQty(delta) { 
    if (!currentItem) return; 
    currentItem.qty = Math.max(1, currentItem.qty + delta); 
    updateModalUI(); 
}

function updateModalUI() { 
    document.getElementById('modalItemQty').textContent = currentItem.qty; 
    document.getElementById('modalBtnTotal').textContent = formatMoney(currentItem.qty * currentItem.price); 
}

// Thêm món từ modal
function confirmAddToOrder() {
    if (MENU_CONFIG.orderId <= 0) {
        alert('Vui lòng chọn bàn trước khi gọi món!');
        return;
    }
    const f = new FormData();
    f.append('order_id', MENU_CONFIG.orderId);
    f.append('menu_item_id', currentItem.id);
    f.append('qty', currentItem.qty);
    f.append('note', document.getElementById('modalItemNote').value);
    
    fetch(`${MENU_CONFIG.baseUrl}/orders/add`, { method: 'POST', body: f })
    .then(res => res.json())
    .then(res => {
        if (res.ok) { 
            Aurora.closeModal('modalItemDetail'); 
            showToast('Đã thêm vào giỏ!'); 
            updateCartUI(res); 
        }
    });
}

// Thêm nhanh món từ danh sách
function quickAdd(event, itemId, orderId) {
    event.stopPropagation();
    if (!orderId) {
        alert('Vui lòng chọn bàn trước khi gọi món!');
        // Mở drawer giỏ hàng để khách/nhân viên thấy ô chọn bàn
        if (window.innerWidth <= 900) {
            document.getElementById('posCartCol').classList.add('is-open');
        }
        return;
    }
    const f = new FormData();
    f.append('order_id', orderId);
    f.append('menu_item_id', itemId);
    f.append('qty', 1);
    
    fetch(`${MENU_CONFIG.baseUrl}/orders/add`, { method: 'POST', body: f })
    .then(res => res.json())
    .then(res => {
        if (res.ok) { 
            showToast('Đã thêm món!'); 
            updateCartUI(res); 
        }
    });
}

// Thông báo Toast
function showToast(msg) {
    const t = document.getElementById('addToast');
    if (!t) return;
    t.textContent = msg; 
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2000);
}

// Lọc món theo danh mục
document.querySelectorAll('.filter-pill').forEach(pill => {
    pill.addEventListener('click', () => {
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('is-active'));
        pill.classList.add('is-active');
        const f = pill.dataset.filter;
        document.querySelectorAll('.menu-section').forEach(s => {
            s.style.display = (f === 'all' || s.dataset.section === f) ? 'block' : 'none';
        });
    });
});
