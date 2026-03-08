<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Không có quyền</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f1117;
            color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 1rem
        }

        .icon {
            font-size: 4rem;
            color: #f59e0b;
            margin-bottom: 1.5rem
        }

        h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: .5rem
        }

        p {
            color: #9ca3af;
            margin-bottom: 1.5rem
        }

        a {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .7rem 1.5rem;
            background: linear-gradient(135deg, #d4af37, #b8941f);
            color: #fff;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700
        }
    </style>
</head>

<body>
    <div>
        <div class="icon"><i class="fas fa-shield-alt"></i></div>
        <h1>403 — Không có quyền</h1>
        <p>Bạn không có quyền truy cập trang này.</p>
        <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>/"><i class="fas fa-home"></i> Trang chủ</a>
    </div>
</body>

</html>