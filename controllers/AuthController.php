<?php
// ============================================================
// AuthController — PIN Login & Redirect
// ============================================================

require_once BASE_PATH . '/models/User.php';

class AuthController extends Controller
{
    /**
     * GET / — Redirect theo role sau khi đăng nhập
     */
    public function home(): void
    {
        if (!Auth::check()) {
            $this->redirect('/auth/login');
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

        // Lấy danh sách phục vụ để chọn nhanh trên iPad
        $userModel = new User();
        $waiters = $userModel->getActiveWaiters();

        $this->view('auth/login', [
            'pageTitle' => 'Đăng nhập',
            'waiters' => $waiters,
        ]);
    }

    /**
     * POST /auth/login — Xử lý PIN login
     */
    public function handleLogin(): void
    {
        $username = trim($this->input('username', ''));
        $pin = trim($this->input('pin', ''));

        if (empty($username) || empty($pin)) {
            $_SESSION['login_error'] = 'Vui lòng chọn tên và nhập PIN.';
            $this->redirect('/auth/login');
        }

        $userModel = new User();
        $user = $userModel->findByCredentials($username, $pin);

        if (!$user) {
            $_SESSION['login_error'] = 'PIN không đúng. Vui lòng thử lại.';
            $this->redirect('/auth/login');
        }

        Auth::login($user);
        $this->home();
    }

    /**
     * GET /auth/logout
     */
    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/auth/login');
    }
}
