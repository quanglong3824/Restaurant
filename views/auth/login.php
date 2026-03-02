<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="description" content="Đăng nhập hệ thống Aurora Restaurant">
    <title>Đăng nhập — Aurora Restaurant</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/auth.css">
</head>
<body>

<div class="auth-bg" aria-hidden="true"></div>

<div class="auth-card" role="main">

    <!-- Logo -->
    <div class="auth-logo">
        <div class="auth-logo__icon" aria-hidden="true">
            <i class="fas fa-utensils"></i>
        </div>
        <h1 class="auth-logo__title">Aurora Restaurant</h1>
        <p class="auth-logo__sub">Đăng nhập để tiếp tục</p>
    </div>

    <!-- Error alert -->
    <?php if (!empty($_SESSION['login_error'])): ?>
            <div class="auth-alert auth-alert--error" role="alert">
                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                <?= e($_SESSION['login_error']) ?>
            </div>
            <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/auth/login" id="loginForm" novalidate>

        <!-- Hidden fields -->
        <input type="hidden" name="username" id="usernameField">
        <input type="hidden" name="pin"      id="pinField">

        <!-- ── Waiter Mode: Quick select ── -->
        <div id="waiterSection">
            <p class="field-label">
                <i class="fas fa-user" aria-hidden="true"></i>
                Chọn nhân viên
            </p>
            <div class="user-grid" role="listbox" aria-label="Chọn nhân viên">
                <?php foreach ($waiters as $w): ?>
                        <button
                            type="button"
                            class="user-chip"
                            role="option"
                            data-username="<?= e($w['username']) ?>"
                            aria-pressed="false">
                            <?= e($w['name']) ?>
                        </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ── Admin/IT Mode: Username input ── -->
        <div id="adminSection" class="u-hidden">
            <p class="field-label">
                <i class="fas fa-user-shield" aria-hidden="true"></i>
                Tên đăng nhập
            </p>
            <div class="input-wrap">
                <i class="input-wrap__icon fas fa-at" aria-hidden="true"></i>
                <input
                    type="text"
                    id="adminUsername"
                    class="form-input"
                    placeholder="Nhập username..."
                    autocomplete="username"
                    autocorrect="off"
                    autocapitalize="off"
                    spellcheck="false">
            </div>
        </div>

        <!-- Toggle admin mode -->
        <div class="admin-toggle" role="button" tabindex="0" aria-label="Chuyển chế độ đăng nhập">
            <i class="fas fa-shield-alt" aria-hidden="true"></i>
            <span id="adminToggleText">Đăng nhập Admin / IT</span>
        </div>

        <!-- ── PIN Entry ── -->
        <p class="field-label">
            <i class="fas fa-lock" aria-hidden="true"></i>
            Nhập PIN (4 số)
        </p>

        <!-- PIN dots indicator -->
        <div class="pin-dots" aria-live="polite" aria-label="Trạng thái PIN">
            <div class="pin-dot" aria-hidden="true"></div>
            <div class="pin-dot" aria-hidden="true"></div>
            <div class="pin-dot" aria-hidden="true"></div>
            <div class="pin-dot" aria-hidden="true"></div>
        </div>

        <!-- PIN numpad -->
        <div class="pin-pad" role="group" aria-label="Bàn phím số">
            <?php foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $n): ?>
                    <button type="button" class="pin-key" data-key="<?= $n ?>" aria-label="<?= $n ?>"><?= $n ?></button>
            <?php endforeach; ?>
            <button type="button" class="pin-key pin-key--del" data-key="del" aria-label="Xóa">
                <i class="fas fa-delete-left" aria-hidden="true"></i>
            </button>
            <button type="button" class="pin-key pin-key--zero" data-key="0" aria-label="0">0</button>
        </div>

        <!-- Submit -->
        <button type="submit" id="submitBtn" class="btn-login" disabled aria-label="Đăng nhập">
            <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
            Đăng nhập
        </button>

    </form>
</div>

<script src="<?= BASE_URL ?>/public/js/auth.js" defer></script>
</body>
</html>