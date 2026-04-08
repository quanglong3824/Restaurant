/**
 * Paid Bill View JavaScript - Aurora Restaurant
 * Handles receipt screenshot and session management
 */

/**
 * Capture receipt as image using html2canvas
 */
async function captureReceipt() {
    const receipt = document.querySelector('.receipt-paper');
    const btn = document.querySelector('.btn-save-img');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG TẠO ẢNH...';
    btn.disabled = true;

    try {
        // Create canvas from receipt element
        const canvas = await html2canvas(receipt, {
            scale: 3, // Higher scale for better quality/printing
            useCORS: true,
            backgroundColor: '#ffffff',
            logging: false
        });
        
        // Convert to image and download
        const image = canvas.toDataURL("image/png");
        const link = document.createElement('a');
        link.download = `HoaDon_Aurora_#${orderId}.png`;
        link.href = image;
        link.click();
        
        btn.innerHTML = '<i class="fas fa-check"></i> ĐÃ LƯU ẢNH';
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 2000);
    } catch (err) {
        console.error('Capture error:', err);
        alert('Không thể tạo ảnh hóa đơn. Vui lòng thử lại.');
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

/**
 * Start a new order session
 */
function startNewOrder() {
    if (!confirm('Bạn muốn bắt đầu lượt gọi món mới cho bàn này?')) return;
    
    fetch(baseUrl + '/qr/session/clear', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = baseUrl + '/qr/menu?table_id=' + tableId + '&token=' + (token || '');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Có lỗi xảy ra. Vui lòng thử lại.');
    });
}

/**
 * Exit the current session
 */
function exitSession() {
    if (!confirm('Bạn xác nhận rời bàn và kết thúc phiên làm việc?')) return;
    
    fetch(baseUrl + '/qr/session/clear', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Redirect to a neutral page or home
            window.location.href = 'https://google.com'; // Or your homepage
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Có lỗi xảy ra. Vui lòng thử lại.');
    });
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Any initialization code can go here
});