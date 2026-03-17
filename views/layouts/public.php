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
    <link rel="stylesheet" href="<?= asset('public/css/app.css') ?>">
    <!-- Layout CSS -->
    <link rel="stylesheet" href="<?= asset('public/css/layout/public.css') ?>">
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

    <!-- App JS (defer - không block HTML parse) -->
    <script src="<?= asset('public/js/app.js') ?>" defer></script>
    <!-- Layout JS (defer - không block HTML parse) -->
    <script src="<?= asset('public/js/layout/public.js') ?>" defer></script>
</body>

</html>