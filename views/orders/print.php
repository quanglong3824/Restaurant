<?php // views/orders/print.php - A5 Guest Check - Single Page ?>
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
            margin: 10mm;
        }
        
        /* Remove browser header/footer */
        @page :first {
            margin-top: 10mm;
        }
        
        @page :left {
            margin-left: 10mm;
        }
        
        @page :right {
            margin-right: 10mm;
        }
        
        body::before, body::after {
            display: none;
            content: none;
        }
        
        /* Hide browser headers/footers */
        @media print {
            @page {
                margin: 10mm;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            /* Remove default browser headers and footers */
            html {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #000;
            background: #fff;
        }
        
        .container {
            width: 100%;
            max-width: 148mm;
            margin: 0 auto;
            padding: 5mm;
        }
        
        /* Header Section - Compact */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
        }
        
        .header-left {
            flex: 1;
            font-size: 8pt;
            line-height: 1.4;
        }
        
        .header-left .company-name {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        
        .header-left .hotel-name {
            font-size: 9pt;
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .header-left .address {
            margin-bottom: 2px;
        }
        
        .header-left .contact {
            margin-bottom: 1px;
        }
        
        .header-left .outlet {
            margin-top: 3px;
            font-weight: bold;
        }
        
        .header-right {
            text-align: right;
            margin-left: 10px;
        }
        
        .header-right .logo {
            width: 80px;
            height: auto;
        }
        
        /* Meta Info - Single line */
        .meta-info {
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            margin: 5px 0;
            padding: 3px 0;
            border-bottom: 1px dashed #ccc;
        }
        
        /* Invoice label */
        .invoice-label {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 5px 0;
            text-align: center;
        }
        
        /* Title */
        .title-section {
            text-align: center;
            margin: 8px 0;
        }
        
        .title-vi {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .title-en {
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Table - Compact */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 8.5pt;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 4px 5px;
        }
        
        .items-table th {
            font-weight: bold;
            text-align: center;
            background: #f5f5f5;
            font-size: 8pt;
        }
        
        .items-table th.col-name {
            text-align: left;
            width: 45%;
        }
        
        .items-table th.col-qty {
            width: 12%;
        }
        
        .items-table th.col-price {
            width: 21%;
        }
        
        .items-table th.col-total {
            width: 22%;
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
        
        .item-name {
            font-weight: normal;
        }
        
        .item-note {
            font-size: 7.5pt;
            color: #555;
            font-style: italic;
            margin-top: 2px;
        }
        
        /* Totals - Compact */
        .totals-section {
            margin-top: 5px;
            border: 1px solid #000;
            padding: 5px;
        }
        
        .totals-row {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 2px 0;
            font-size: 8.5pt;
        }
        
        .totals-label {
            text-align: right;
            margin-right: 15px;
            min-width: 100px;
        }
        
        .totals-value {
            text-align: right;
            min-width: 70px;
        }
        
        .totals-row.total {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 3px;
        }
        
        /* Payment Status - Compact */
        .payment-status {
            margin-top: 8px;
            padding: 4px;
            border: 1px solid #000;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
        }
        
        .payment-status.paid {
            background: #e8f5e9;
        }
        
        .payment-status.unpaid {
            background: #fff3e0;
        }
        
        /* Signature Section - Compact */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 8pt;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
        }
        
        .signature-box .label {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .signature-box .space {
            height: 35px;
        }
        
        /* Footer - Compact */
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 8pt;
            font-style: italic;
            border-top: 1px dashed #ccc;
            padding-top: 8px;
        }
        
        .footer .restaurant-name {
            font-size: 9pt;
            font-weight: bold;
            font-style: normal;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        /* Page break prevention */
        .container {
            page-break-inside: avoid;
        }
        
        .items-table {
            page-break-inside: avoid;
        }
        
        /* Print utilities */
        .no-print {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header with Company Info and Logo -->
        <div class="header">
            <div class="header-left">
                <div class="company-name">CÔNG TY CP PHÁT TRIỂN TRI THỨC VIỆT</div>
                <div class="hotel-name">AURORA HOTEL & PLAZA</div>
                <div class="address">253 Phạm Văn Thuận, KP 2, P.Tam Hiệp, Biên Hòa, ĐN</div>
                <div class="contact">Tel:(0251)3918888 - Fax:(0251)3918300</div>
                <div class="contact">Email:info@aurorahotelplaza.com</div>
                <div class="outlet">Outlet: Grand Restaurant</div>
            </div>
            <div class="header-right">
                <img src="<?= BASE_URL ?>/public/src/logo/logo-dark-ui.png" alt="AURORA HOTEL PLAZA" class="logo"
                    onerror="this.style.display='none'">
            </div>
        </div>
        
        <!-- Invoice Label -->
        <div class="invoice-label">HÓA ĐƠN</div>
        
        <!-- Title -->
        <div class="title-section">
            <div class="title-vi">BẢNG KÊ CHI TIẾT</div>
            <div class="title-en">GUEST CHECK</div>
        </div>
        
        <!-- Meta Info -->
        <div class="meta-info">
            <span>Date: <?= date('d/m/Y') ?> &nbsp;&nbsp; Time: <?= date('H:i') ?></span>
            <span>Table: <?= e($tableDisplayName) ?></span>
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
                            <span class="item-name"><?= e($item['item_name']) ?></span>
                            <?php
                            $note = trim($item['note'] ?? '');
                            if ($note && !preg_match('/^Set:\s*.+$/', $note)):
                            ?>
                                <div class="item-note">↳ <?= e($note) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="col-qty"><?= $item['quantity'] ?></td>
                        <td class="col-price"><?= number_format($item['item_price'], 0, ',', '.') ?></td>
                        <td class="col-total"><?= number_format($item['item_price'] * $item['quantity'], 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-row">
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
            <div class="restaurant-name">AURORA RESTAURANT</div>
            <p>Hoá đơn được in từ Aurora Restaurant</p>
        </div>
    </div>
    
    <script>
        // Auto print on load
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 500);
        };
    </script>
</body>

</html>