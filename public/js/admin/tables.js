/**
 * Admin Tables Management - Aurora Restaurant
 * Handles QR code generation and table management
 */

(function() {
    'use strict';

    let qrModal = null;
    let qrContainer = null;
    let qrUrlText = null;
    let qrTitle = null;
    let qrTableDisplay = null;
    let closeBtns = null;

    /**
     * Initialize QR Code modal
     */
    function initQRModal() {
        qrModal = document.getElementById('qrModal');
        qrContainer = document.getElementById('qrcode-canvas');
        qrUrlText = document.getElementById('qrUrl');
        qrTitle = document.getElementById('qrModalTitle');
        qrTableDisplay = document.getElementById('qrTableDisplay');
        closeBtns = document.querySelectorAll('.close-modal');

        if (!qrModal || !qrContainer) return;

        document.querySelectorAll('.btn-qr').forEach(btn => {
            btn.addEventListener('click', function() {
                const tableId = btn.dataset.id;
                const tableName = btn.dataset.name;
                const token = btn.dataset.token;

                if (!token) {
                    if (confirm('Bàn/Phòng này chưa có mã QR định danh. Bạn có muốn hệ thống tự động tạo mã ngay bây giờ?')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = BASE_URL + '/admin/qr-codes/generate';
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'table_id';
                        input.value = tableId;
                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    }
                    return;
                }

                const fullUrl = BASE_URL + '/q?t=' + token;

                qrTitle.innerText = 'Mã QR: ' + tableName;
                qrTableDisplay.innerText = tableName.toUpperCase();
                qrUrlText.innerText = fullUrl;
                qrContainer.innerHTML = '';

                // Check if QRCode library is loaded
                if (typeof QRCode !== 'undefined') {
                    new QRCode(qrContainer, {
                        text: fullUrl,
                        width: 300,
                        height: 300,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H,
                        margin: 2
                    });
                } else {
                    qrContainer.innerHTML = '<p style="color: red;">Thư viện QRCode chưa được tải!</p>';
                }

                qrModal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        });

        closeBtns.forEach(btn => {
            btn.addEventListener('click', closeQRModal);
        });

        window.addEventListener('click', function(e) {
            if (e.target == qrModal) {
                closeQRModal();
            }
        });
    }

    /**
     * Close QR Modal
     */
    function closeQRModal() {
        if (!qrModal) return;
        qrModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    /**
     * Print QR Code
     */
    function printQR() {
        window.print();
    }

    /**
     * Download QR Code as image
     */
    function downloadQR() {
        const img = document.querySelector('#qrcode-canvas img');
        if (!img) return;
        const link = document.createElement('a');
        link.download = 'QR-' + document.getElementById('qrTableDisplay').innerText + '.png';
        link.href = img.src;
        link.click();
    }

    /**
     * Confirm Reset QR Code
     * @param {number} tableId - Table ID
     * @param {string} tableName - Table name
     * @param {number} isPrinted - Whether QR was printed
     * @param {number} scanCount - Number of scans
     * @param {number} itemsCount - Number of items ordered
     */
    function confirmResetQR(tableId, tableName, isPrinted, scanCount, itemsCount) {
        if (itemsCount > 0) {
            alert('CẢNH BÁO: Bàn/Phòng ' + tableName + ' đang có khách đã đặt món (' + itemsCount + ' món).\n\nVui lòng hoàn tất đơn hàng và thanh toán trước khi reset QR.');
            return;
        }

        if (isPrinted) {
            if (!confirm('Mã QR của ' + tableName + ' ĐÃ ĐƯỢC IN ra giấy.\n\nNếu bạn reset, mã QR cũ trên giấy sẽ không còn tác dụng và khách không thể quét được nữa.\n\nBạn có CHẮC CHẮN vẫn muốn tạo mã mới?')) {
                return;
            }
        } else if (scanCount > 0) {
            if (!confirm('Mã QR này đã được quét ' + scanCount + ' lần.\n\nBạn có chắc chắn muốn reset không?')) {
                return;
            }
        } else {
            if (!confirm('Xác nhận tạo mã QR mới cho ' + tableName + '?')) {
                return;
            }
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = BASE_URL + '/admin/qr-codes/generate';
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'table_id';
        input.value = tableId;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }

    /**
     * Initialize event listeners
     */
    function init() {
        initQRModal();

        // Expose global functions for onclick handlers
        window.printQR = printQR;
        window.downloadQR = downloadQR;
        window.confirmResetQR = confirmResetQR;
        window.closeQRModal = closeQRModal;
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();