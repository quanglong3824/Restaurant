<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#d4af37">
    <title><?= e($pageTitle ?? 'Admin') ?> — Aurora Restaurant</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- QRCode JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <!-- App CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin.css">
    <?php if (isset($pageCSS)): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/<?= e($pageCSS) ?>.css">
    <?php endif; ?>
</head>

<body class="admin-layout">

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">

        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-utensils"></i>
                <div>
                    <h2>Aurora</h2>
                    <p>Restaurant Manager</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Nav -->
        <nav class="sidebar-nav">

            <?php if (Auth::isAdmin()): ?>
                <div class="nav-section">
                    <div class="nav-section-title">Menu</div>
                    <a href="<?= BASE_URL ?>/admin/menu" class="nav-item <?= activeClass('/admin/menu') ?>">
                        <i class="fas fa-utensils"></i>
                        <span>Quản lý Món</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/categories" class="nav-item <?= activeClass('/admin/categories') ?>">
                        <i class="fas fa-tags"></i>
                        <span>Danh Mục</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/tables" class="nav-item <?= activeClass('/admin/tables') ?>">
                        <i class="fas fa-table-cells-large"></i>
                        <span>Quản lý Bàn</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Báo cáo</div>
                    <a href="<?= BASE_URL ?>/admin/reports" class="nav-item <?= activeClass('/admin/reports') ?>">
                        <i class="fas fa-chart-bar"></i>
                        <span>Thống kê</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">AI Trợ lý</div>
                    <a href="<?= BASE_URL ?>/admin/ai" class="nav-item <?= activeClass('/admin/ai') ?>">
                        <i class="fas fa-robot" style="color:var(--gold)"></i>
                        <span>AI Assistant</span>
                    </a>
                </div>
            <?php endif; ?>

            <?php if (Auth::isIT()): ?>
                <div class="nav-section">
                    <div class="nav-section-title">Hệ thống</div>
                    <a href="<?= BASE_URL ?>/it/users" class="nav-item <?= activeClass('/it/users') ?>">
                        <i class="fas fa-users"></i>
                        <span>Quản lý User</span>
                    </a>
                </div>
            <?php endif; ?>

            <div class="nav-section">
                <div class="nav-section-title">Tài khoản</div>
                <a href="<?= BASE_URL ?>/auth/logout" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Đăng xuất</span>
                </a>
            </div>

        </nav>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <i class="fas fa-user-circle"></i>
                <div>
                    <strong><?= e(Auth::user()['name'] ?? '') ?></strong>
                    <span><?= e(roleLabel(Auth::user()['role'] ?? '')) ?></span>
                </div>
            </div>
        </div>

    </aside>

    <!-- Main Content -->
    <div class="admin-body">

        <!-- Page Header -->
        <div class="admin-topbar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="admin-topbar-title">
                <h1><?= e($pageTitle ?? '') ?></h1>
                <?php if (isset($pageSubtitle)): ?>
                    <p><?= e($pageSubtitle) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Content -->
        <main class="admin-main">
            <?php
            // Flash message
            if (!empty($_SESSION['flash'])):
                $fType = $_SESSION['flash']['type'];
                $fIcon = match ($fType) {
                    'success' => 'check-circle',
                    'warning' => 'exclamation-triangle',
                    'danger' => 'times-circle',
                    default => 'info-circle',
                };
                ?>
                <div class="alert alert-<?= e($fType) ?>" data-autohide="4000" role="alert">
                    <i class="fas fa-<?= $fIcon ?>" aria-hidden="true"></i>
                    <?= e($_SESSION['flash']['message']) ?>
                </div>
                <?php unset($_SESSION['flash']); endif; ?>

            <?php require BASE_PATH . "/views/{$view}.php"; ?>
        </main>

    </div>

    <!-- App JS -->
    <script src="<?= BASE_URL ?>/public/js/app.js" defer></script>
    <?php if (isset($pageJS)): ?>
        <script src="<?= BASE_URL ?>/public/js/<?= e($pageJS) ?>.js" defer></script>
    <?php endif; ?>

</body>

</html>