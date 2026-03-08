let currentItem = null;
let currentSet = null;

function handleOpenSetModal(set) {
    currentSet = set;
    document.getElementById('modalSetName').textContent = set.name;
    document.getElementById('modalSetPrice').textContent = formatMoney(set.price);
    document.getElementById('modalSetDesc').textContent = set.description || '';

    const list = document.getElementById('modalSetItemsList');
    list.innerHTML = '';

    if (set.items && set.items.length > 0) {
        set.items.forEach(it => {
            const itemDiv = document.createElement('div');
            itemDiv.style.cssText = 'display:flex; align-items:center; justify-content:space-between; padding:0.75rem; background:#fff; border-radius:12px; border:1px solid var(--border);';
            itemDiv.innerHTML = `
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <input type="checkbox" checked disabled style="width:18px; height:18px; accent-color:var(--gold);">
                    <div>
                        <div style="font-weight:700; font-size:0.9rem;">${it.name}</div>
                        <small style="color:var(--text-muted);">Số lượng: ${it.quantity}</small>
                    </div>
                </div>
            `;
            list.appendChild(itemDiv);
        });
    }

    Aurora.openModal('modalSetDetail');
}

function confirmAddSetToOrder() {
    if (!MENU_CONFIG.orderId) { alert('Vui lòng chọn bàn/order trước!'); return; }

    const f = new URLSearchParams();
    f.append('order_id', MENU_CONFIG.orderId);
    f.append('set_id', currentSet.id);

    // Prepare items array for the controller
    currentSet.items.forEach((it, idx) => {
        f.append(`items[${idx}][menu_item_id]`, it.menu_item_id);
        f.append(`items[${idx}][quantity]`, it.quantity);
    });

    fetch(MENU_CONFIG.baseUrl + '/orders/add-set', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: f
    }).then(res => res.json()).then(res => {
        if (res.ok) {
            Aurora.closeModal('modalSetDetail');
            showToast('Đã thêm Combo!');
            updateCartUI(res);
        } else {
            alert(res.message || 'Lỗi khi thêm Combo');
        }
    });
}

function formatMoney(amount) { return new Intl.NumberFormat('vi-VN').format(amount) + '₫'; }
function toggleCart(show) {
    const c = document.getElementById('cartCol');
    const o = document.getElementById('cartOverlay');
    if (!c) return;
    if (show) { c.classList.add('is-visible'); o?.classList.add('is-visible'); document.body.style.overflow = 'hidden'; }
    else { c.classList.remove('is-visible'); o?.classList.remove('is-visible'); document.body.style.overflow = ''; }
}

function handleBodyClick(e) {
    if (window.innerWidth >= 1024) return;
    // Nếu click trực tiếp vào cart-body hoặc vùng không chứa class quan trọng -> Đóng giỏ
    const isItemRow = e.target.closest('.cart-item-row');
    const isButton = e.target.closest('button, a');
    if (!isItemRow && !isButton) {
        toggleCart(false);
    }
}

function updateCartUI(data) {
    if (!data.ok) return;
    const body = document.querySelector('.cart-body');
    const totalEl = document.getElementById('orderTotal');
    const fTotal = document.getElementById('fabTotal');
    const fCount = document.getElementById('fabCount');
    const btnContainer = document.getElementById('cartActionBtn');

    if (totalEl) totalEl.textContent = data.total_fmt;
    if (fTotal) fTotal.textContent = data.total_fmt;
    if (fCount) fCount.textContent = data.items.length;

    if (body) {
        if (data.items.length === 0) {
            body.innerHTML = '<div style="text-align:center; padding-top:3rem; color:var(--text-dim);"><i class="fas fa-shopping-basket" style="font-size:2rem; margin-bottom:1rem; opacity:0.3;"></i><p>Chưa có món nào.</p></div>';
        } else {
            let draftsHtml = ''; let confirmedHtml = ''; let draftCount = 0;
            data.items.forEach(it => {
                const itemHtml = `<div class="cart-item-row" style="display:flex; justify-content:space-between; margin-bottom:1rem; padding-bottom:1rem; border-bottom:1px dashed var(--border);">
                    <div style="flex:1;">
                        <div style="font-weight:700; font-size:0.95rem; margin-bottom:4px;">${it.item_name}</div>
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <span style="font-size:0.85rem; color:var(--gold-dark); font-weight:700;">${it.price_fmt}</span>
                            ${it.status === 'draft' ? `
                            <div style="display:inline-flex; align-items:center; background:var(--surface-2); border-radius:20px; padding:2px 8px;">
                                <button onclick="event.stopPropagation(); changeCartQty(${it.id}, -1)" style="border:none; background:none; padding:4px; cursor:pointer;"><i class="fas fa-minus" style="font-size:0.7rem;"></i></button>
                                <span style="width:24px; text-align:center; font-weight:800; font-size:0.85rem;">${it.quantity}</span>
                                <button onclick="event.stopPropagation(); changeCartQty(${it.id}, 1)" style="border:none; background:none; padding:4px; cursor:pointer;"><i class="fas fa-plus" style="font-size:0.7rem;"></i></button>
                            </div>
                            ` : `
                            <span style="font-size:0.85rem; color:var(--text-muted); font-weight:700;">x${it.quantity}</span>
                            `}
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-weight:800; font-size:0.95rem;">${it.subtotal_fmt}</div>
                        <small style="color:${it.status === 'confirmed' ? 'var(--success)' : 'var(--text-muted)'}; font-weight:600;">
                            ${it.status === 'confirmed' ? 'Đã gửi' : 'Món nháp'}
                        </small>
                    </div>
                </div>`;

                if (it.status === 'draft') {
                    draftsHtml += itemHtml;
                    draftCount++;
                } else {
                    confirmedHtml += itemHtml;
                }
            });

            let finalHtml = '';
            if (draftsHtml) {
                finalHtml += `<div class="section-label"><i class="fas fa-edit"></i> Món đang chọn (nháp)</div>${draftsHtml}`;
            }
            if (confirmedHtml) {
                finalHtml += `<div class="section-label"><i class="fas fa-check-circle"></i> Đã gửi bếp (Đang làm)</div><div class="confirmed-section">${confirmedHtml}</div>`;
            }
            body.innerHTML = finalHtml;

            if (btnContainer) {
                if (draftCount > 0) {
                    btnContainer.innerHTML = `<button type="button" onclick="confirmOrderAjax(${MENU_CONFIG.orderId})" class="btn btn-gold btn-block" style="background:var(--danger); border:none; height:54px; font-size:1.05rem; box-shadow:0 4px 12px rgba(239,68,68,0.3);"><i class="fas fa-concierge-bell"></i> GỬI BẾP (${draftCount} món)</button>`;
                } else if (data.items.length > 0) {
                    btnContainer.innerHTML = `<a href="${MENU_CONFIG.baseUrl}/orders?table_id=${MENU_CONFIG.tableId}&order_id=${MENU_CONFIG.orderId}" class="btn btn-gold btn-block" style="background:var(--success); color:white; border:none; height:54px; font-size:1.05rem; box-shadow:0 4px 12px rgba(16,185,129,0.3);"><i class="fas fa-check-circle"></i> ĐÃ GỬI BẾP (XEM BILL)</a>`;
                } else {
                    btnContainer.innerHTML = `<a href="${MENU_CONFIG.baseUrl}/orders?table_id=${MENU_CONFIG.tableId}&order_id=${MENU_CONFIG.orderId}" class="btn btn-gold btn-block" style="height:54px; font-size:1rem;"><i class="fas fa-file-invoice-dollar"></i> XEM CHI TIẾT BILL</a>`;
                }
            }
        }
    }
}

function changeCartQty(itemId, delta) {
    fetch(MENU_CONFIG.baseUrl + '/orders/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ item_id: itemId, order_id: MENU_CONFIG.orderId, qty: 'delta:' + delta })
    }).then(r => r.json()).then(data => { if (data.ok) updateCartUI(data); });
}

function confirmOrderAjax(orderId) {
    if (!confirm('Xác nhận gửi các món nháp này xuống bếp?')) return;
    fetch(MENU_CONFIG.baseUrl + '/orders/confirm', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ order_id: orderId, table_id: MENU_CONFIG.tableId })
    }).then(r => r.json()).then(res => { if (res.ok) { showToast('Đã gửi bếp thành công!'); updateCartUI(res); } });
}

function handleOpenItemModal(el) {
    const d = el.dataset;
    currentItem = { id: d.id, name: d.name, price: parseFloat(d.price), orderId: d.order, qty: 1 };
    document.getElementById('modalItemName').textContent = d.name;
    document.getElementById('modalItemPrice').textContent = formatMoney(d.price);
    document.getElementById('modalItemDesc').textContent = d.desc || '';
    const imgEl = document.getElementById('modalItemImg');
    const placeholder = document.getElementById('modalItemImgPlaceholder');
    imgEl.querySelectorAll('img').forEach(i => i.remove());
    if (d.img) {
        placeholder.style.display = 'none';
        const i = document.createElement('img');
        i.src = d.img; i.style.cssText = 'width:100%; height:100%; object-fit:cover;';
        imgEl.appendChild(i);
    } else { placeholder.style.display = 'flex'; }
    document.getElementById('orderControlsSection').style.display = d.order ? 'block' : 'none';
    if (d.order) updateModalUI();
    Aurora.openModal('modalItemDetail');
}

function changeModalQty(delta) { if (!currentItem) return; currentItem.qty = Math.max(1, currentItem.qty + delta); updateModalUI(); }
function updateModalUI() { document.getElementById('modalItemQty').textContent = currentItem.qty; document.getElementById('modalBtnTotal').textContent = formatMoney(currentItem.qty * currentItem.price); }

function confirmAddToOrder() {
    const f = new FormData();
    f.append('order_id', currentItem.orderId); f.append('menu_item_id', currentItem.id);
    f.append('qty', currentItem.qty); f.append('note', document.getElementById('modalItemNote').value);
    fetch(MENU_CONFIG.baseUrl + '/orders/add', { method: 'POST', body: f }).then(res => res.json()).then(res => {
        if (res.ok) { Aurora.closeModal('modalItemDetail'); showToast('Đã thêm món!'); updateCartUI(res); }
    });
}

function quickAdd(event, itemId, orderId) {
    event.stopPropagation();
    if (!orderId) { alert('Vui lòng chọn bàn trước khi gọi món!'); return; }
    const f = new FormData(); f.append('order_id', orderId); f.append('menu_item_id', itemId); f.append('qty', 1);
    fetch(MENU_CONFIG.baseUrl + '/orders/add', { method: 'POST', body: f }).then(res => res.json()).then(res => {
        if (res.ok) { showToast('Đã thêm món!'); updateCartUI(res); }
    });
}

function showToast(msg) {
    const t = document.getElementById('addToast');
    if (!t) return;
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2000);
}

document.querySelectorAll('.filter-pill').forEach(pill => {
    pill.addEventListener('click', () => {
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('is-active'));
        pill.classList.add('is-active');
        const f = pill.dataset.filter;
        document.querySelectorAll('.menu-section').forEach(s => { s.style.display = (f === 'all' || s.dataset.section === f) ? 'block' : 'none'; });
    });
});
