<?php // views/home.php — Simple Home Landing ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/home.css">

<div class="home-container">
    <div class="home-content">
        <div class="home-logo">
            <img src="<?= BASE_URL ?>/public/src/logo/logo-dark-ui.png" alt="Aurora Logo">
        </div>
        <h1 class="playfair">AURORA</h1>
        <p class="subtitle">HOTEL PLAZA RESTAURANT</p>
        
        <div class="home-actions">
            <?php if (Auth::isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/tables" class="btn-gold-premium">
                    <i class="fas fa-desktop me-2"></i> VÀO HỆ THỐNG
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login" class="btn-gold-premium">
                    <i class="fas fa-sign-in-alt me-2"></i> ĐĂNG NHẬP
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
