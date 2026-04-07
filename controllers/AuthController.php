<?php
// ============================================================
// AuthController — PIN Login & Redirect
// ============================================================

require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/ActivityLog.php';

class AuthController extends Controller
{
    /**
     * GET /home — Landing page for iOS Home Screen
     */
    public function landing(): void
    {
        $this->view('layouts/public', [
            'view' => 'home',
            'pageTitle' => 'Aurora Restaurant',
        ]);
    }

    /**
     * GET / — Redirect theo role sau khi đăng nhập
     */
    public function home(): void
    {
        if (!Auth::check()) {
            $this->landing();
            return;
        }

        // Redirect theo role
        match (Auth::role()) {
            ROLE_WAITER => $this->redirect('/tables'),
            ROLE_ADMIN => $this->redirect('/admin/menu'),
            ROLE_IT => $this->redirect('/it/users'),
            default => $this->redirect('/auth/login'),
        };
    }

    /**
     * GET /auth/login — Hiển thị màn hình PIN login
     */
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->home();
        }

        // Lấy danh sách nhân viên
        $userModel = new User();
        $staff = $userModel->getActiveStaff();

        // Lấy danh sách ca trực
        $db = getDB();
        $shifts = $db->query("SELECT * FROM shifts ORDER BY start_time ASC")->fetchAll();

        // Nếu chưa có ca trực nào, tự động tạo ca mặc định
        if (empty($shifts)) {
            $defaultShifts = [
                ['Ca Sáng',  '06:00:00', '14:00:00'],
                ['Ca Chiều', '14:00:00', '22:00:00'],
                ['Ca Tối',   '22:00:00', '06:00:00'],
            ];
            $stmt = $db->prepare("INSERT INTO shifts (name, start_time, end_time) VALUES (?, ?, ?)");
            foreach ($defaultShifts as $s) {
                $stmt->execute($s);
            }
            $shifts = $db->query("SELECT * FROM shifts ORDER BY start_time ASC")->fetchAll();
        }

        $this->view('auth/login', [
            'pageTitle' => 'Đăng nhập',
            'staff' => $staff,
            'shifts' => $shifts,
        ]);
    }

    /**
     * POST /auth/login — Xử lý PIN login
     */
    public function handleLogin(): void
    {
        $username = trim($this->input('username', ''));
        $pin = trim($this->input('pin', ''));
        $shiftId = (int) $this->input('shift_id', 0);
        $activityLog = new ActivityLog();

        if (empty($username) || empty($pin)) {
            $activityLog->logLogin(0, false, 'Missing credentials');
            $_SESSION['login_error'] = 'Vui lòng nhập tên đăng nhập và mã PIN.';
            $this->redirect('/auth/login');
        }

        $userModel = new User();
        $user = $userModel->findByCredentials($username, $pin);

        if (!$user) {
            $activityLog->logLogin(0, false, 'Invalid PIN for user: ' . $username);
            $_SESSION['login_error'] = 'PIN không đúng. Vui lòng thử lại.';
            $this->redirect('/auth/login');
        }

        // Kiểm tra ca trực (Chỉ bắt buộc với Waiter)
        if ($user['role'] === ROLE_WAITER && $shiftId <= 0) {
            $activityLog->logLogin($user['id'], false, 'No shift selected');
            $_SESSION['login_error'] = 'Vui lòng chọn ca trực của bạn.';
            $this->redirect('/auth/login');
        }

        // Lưu thông tin ca trực vào session
        Auth::login($user);
        $_SESSION['user_shift_id'] = $shiftId;

        // Log successful login
        $activityLog->logLogin($user['id'], true);

        $this->home();
    }

    /**
     * GET /auth/logout
     */
    public function logout(): void
    {
        // Log logout before destroying session
        if (Auth::isLoggedIn()) {
            $activityLog = new ActivityLog();
            $activityLog->logLogout(Auth::user()['id']);
        }
        
        Auth::logout();
        $this->redirect('/auth/login');
    }
}
