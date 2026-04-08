<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#c5a059">
    <title><?= e($pageTitle ?? 'Menu') ?> — Aurora Restaurant</title>

    <!-- App Icons & iOS Web App Meta -->
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/src/logo/favicon.png">
    <link rel="apple-touch-icon" href="<?= BASE_URL ?>/public/src/logo/favicon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?= e(APP_NAME) ?>">

    <!-- Layout CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/layout/public.css">

    <!-- Google Fonts: Outfit & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Global JS Config -->
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
    </script>

</head>

<body>
    <!-- Initial Loading -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Main Content View -->
    <?php require BASE_PATH . "/views/{$view}.php"; ?>

    <script>
        // Hide loading when everything is ready
        window.addEventListener('load', () => {
            const overlay = document.getElementById('loadingOverlay');
            overlay.style.opacity = '0';
            setTimeout(() => overlay.remove(), 500);
        });
    </script>
</body>

</html>
