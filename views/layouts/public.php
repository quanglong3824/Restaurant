<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#d4af37">
    <title>
        <?= e($pageTitle ?? 'Menu') ?> — Aurora Restaurant
    </title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- App CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/app.css">

    <style>
        :root {
            --gold: #d4af37;
            --gold-dark: #b8860b;
            --surface: #ffffff;
            --bg: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            padding-bottom: 80px;
            /* Space for bottom cart on mobile */
        }

        .public-header {
            background: #fff;
            padding: 1.2rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .public-logo {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .public-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }

        .table-info-badge {
            background: #000;
            color: #fff;
            padding: 4px 12px;
            border-radius: 0px; /* Flat design */
            font-size: 0.8rem;
            margin-top: 5px;
            display: inline-block;
        }

        /* Override somePOS styles for public view */
        .pos-cart-col {
            position: fixed !important;
            bottom: 0;
            left: 0;
            width: 100% !important;
            z-index: 1000;
            padding: 0 !important;
            top: auto !important;
        }

        .cart-panel {
            border-radius: 0px !important; /* Flat design */
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .pos-layout {
            flex-direction: column !important;
        }

        /* Floating Support Buttons */
        .support-fab-container {
            position: fixed;
            bottom: 120px; /* Above cart */
            right: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 999;
        }

        .support-fab {
            background: var(--surface);
            color: var(--gold-dark);
            border: 2px solid var(--gold);
            padding: 12px 15px;
            border-radius: 0px; /* Flat design */
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(212,175,55,0.2);
            transition: all 0.2s ease;
        }

        .support-fab:active {
            transform: scale(0.95);
            background: var(--gold);
            color: #fff;
        }
        
        .support-fab-payment {
            background: var(--gold);
            color: #fff;
        }
    </style>
</head>

<body>
    <header class="public-header">
        <div class="public-logo">
            <i class="fas fa-utensils"></i>
            <span>AURORA RESTAURANT</span>
        </div>
        <?php if (isset($tableId) && $tableId > 0): ?>
            <div class="table-info-badge">
                <i class="fas fa-chair"></i> Bàn
                <?= e($tableId) ?>
            </div>
        <?php endif; ?>
    </header>

    <main class="public-container">
        <?php require BASE_PATH . "/views/{$view}.php"; ?>
    </main>

    <?php if (isset($tableId) && $tableId > 0): ?>
    <div class="support-fab-container">
        <button class="support-fab" onclick="requestSupport(<?= $tableId ?>, 'support')">
            <i class="fas fa-concierge-bell"></i> Gọi Phục Vụ
        </button>
        <button class="support-fab support-fab-payment" onclick="requestSupport(<?= $tableId ?>, 'payment')">
            <i class="fas fa-file-invoice-dollar"></i> Tính Tiền
        </button>
    </div>
    <?php endif; ?>

    <!-- App JS -->
    <script src="<?= BASE_URL ?>/public/js/app.js"></script>
    <script>
        function requestSupport(tableId, type) {
            const typeName = type === 'payment' ? 'Tính tiền' : 'Gọi phục vụ';
            if (!confirm('Bạn muốn yêu cầu: ' + typeName + '?')) return;

            const data = new FormData();
            data.append('table_id', tableId);
            data.append('type', type);

            fetch('<?= BASE_URL ?>/support/request', {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(res => {
                if (res.ok) {
                    alert(res.message);
                } else {
                    alert(res.message || 'Có lỗi xảy ra.');
                }
            })
            .catch(err => alert('Lỗi kết nối.'));
        }
    </script>
</body>

</html>