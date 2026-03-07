<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#d4af37">
    <title><?= e($pageTitle ?? 'Aurora Restaurant') ?> — Aurora</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/waiter.css">
    <?php if (isset($pageCSS)): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/<?= e($pageCSS) ?>.css">
    <?php endif; ?>
    <style>
        /* Notifications */
        .topbar-noti {
            position: relative;
            cursor: pointer;
            margin-right: 15px;
            font-size: 1.2rem;
            color: var(--text-muted);
        }
        .topbar-noti:hover { color: var(--gold); }
        .noti-badge {
            position: absolute;
            top: -5px; right: -8px;
            background: var(--danger);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 5px;
            border-radius: 10px;
            display: none;
        }
        .noti-dropdown {
            position: absolute;
            top: 40px; right: -50px;
            width: 320px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0px; /* Flat design */
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            display: none;
            flex-direction: column;
            z-index: 1000;
        }
        .noti-dropdown.show { display: flex; }
        .noti-header {
            padding: 12px 15px;
            background: var(--surface-2);
            font-weight: 700;
            border-bottom: 1px solid var(--border);
        }
        .noti-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .noti-item {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .noti-item:last-child { border-bottom: none; }
        .noti-item-info { font-size: 0.85rem; }
        .noti-item-info strong { color: var(--gold-dark); font-size: 0.95rem;}
        .noti-btn {
            background: var(--success);
            color: #fff;
            border: none;
            padding: 6px 10px;
            border-radius: 0px; /* Flat design */
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
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
                        <div style="padding:15px; text-align:center; color:var(--text-dim); font-size:0.85rem;">Không có yêu cầu nào.</div>
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

    <!-- Bottom Navigation -->
    <nav class="waiter-bottomnav" role="navigation" aria-label="Menu chính">
        <a href="<?= BASE_URL ?>/tables" class="bottomnav-item <?= activeClass('/tables') ?>" aria-label="Sơ đồ bàn">
            <i class="fas fa-table-cells-large" aria-hidden="true"></i>
            <span>Bàn</span>
        </a>
        <a href="<?= BASE_URL ?>/menu" class="bottomnav-item <?= activeClass('/menu') ?>" aria-label="Menu">
            <i class="fas fa-book-open" aria-hidden="true"></i>
            <span>Menu</span>
        </a>
        <a href="<?= BASE_URL ?>/orders" class="bottomnav-item <?= activeClass('/orders') ?>" aria-label="Order">
            <i class="fas fa-receipt" aria-hidden="true"></i>
            <span>Order</span>
        </a>
    </nav>

    <!-- Chat AI Float Button & UI -->
    <a href="javascript:void(0)" onclick="toggleAiChat()" class="ai-float-btn" aria-label="AI Assistant"
        style="position:fixed; bottom: 85px; right: 20px; width: 60px; height: 60px; background: var(--gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; text-decoration: none; box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4); z-index: 1000; transition: transform 0.2s;">
        <i class="fas fa-comment-dots"></i>
    </a>

    <div id="aiChatWindow"
        style="display:none; position:fixed; bottom: 150px; right: 20px; width: 340px; height: 450px; background: var(--surface); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); z-index: 1001; flex-direction: column; overflow: hidden; border: 1px solid var(--border);">
        <div
            style="background: var(--gold); padding: 15px; color: white; border-top-left-radius: 12px; border-top-right-radius: 12px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display:flex; align-items:center; gap: 10px;">
                <div
                    style="width: 30px; height: 30px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-robot"></i></div>
                <strong style="font-size: 1rem;">Aurora AI</strong>
            </div>
            <button onclick="toggleAiChat()"
                style="background:none; border:none; color:white; font-size: 1.2rem; cursor:pointer;"><i
                    class="fas fa-times"></i></button>
        </div>
        <div id="aiChatBody"
            style="flex: 1; padding: 15px; overflow-y: auto; background: var(--bg); display: flex; flex-direction: column; gap: 12px;">
            <!-- Message received -->
            <div style="display: flex; gap: 10px;">
                <div
                    style="width: 25px; height: 25px; border-radius: 50%; background: var(--gold); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0;">
                    <i class="fas fa-robot"></i></div>
                <div
                    style="background: var(--surface); padding: 10px 14px; border-radius: 14px; border-top-left-radius: 4px; font-size: 0.9rem; color: var(--text); border: 1px solid var(--border); box-shadow: 0 2px 4px rgba(0,0,0,0.02); max-width: 85%;">
                    Xin chào! Tôi có thể giúp gì cho bạn hôm nay?
                </div>
            </div>
            <!-- Message received -->
            <div style="display: flex; gap: 10px;">
                <div
                    style="width: 25px; height: 25px; border-radius: 50%; background: var(--gold); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0;">
                    <i class="fas fa-robot"></i></div>
                <div
                    style="background: var(--surface); padding: 10px 14px; border-radius: 14px; border-top-left-radius: 4px; font-size: 0.9rem; color: var(--text); border: 1px solid var(--border); box-shadow: 0 2px 4px rgba(0,0,0,0.02); max-width: 85%;">
                    Phiên bản AI API sẽ sớm được kết nối!
                </div>
            </div>
        </div>
        <div style="padding: 15px; background: var(--surface); border-top: 1px solid var(--border);">
            <div style="display: flex; gap: 8px;">
                <input type="text" id="aiChatInput" placeholder="Nhập tin nhắn..."
                    onkeydown="if(event.key==='Enter') sendAiMsg()"
                    style="flex: 1; padding: 10px 15px; border-radius: 20px; border: 1px solid var(--border); background: var(--bg); color: var(--text); outline: none;">
                <button onclick="sendAiMsg()"
                    style="background: var(--gold); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s;"><i
                        class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <script>
        function toggleAiChat() {
            const chatObj = document.getElementById('aiChatWindow');
            chatObj.style.display = chatObj.style.display === 'none' ? 'flex' : 'none';
        }
        function sendAiMsg() {
            const input = document.getElementById('aiChatInput');
            const txt = input.value.trim();
            if (!txt) return;
            const body = document.getElementById('aiChatBody');
            body.innerHTML += `
            <div style="display: flex; justify-content: flex-end;">
                <div style="background: var(--gold); color: white; padding: 10px 14px; border-radius: 14px; border-top-right-radius: 4px; font-size: 0.9rem; max-width: 85%; box-shadow: 0 2px 4px rgba(212, 175, 55, 0.2);">
                    ${txt.replace(/</g, '&lt;')}
                </div>
            </div>`;
            input.value = '';
            body.scrollTop = body.scrollHeight;
            setTimeout(() => {
                body.innerHTML += `
                <div style="display: flex; gap: 10px;">
                    <div style="width: 25px; height: 25px; border-radius: 50%; background: var(--gold); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0;"><i class="fas fa-robot"></i></div>
                    <div style="background: var(--surface); padding: 10px 14px; border-radius: 14px; border-top-left-radius: 4px; font-size: 0.9rem; color: var(--text); border: 1px solid var(--border); max-width: 85%;">
                        <i class="fas fa-ellipsis-h fa-fade"></i> AI đang suy nghĩ...
                    </div>
                </div>`;
                body.scrollTop = body.scrollHeight;
            }, 600);
        }
    </script>

    <script src="<?= BASE_URL ?>/public/js/app.js" defer></script>
    <?php if (isset($pageJS)): ?>
        <script src="<?= BASE_URL ?>/public/js/<?= e($pageJS) ?>.js" defer></script>
    <?php endif; ?>

    <script>
        // Notification Polling System
        function fetchNotifications() {
            fetch('<?= BASE_URL ?>/support/pending')
                .then(res => res.json())
                .then(res => {
                    if (res.ok) {
                        renderNotifications(res.data);
                    }
                })
                .catch(err => console.error('Lỗi lấy thông báo:', err));
        }

        function renderNotifications(data) {
            const badge = document.getElementById('notiBadge');
            const list = document.getElementById('notiList');
            
            if (data.length > 0) {
                badge.style.display = 'inline-block';
                badge.textContent = data.length;
                let html = '';
                data.forEach(item => {
                    const icon = item.type === 'payment' ? '<i class="fas fa-file-invoice-dollar" style="color:var(--danger)"></i> Yêu cầu tính tiền' : '<i class="fas fa-concierge-bell" style="color:var(--gold)"></i> Gọi phục vụ';
                    html += `
                        <div class="noti-item">
                            <div class="noti-item-info">
                                <strong>Bàn ${item.table_name}</strong> (Khu ${item.area})<br>
                                ${icon}
                            </div>
                            <button class="noti-btn" onclick="resolveNotification(${item.id})">Xong</button>
                        </div>
                    `;
                });
                list.innerHTML = html;
            } else {
                badge.style.display = 'none';
                list.innerHTML = '<div style="padding:15px; text-align:center; color:var(--text-dim); font-size:0.85rem;">Không có yêu cầu nào.</div>';
            }
        }

        function resolveNotification(id) {
            const data = new FormData();
            data.append('id', id);
            
            fetch('<?= BASE_URL ?>/support/resolve', {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(res => {
                if (res.ok) fetchNotifications();
            });
        }

        // Click outside to close dropdown
        window.addEventListener('click', function(e) {
            if (!document.querySelector('.topbar-noti').contains(e.target)) {
                document.getElementById('notiDropdown').classList.remove('show');
            }
        });

        // Start polling every 10 seconds
        setInterval(fetchNotifications, 10000);
        fetchNotifications(); // Initial fetch
    </script>
</body>


</html>