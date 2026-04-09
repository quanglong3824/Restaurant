<?php // views/orders/print.php - A5 Guest Check ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn <?= e($tableDisplayName) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            size: A5;
            margin: 15mm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }
        
        .container {
            width: 100%;
            max-width: 148mm;
            margin: 0 auto;
        }
        
        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .header-left {
            flex: 1;
            font-size: 9pt;
            line-height: 1.5;
        }
        
        .header-left .company-name {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .header-left .hotel-name {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .header-left .address {
            margin-bottom: 5px;
        }
        
        .header-left .contact {
            margin-bottom: 3px;
        }
        
        .header-left .outlet {
            margin-top: 5px;
        }
        
        .header-right {
            text-align: right;
        }
        
        .header-right .logo {
            width: 120px;
            height: auto;
        }
        
        .header-right .logo-text {
            font-family: 'Georgia', serif;
            font-size: 24pt;
            font-weight: normal;
            letter-spacing: 3px;
            color: #000;
        }
        
        .header-right .logo-sub {
            font-size: 10pt;
            letter-spacing: 2px;
            margin-top: 5px;
        }
        
        /* Meta Info */
        .meta-info {
            font-size: 9pt;
            margin-bottom: 3px;
        }
        
        .meta-info span {
            margin-right: 15px;
        }
        
        .meta-info strong {
            font-weight: bold;
        }
        
        /* Title */
        .title-section {
            text-align: center;
            margin: 15px 0 10px;
        }
        
        .title-vi {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .title-en {
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 10pt;
        }
        
        .items-table th {
            font-weight: bold;
            text-align: center;
            background: #f5f5f5;
        }
        
        .items-table th.col-name {
            text-align: left;
            width: 50%;
        }
        
        .items-table th.col-qty {
            width: 10%;
        }
        
        .items-table th.col-price {
            width: 20%;
        }
        
        .items-table th.col-total {
            width: 20%;
        }
        
        .items-table td.col-name {
            text-align: left;
            vertical-align: top;
        }
        
        .items-table td.col-qty,
        .items-table td.col-price,
        .items-table td.col-total {
            text-align: right;
            vertical-align: top;
        }
        
        .item-note {
            font-size: 9pt;
            color: #555;
            font-style: italic;
            margin-top: 3px;
        }
        
        /* Totals */
        .totals-section {
            margin-top: 10px;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 8px;
            font-size: 10pt;
        }
        
        .totals-row.subtotal {
            border-bottom: 1px solid #000;
        }
        
        .totals-label {
            text-align: right;
            flex: 1;
        }
        
        .totals-value {
            text-align: right;
            min-width: 100px;
        }
        
        .totals-row.total {
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 8px;
            margin-top: 5px;
        }
        
        /* Payment Status */
        .payment-status {
            margin-top: 15px;
            padding: 8px;
            border: 1px solid #000;
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
        }
        
        .payment-status.paid {
            background: #e8f5e9;
        }
        
        .payment-status.unpaid {
            background: #fff3e0;
        }
        
        /* Signature Section */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            font-size: 9pt;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
        }
        
        .signature-box .label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .signature-box .space {
            height: 50px;
        }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9pt;
            font-style: italic;
        }
        
        /* Print utilities */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .text-right {
            text-align: right;
        }
        
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header with Company Info and Logo -->
        <div class="header">
            <div class="header-left">
                <div class="company-name">CÔNG TY CỔ PHẦN PHÁT TRIỂN TRI THỨC VIỆT</div>
                <div class="hotel-name">AURORA HOTEL & PLAZA</div>
                <div class="address">253 Phạm Văn Thuận, Khu Phố 2, P.Tam Hiệp, Tỉnh Đồng Nai</div>
                <div class="contact">Tel:(0251)3918888 - Fax:(0251)3918300</div>
                <div class="contact">Email:info@aurorahotelplaza.com</div>
                <div class="outlet">Outlet : Grand Restaurant</div>
                <div class="meta-info mt-1">
                    <span>Date : <?= date('d/m/Y') ?></span>
                    <span>Time : <?= date('H:i') ?></span>
                </div>
                <div class="meta-info">
                    <span>Table: <?= e($tableDisplayName) ?></span>
                </div>
            </div>
            <div class="header-right">
                <img src="<?= BASE_URL ?>/public/src/logo/logo-dark-ui.png" alt="AURORA" class="logo" onerror="this.style.display='none'">
                <div class="logo-text">AURORA</div>
                <div class="logo-sub">HOTEL ■ PLAZA</div>
            </div>
        </div>
        
        <!-- Title -->
        <div class="title-section">
            <div class="title-vi">BẢNG KÊ CHI TIẾT</div>
            <div class="title-en">GUEST CHECK</div>
        </div>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-name">Tên hàng hóa dịch vụ<br>(Selling items)</th>
                    <th class="col-qty">SL<br>Q.ty</th>
                    <th class="col-price">Đơn Giá<br>Price</th>
                    <th class="col-total">Thành Tiền<br>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="col-name">
                            <?= e($item['item_name']) ?>
                            <?php
                            $note = trim($item['note'] ?? '');
                            if ($note && !preg_match('/^Set:\s*.+$/', $note)):
                            ?>
                            <div class="item-note">↳ <?= e($note) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="col-qty"><?= $item['quantity'] ?></td>
                        <td class="col-price"><?= number_format($item['item_price'], 0, ',', '.') ?></td>
                        <td class="col-total"><?= number_format($item['item_price'] * $item['quantity'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-row subtotal">
                <span class="totals-label">Sub amount :</span>
                <span class="totals-value"><?= number_format($subtotal, 0, ',', '.') ?></span>
            </div>
            <div class="totals-row">
                <span class="totals-label">VAT :</span>
                <span class="totals-value"><?= number_format($vat, 0, ',', '.') ?></span>
            </div>
            <div class="totals-row total">
                <span class="totals-label">Total amount :</span>
                <span class="totals-value"><?= number_format($total, 0, ',', '.') ?></span>
            </div>
        </div>
        
        <!-- Payment Status -->
        <?php
        $isActuallyPaid = ($order['status'] === 'closed' || !empty($_GET['payment_method']));
        ?>
        <div class="payment-status <?= $isActuallyPaid ? 'paid' : 'unpaid' ?>">
            <?= $isActuallyPaid ? 'ĐÃ THANH TOÁN / PAID' : 'CHƯA THANH TOÁN / UNPAID' ?>
        </div>
        
        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="label">Guest Signature/</div>
                <div class="label">Chữ ký khách</div>
                <div class="space"></div>
            </div>
            <div class="signature-box">
                <div class="label">Cashier Signature/</div>
                <div class="label">Chữ ký thu ngân</div>
                <div class="space"></div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Thank you and see you again!</p>
            <p>Cảm ơn quý khách và hẹn gặp lại!</p>
        </div>
    </div>
    
    <script>
        // Auto print on load
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>