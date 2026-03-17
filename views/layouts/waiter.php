<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#d4af37">
    <title><?= e($pageTitle ?? 'Aurora Restaurant') ?> — Aurora</title>

    <!-- Google Fonts: Outfit & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('public/css/waiter.css') ?>">
    <link rel="stylesheet" href="<?= asset('public/css/layout/waiter-notify.css') ?>">
    <?php if (isset($pageCSS)): ?>
        <link rel="stylesheet" href="<?= asset('public/css/' . e($pageCSS) . '.css') ?>">
    <?php endif; ?>
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
    </script>
</head>

<body class="waiter-layout">

    <!-- Top Bar -->
    <header class="waiter-topbar" role="banner">
        <div class="topbar-left">
            <div class="topbar-brand">
                <i class="fas fa-utensils" aria-hidden="true"></i>
                <span>Aurora</span>
            </div>
        </div>
        <div class="topbar-center">
            <span class="topbar-page"><?= e($pageTitle ?? '') ?></span>
        </div>
        <div class="topbar-right">

            <!-- Notifications -->
            <div class="topbar-noti" onclick="document.getElementById('notiDropdown').classList.toggle('show')">
                <i class="fas fa-bell"></i>
                <span class="noti-badge" id="notiBadge">0</span>

                <div class="noti-dropdown" id="notiDropdown" onclick="event.stopPropagation()">
                    <div class="noti-header">Yêu cầu từ Khách hàng</div>
                    <div class="noti-list" id="notiList">
                        <div style="padding:15px; text-align:center; color:var(--text-dim); font-size:0.85rem;">Không có
                            yêu cầu nào.</div>
                    </div>
                </div>
            </div>

            <div class="topbar-user">
                <i class="fas fa-user-circle" aria-hidden="true"></i>
                <span><?= e(Auth::user()['name'] ?? '') ?></span>
            </div>
            <a href="<?= BASE_URL ?>/auth/logout" class="topbar-logout" aria-label="Đăng xuất">
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="waiter-main" id="main-content">

        <!-- Flash Message -->
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="alert alert-<?= e($_SESSION['flash']['type']) ?>" style="margin: .75rem 1rem 0;"
                data-autohide="3000" role="alert">
                <i class="fas fa-<?= $_SESSION['flash']['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"
                    aria-hidden="true"></i>
                <?= e($_SESSION['flash']['message']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?php require BASE_PATH . "/views/{$view}.php"; ?>
    </main>

    <!-- Bottom Navigation - Floating with Apple Liquid Glass Design -->
    <nav class="waiter-bottomnav" role="navigation" aria-label="Menu chính">
        <a href="<?= BASE_URL ?>/tables" class="bottomnav-item <?= activeClass('/tables') ?>" aria-label="Sơ đồ bàn">
            <span class="liquid-ring"></span>
            <i class="fas fa-table-cells-large" aria-hidden="true"></i>
            <span>Bàn</span>
        </a>
        <a href="<?= BASE_URL ?>/menu" class="bottomnav-item <?= activeClass('/menu') ?>" aria-label="Menu">
            <span class="liquid-ring"></span>
            <i class="fas fa-book-open" aria-hidden="true"></i>
            <span>Menu</span>
        </a>
        <a href="<?= BASE_URL ?>/orders" class="bottomnav-item <?= activeClass('/orders') ?>" aria-label="Order">
            <span class="liquid-ring"></span>
            <i class="fas fa-receipt" aria-hidden="true"></i>
            <span>Order</span>
        </a>
        <a href="<?= BASE_URL ?>/orders/history" class="bottomnav-item <?= activeClass('/orders/history') ?>"
            aria-label="Lịch sử">
            <span class="liquid-ring"></span>
            <i class="fas fa-history" aria-hidden="true"></i>
            <span>Lịch sử</span>
        </a>
    </nav>

    <!-- Chat AI Float Button & UI (Temporarily Hidden) -->
    <?php /*
<a href="javascript:void(0)" onclick="toggleAiChat()" class="ai-float-btn" aria-label="AI Assistant"
...
   </div>
</div>
*/ ?>

    <!-- Layout JS (tải trước nội dung trang) -->
    <script src="<?= asset('public/js/layout/waiter-notify.js') ?>" defer></script>
    <script src="<?= asset('public/js/layout/waiter-ai.js') ?>" defer></script>

    <!-- App JS -->
    <script src="<?= asset('public/js/app.js') ?>" defer></script>
    <?php if (isset($pageJS)): ?>
        <script src="<?= asset('public/js/' . e($pageJS) . '.js') ?>" defer></script>
    <?php endif; ?>
</body>

</html>
