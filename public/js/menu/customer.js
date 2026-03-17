/**
 * Customer Menu JS — Aurora Restaurant
 */

let cart = [];
let currentItem = null;

document.addEventListener('DOMContentLoaded', () => {
    loadCart();
    setupCategoryNav();
    setupSearch();
    updateCartUI();
});

function setupCategoryNav() {
    const pills = document.querySelectorAll('.cat-pill');
    pills.forEach(pill => {
        pill.addEventListener('click', (e) => {
            pills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
        });
    });

    // Intersection Observer to update active category on scroll
    const sections = document.querySelectorAll('.menu-section');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id.replace('cat-', '');
                pills.forEach(p => {
                    p.classList.toggle('active', p.dataset.category === id);
                });
            }
        });
    }, { threshold: 0.5 });

    sections.forEach(s => observer.observe(s));
}

function setupSearch() {
    const searchInput = document.getElementById('menuSearch');
    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.menu-card');
        
        cards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const isMatch = name.includes(query);
            card.style.display = isMatch ? 'flex' : 'none';
        });

        // Hide empty sections
        document.querySelectorAll('.menu-section').forEach(section => {
            const hasVisibleItems = Array.from(section.querySelectorAll('.menu-card'))
                .some(card => card.style.display !== 'none');
            section.style.display = hasVisibleItems ? 'block' : 'none';
        });
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}

function loadCart() {
    const saved = localStorage.getItem(`cart_table_${CUSTOMER_CONFIG.tableId}`);
    if (saved) {
        cart = JSON.parse(saved);
    }
}

function saveCart() {
    localStorage.setItem(`cart_table_${CUSTOMER_CONFIG.tableId}`, JSON.stringify(cart));
    updateCartUI();
}

function updateCartUI() {
    const cartBar = document.getElementById('cartBar');
    const cartCount = document.getElementById('cartCount');
    const cartTotal = document.getElementById('cartTotal');
    
    const totalCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    if (totalCount > 0) {
        cartBar.classList.remove('hidden');
        cartCount.textContent = totalCount;
        cartTotal.textContent = formatCurrency(totalPrice);
    } else {
        cartBar.classList.add('hidden');
    }

    // Also update modal if open
    updateCartModal();
}

function quickAdd(id, name, price) {
    const existing = cart.find(item => item.id === id && !item.note);
    if (existing) {
        existing.quantity++;
    } else {
        cart.push({ id, name, price, quantity: 1, note: '' });
    }
    saveCart();
    showToast(`Đã thêm ${name}`);
}

function showToast(msg) {
    const toast = document.createElement('div');
    toast.className = 'toast-msg';
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function showItemDetail(item) {
    currentItem = { ...item, quantity: 1, note: '' };
    document.getElementById('detailName').textContent = item.name;
    document.getElementById('detailPrice').textContent = formatCurrency(item.price);
    document.getElementById('detailDesc').textContent = item.description || 'Không có mô tả.';
    document.getElementById('detailQty').textContent = '1';
    document.getElementById('detailNote').value = '';
    
    const imgContainer = document.getElementById('detailImg');
    if (item.image) {
        imgContainer.style.backgroundImage = `url(${CUSTOMER_CONFIG.baseUrl}/public/uploads/${item.image})`;
    } else {
        imgContainer.style.backgroundColor = '#f0f0f0';
        imgContainer.innerHTML = '<i class="fas fa-image" style="font-size:3rem; color:#ccc;"></i>';
    }

    updateDetailTotal();
    document.getElementById('itemDetailModal').classList.remove('hidden');
}

function changeDetailQty(delta) {
    if (!currentItem) return;
    currentItem.quantity = Math.max(1, currentItem.quantity + delta);
    document.getElementById('detailQty').textContent = currentItem.quantity;
    updateDetailTotal();
}

function updateDetailTotal() {
    const total = currentItem.price * currentItem.quantity;
    document.getElementById('detailBtnTotal').textContent = formatCurrency(total);
}

function addFromDetail() {
    currentItem.note = document.getElementById('detailNote').value;
    
    const existing = cart.find(item => item.id === currentItem.id && item.note === currentItem.note);
    if (existing) {
        existing.quantity += currentItem.quantity;
    } else {
        cart.push({ ...currentItem });
    }
    
    saveCart();
    document.getElementById('itemDetailModal').classList.add('hidden');
    showToast(`Đã thêm ${currentItem.name}`);
}

function toggleCartModal() {
    document.getElementById('cartModal').classList.toggle('hidden');
    updateCartModal();
}

function updateCartModal() {
    const container = document.getElementById('cartItemsList');
    const modalTotal = document.getElementById('modalCartTotal');
    
    if (cart.length === 0) {
        container.innerHTML = '<div class="text-center py-5 text-muted">Giỏ hàng trống</div>';
        modalTotal.textContent = '0₫';
        return;
    }

    let html = '';
    let total = 0;
    
    cart.forEach((item, index) => {
        total += item.price * item.quantity;
        html += `
            <div class="cart-item">
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">${formatCurrency(item.price)}</div>
                    ${item.note ? `<div class="cart-item-note small text-muted">Lưu ý: ${item.note}</div>` : ''}
                </div>
                <div class="qty-control">
                    <button onclick="changeCartQty(${index}, -1)"><i class="fas fa-minus"></i></button>
                    <span>${item.quantity}</span>
                    <button onclick="changeCartQty(${index}, 1)"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    modalTotal.textContent = formatCurrency(total);
}

function changeCartQty(index, delta) {
    cart[index].quantity += delta;
    if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
    }
    saveCart();
}

async function submitOrder() {
    if (cart.length === 0) return;

    const notes = document.getElementById('orderNotes').value;
    const btn = document.querySelector('.btn-submit-order');
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';

    const formData = new FormData();
    formData.append('cart', JSON.stringify(cart));
    formData.append('notes', notes);

    try {
        const response = await fetch(`${CUSTOMER_CONFIG.baseUrl}/qr/order/submit`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            cart = [];
            saveCart();
            window.location.href = `${CUSTOMER_CONFIG.baseUrl}/qr/order/status`;
        } else {
            alert(result.error || 'Lỗi gửi order');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (e) {
        alert('Lỗi kết nối máy chủ');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// Close modals when clicking backdrop
document.querySelectorAll('.modal-backdrop').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
