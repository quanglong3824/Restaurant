<?php // views/orders/print.php ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn Bàn <?= e($table['name']) ?></title>
    <style>
        /* CSS cho máy in nhiệt 80mm */
        @page { margin: 0; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 15px;
            width: 80mm;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        
        .header h1 { font-size: 16px; margin: 0 0 5px; }
        .header p { margin: 0; font-size: 11px; }

        .meta-info { font-size: 11px; display: flex; justify-content: space-between; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { border-bottom: 1px dashed #000; padding-bottom: 5px; text-align: left; }
        td { padding: 5px 0; vertical-align: top; }
        .col-qty { width: 15%; text-align: center; }
        .col-price { width: 25%; text-align: right; }
        .col-total { width: 25%; text-align: right; }
        
        .total-row { font-size: 14px; font-weight: bold; display: flex; justify-content: space-between; margin-top: 10px; }
        .footer { text-align: center; margin-top: 20px; font-size: 11px; }
    </style>
</head>
<body onload="window.print();">

    <div class="header text-center mb-2">
        <h1 class="font-bold">AURORA RESTAURANT</h1>
        <p>123 Đường XYZ, Quận 1, TP. HCM</p>
        <p>Tel: 0123.456.789</p>
    </div>

    <div class="divider"></div>

    <div class="text-center font-bold mb-2" style="font-size: 14px;">PHIẾU THANH TOÁN</div>

    <div class="meta-info mb-1">
        <span>Bàn: <strong><?= e($table['name']) ?></strong></span>
        <span>Số khách: <?= $order['guest_count'] ?></span>
    </div>
    <div class="meta-info mb-1">
        <span>Phục vụ: <?= e($order['waiter_name'] ?? 'N/A') ?></span>
        <span>Giờ mở: <?= date('H:i', strtotime($order['opened_at'])) ?></span>
    </div>
    <div class="meta-info">
        <span>Ngày: <?= date('d/m/Y') ?></span>
        <span>Giờ in: <?= date('H:i') ?></span>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th>Tên món</th>
                <th class="col-qty">SL</th>
                <th class="col-price">Đơn giá</th>
                <th class="col-total">T.Tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= e($item['item_name']) ?></td>
                <td class="col-qty"><?= $item['quantity'] ?></td>
                <td class="col-price"><?= number_format($item['item_price'], 0, ',', '.') ?></td>
                <td class="col-total"><?= number_format($item['item_price'] * $item['quantity'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="total-row">
        <span>TỔNG CỘNG:</span>
        <span><?= formatPrice($total) ?></span>
    </div>

    <div class="divider"></div>

    <div class="footer">
        <p>Cảm ơn quý khách và hẹn gặp lại!</p>
        <p>Powered by Aurora System</p>
    </div>

</body>
</html>
