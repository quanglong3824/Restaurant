/**
 * Customer Menu JavaScript - Aurora Restaurant
 * Digital Menu for Customers
 */

(function() {
    'use strict';

    // State
    var cart = [];
    var currentDetailItem = null;
    var detailQty = 1;
    var detailNote = '';
    var selectedOptions = [];

    /**
     * Show item detail modal
     */
    window.showItemDetail = function(item) {
        currentDetailItem = item;
        detailQty = 1;
        detailNote = '';
        selectedOptions = [];

        document.getElementById('detailName').textContent = item.name;
        document.getElementById('detailNameEn').textContent = item.name_en || '';
        document.getElementById('detailPrice').textContent = formatPrice(item.price);
        document.getElementById('detailDesc').textContent = item.description || '';
        document.getElementById('detailQty').textContent = '1';
        document.getElementById('detailNote').value = '';

        // Set background image
        var imgBox = document.getElementById('detailImg');
        if (item.image) {
            imgBox.style.backgroundImage = "url('" + CUSTOMER_CONFIG.baseUrl + "/public/uploads/" + item.image + "')";
        } else {
            imgBox.style.backgroundImage = 'none';
            imgBox.style.background = '#f1f5f9';
        }

        // Show options if available
        var optsWrap = document.getElementById('detailOptsWrap');
        if (item.note_options) {
            var options = (item.note_options || '').split(',').map(function(o) { return o.trim(); }).filter(Boolean);
            var optionsEn = (item.note_options_en || '').split(',').map(function(o) { return o.trim(); }).filter(Boolean);
            var container = document.getElementById('detailOptsContainer');
            container.innerHTML = '';
            
            options.forEach(function(opt, idx) {
                var chip = document.createElement('span');
                chip.className = 'opt-chip-premium';
                chip.textContent = optionsEn[idx] || opt;
                chip.dataset.option = opt;
                chip.onclick = function() {
                    this.classList.toggle('active');
                    if (this.classList.contains('active')) {
                        selectedOptions.push(opt);
                    } else {
                        selectedOptions = selectedOptions.filter(function(o) { return o !== opt; });
                    }
                };
                container.appendChild(chip);
            });
            optsWrap.style.display = 'block';
        } else {
            optsWrap.style.display = 'none';
        }

        document.getElementById('itemDetailModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    /**
     * Close item detail modal
     */
    window.closeItemDetail = function() {
        document.getElementById('itemDetailModal').classList.add('hidden');
        document.body.style.overflow = '';
        currentDetailItem = null;
    };

    /**
     * Change detail quantity
     */
    window.changeDetailQty = function(delta) {
        detailQty = Math.max(1, detailQty + delta);
        document.getElementById('detailQty').textContent = detailQty;
    };

    /**
     * Add item from detail modal
     */
    window.addFromDetail = function() {
        if (!currentDetailItem) return;
        
        detailNote = document.getElementById('detailNote').value.trim();
        
        addToCart({
            id: currentDetailItem.id,
            name: currentDetailItem.name,
            price: currentDetailItem.price,
            quantity: detailQty,
            note: detailNote,
            options: selectedOptions.join(', ')
        });

        closeItemDetail();
        updateCartDisplay();
    };

    /**
     * Quick add item
     */
    window.quickAdd = function(id, name, price) {
        addToCart({
            id: id,
            name: name,
            price: price,
            quantity: 1,
            note: '',
            options: ''
        });
        updateCartDisplay();
    };

    /**
     * Add to cart
     */
    function addToCart(item) {
        var existing = cart.find(function(c) { 
            return c.id === item.id && c.note === item.note && c.options === item.options; 
        });
        
        if (existing) {
            existing.quantity += item.quantity;
        } else {
            cart.push(item);
        }
    }

    /**
     * Remove from cart
     */
    window.removeFromCart = function(index) {
        cart.splice(index, 1);
        updateCartDisplay();
    };

    /**
     * Change cart item quantity
     */
    window.changeCartQty = function(index, delta) {
        if (cart[index]) {
            cart[index].quantity = Math.max(1, cart[index].quantity + delta);
            updateCartDisplay();
        }
    };

    /**
     * Update cart display
     */
    function updateCartDisplay() {
        var cartBar = document.getElementById('cartBar');
        var cartModal = document.getElementById('cartModal');
        
        if (cart.length === 0) {
            cartBar.classList.add('hidden');
            if (cartModal) cartModal.classList.add('hidden');
            return;
        }

        var total = cart.reduce(function(sum, item) { return sum + (item.price * item.quantity); }, 0);
        var count = cart.reduce(function(sum, item) { return sum + item.quantity; }, 0);

        document.getElementById('cartCount').textContent = count;
        document.getElementById('cartTotal').textContent = formatPrice(total);
        document.getElementById('modalCartTotal').textContent = formatPrice(total);

        // Update cart items list
        var itemsList = document.getElementById('cartItemsList');
        if (itemsList) {
            itemsList.innerHTML = cart.map(function(item, idx) {
                return '<div class="bill-item">' +
                    '<div class="bill-item-main">' +
                        '<span class="bill-qty">' + item.quantity + 'x</span>' +
                        '<span class="bill-name">' + escapeHtml(item.name) + '</span>' +
                        '<span class="bill-price">' + formatPrice(item.price * item.quantity) + '</span>' +
                    '</div>' +
                    (item.note ? '<div style="font-size:.7rem;color:#94a3b8;padding-left:36px;margin-top:2px;"><i class="fas fa-pen" style="font-size:.6rem;"></i> ' + escapeHtml(item.note) + '</div>' : '') +
                    (item.options ? '<div style="font-size:.7rem;color:#94a3b8;padding-left:36px;margin-top:2px;"><i class="fas fa-tag" style="font-size:.6rem;"></i> ' + escapeHtml(item.options) + '</div>' : '') +
                    '<div style="display:flex;gap:0.5rem;margin-top:0.5rem;">' +
                        '<button onclick="changeCartQty(' + idx + ', -1)" style="width:28px;height:28px;border-radius:50%;border:1px solid #e2e8f0;background:#f8fafc;color:#64748b;cursor:pointer;"><i class="fas fa-minus" style="font-size:.7rem;"></i></button>' +
                        '<button onclick="changeCartQty(' + idx + ', 1)" style="width:28px;height:28px;border-radius:50%;border:1px solid #e2e8f0;background:#f8fafc;color:#64748b;cursor:pointer;"><i class="fas fa-plus" style="font-size:.7rem;"></i></button>' +
                        '<button onclick="removeFromCart(' + idx + ')" style="width:28px;height:28px;border-radius:50%;border:1px solid #fee2e2;background:#fee2e2;color:#dc2626;cursor:pointer;"><i class="fas fa-trash" style="font-size:.7rem;"></i></button>' +
                    '</div>' +
                '</div>';
            }).join('');
        }

        cartBar.classList.remove('hidden');
    }

    /**
     * Toggle cart modal
     */
    window.toggleCartModal = function() {
        var modal = document.getElementById('cartModal');
        modal.classList.toggle('hidden');
        document.body.style.overflow = modal.classList.contains('hidden') ? '' : 'hidden';
    };

    /**
     * Submit order
     */
    window.submitOrder = function() {
        if (cart.length === 0) {
            alert('Giỏ hàng trống!');
            return;
        }

        var btn = document.getElementById('btnSubmitOrder');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang gửi...';

        var notes = document.getElementById('orderNotes').value.trim();

        fetch(CUSTOMER_CONFIG.baseUrl + '/qr/orders/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                table_id: CUSTOMER_CONFIG.tableId,
                items: JSON.stringify(cart),
                notes: notes
            }).toString()
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.ok) {
                alert('Đơn hàng đã được gửi thành công!');
                cart = [];
                updateCartDisplay();
                document.getElementById('cartModal').classList.add('hidden');
                document.getElementById('orderNotes').value = '';
                window.location.reload();
            } else {
                alert('Lỗi: ' + (data.message || 'Không thể gửi đơn hàng'));
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> XÁC NHẬN GỌI MÓN';
            }
        })
        .catch(function(err) {
            alert('Lỗi kết nối: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> XÁC NHẬN GỌI MÓN';
        });
    };

    /**
     * Call waiter / support
     */
    window.callWaiter = function(type) {
        var msg = type === 'payment' ? 'Yêu cầu thanh toán' : (CUSTOMER_CONFIG.isRoomService ? 'Gọi lễ tân' : 'Gọi phục vụ');
        if (!confirm('Bạn có chắc muốn ' + msg + '?')) return;

        fetch(CUSTOMER_CONFIG.baseUrl + '/qr/support/request', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                table_id: CUSTOMER_CONFIG.tableId,
                type: type
            }).toString()
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.ok) {
                alert('Yêu cầu đã được gửi! Nhân viên sẽ đến ngay.');
            } else {
                alert('Lỗi: ' + (data.message || 'Không thể gửi yêu cầu'));
            }
        })
        .catch(function(err) {
            alert('Lỗi kết nối: ' + err.message);
        });
    };

    /**
     * Format price
     */
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price) + '₫';
    }

    /**
     * Escape HTML
     */
    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Initialize
     */
    function init() {
        // Check if need to show bill on load
        if (CUSTOMER_CONFIG.showBill && CUSTOMER_CONFIG.hasItems) {
            setTimeout(function() {
                window.showBillTam();
            }, 500);
        }

        // Close modal on outside click
        document.querySelectorAll('.modal-backdrop').forEach(function(modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();