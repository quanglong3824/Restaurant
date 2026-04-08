<?php // views/admin/tables/qr_download.php — Print QR Code ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin/qr-download.css">

<div class="qr-print-container">
    <div class="no-print mb-4 d-flex gap-2">
        <button onclick="window.print()" class="btn-gold"><i class="fas fa-print me-1"></i> In mã QR</button>
        <a href="<?= BASE_URL ?>/admin/qr-codes" class="btn-ghost"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
    </div>

    <div class="qr-card-printable" id="printableArea">
        <div class="qr-card-header">
            <i class="fas fa-utensils"></i>
            <h2>AURORA</h2>
            <p>RESTAURANT</p>
        </div>
        
        <div class="qr-code-wrapper">
            <div id="qrcode"></div>
            <?php 
                $logoPath = BASE_URL . '/public/src/logo/favicon.png'; 
            ?>
            <img src="<?= $logoPath ?>" class="qr-logo" alt="Logo" onerror="console.error('QR Logo failed to load from: ' + this.src)">
        </div>
        
        <div class="qr-card-footer">
            <div class="table-number">BÀN <?= e($tableName) ?></div>
            <p>Quét mã để xem menu & đặt món</p>
            <small>Scan to order</small>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const url = '<?= BASE_URL ?>/q?t=<?= $token ?>';
        new QRCode(document.getElementById("qrcode"), {
            text: url,
            width: 250,
            height: 250,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H,
            margin: 2
        });
    });
</script>