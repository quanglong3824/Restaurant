<?php
// ============================================================
// SettingController — IT: Full User Management
// ============================================================

require_once BASE_PATH . '/models/User.php';

class SettingController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /** GET /it/users */
    public function users(): void
    {
        Auth::requireRole(ROLE_IT);
        $users = $this->userModel->getAll();
        $this->view('layouts/admin', [
            'view' => 'it/users',
            'pageTitle' => 'Quản lý Nhân viên',
            'pageSubtitle' => count($users) . ' tài khoản',
            'users' => $users,
            'editUser' => null,
        ]);
    }

    /** GET /it/users/edit?id= */
    public function editUser(): void
    {
        Auth::requireRole(ROLE_IT);

        $id = (int) $this->input('id');
        $editUser = $this->userModel->findById($id);
        if (!$editUser) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không tìm thấy nhân viên.'];
            $this->redirect('/it/users');
        }

        $users = $this->userModel->getAll();
        $this->view('layouts/admin', [
            'view' => 'it/users',
            'pageTitle' => 'Quản lý Nhân viên',
            'pageSubtitle' => count($users) . ' tài khoản',
            'users' => $users,
            'editUser' => $editUser,
        ]);
    }

    /** POST /it/users/store */
    public function storeUser(): void
    {
        Auth::requireRole(ROLE_IT);

        $pin = trim((string) $this->input('pin', ''));
        if (strlen($pin) !== 4 || !ctype_digit($pin)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'PIN phải là đúng 4 chữ số.'];
            $this->redirect('/it/users');
        }

        $name = trim((string) $this->input('name', ''));
        $username = trim((string) $this->input('username', ''));

        if (!$name || !$username) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Họ tên và username không được để trống.'];
            $this->redirect('/it/users');
        }

        $this->userModel->create([
            'name' => $name,
            'username' => $username,
            'pin' => $pin,
            'role' => $this->input('role', ROLE_WAITER),
        ]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã thêm nhân viên!'];
        $this->redirect('/it/users');
    }

    /** POST /it/users/update */
    public function updateUser(): void
    {
        Auth::requireRole(ROLE_IT);

        $id = (int) $this->input('id');
        $name = trim((string) $this->input('name', ''));
        $username = trim((string) $this->input('username', ''));
        $pin = trim((string) $this->input('pin', ''));

        if (!$name || !$username) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Họ tên và username không được để trống.'];
            $this->redirect('/it/users');
        }
        if ($pin !== '' && (strlen($pin) !== 4 || !ctype_digit($pin))) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'PIN phải là đúng 4 chữ số hoặc để trống để giữ nguyên.'];
            $this->redirect('/it/users');
        }

        // Nếu không nhập PIN mới → giữ nguyên PIN cũ
        $current = $this->userModel->findById($id);
        $this->userModel->update($id, [
            'name' => $name,
            'username' => $username,
            'pin' => $pin !== '' ? $pin : $current['pin'],
            'role' => $this->input('role', ROLE_WAITER),
            'is_active' => (int) $this->input('is_active', 1),
        ]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã cập nhật nhân viên!'];
        $this->redirect('/it/users');
    }

    /** POST /it/users/delete */
    public function deleteUser(): void
    {
        Auth::requireRole(ROLE_IT);

        $id = (int) $this->input('id');
        if ($id === Auth::user()['id']) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không thể xóa tài khoản đang đăng nhập.'];
        } else {
            $this->userModel->delete($id);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xóa nhân viên.'];
        }
        $this->redirect('/it/users');
    }
}
