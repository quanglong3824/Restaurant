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

    /** GET /it/database */
    public function database(): void
    {
        Auth::requireRole(ROLE_IT, ROLE_ADMIN);

        if (!is_dir(BACKUP_PATH)) {
            mkdir(BACKUP_PATH, 0755, true);
        }

        $files = glob(BACKUP_PATH . '*.sql');
        $backups = [];
        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => filesize($file),
                'date' => date('Y-m-d H:i:s', filemtime($file)),
            ];
        }
        // Sắp xếp bản mới nhất lên đầu
        usort($backups, fn($a, $b) => strcmp($b['date'], $a['date']));

        $this->view('layouts/admin', [
            'view' => 'it/database',
            'pageTitle' => 'Cơ sở dữ liệu',
            'pageSubtitle' => 'Quản lý sao lưu và phục hồi hệ thống',
            'backups' => $backups,
        ]);
    }

    /** GET /it/database/backup */
    public function backup(): void
    {
        Auth::requireRole(ROLE_IT, ROLE_ADMIN);

        try {
            $db = getDB();
            $tables = [];
            $result = $db->query('SHOW TABLES');
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            $sql = "-- Aurora Restaurant Database Backup\n";
            $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET NAMES utf8mb4;\n";
            $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

            foreach ($tables as $table) {
                $result = $db->query("SHOW CREATE TABLE `$table`")->fetch();
                $sql .= "DROP TABLE IF EXISTS `$table`;\n";
                $sql .= $result['Create Table'] . ";\n\n";

                $result = $db->query("SELECT * FROM `$table` ");
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $sql .= "INSERT INTO `$table` VALUES (";
                    $values = [];
                    foreach ($row as $value) {
                        $values[] = ($value === null) ? "NULL" : $db->quote($value);
                    }
                    $sql .= implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
            $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

            if (!is_dir(BACKUP_PATH)) {
                mkdir(BACKUP_PATH, 0755, true);
            }

            $filename = 'backup_' . DB_NAME . '_' . date('Ymd_His') . '.sql';
            file_put_contents(BACKUP_PATH . $filename, $sql);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã tạo bản sao lưu: ' . $filename];
            $this->redirect('/it/database');
        } catch (Exception $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lỗi sao lưu: ' . $e->getMessage()];
            $this->redirect('/it/database');
        }
    }

    /** GET /it/database/download?file= */
    public function downloadBackup(): void
    {
        Auth::requireRole(ROLE_IT, ROLE_ADMIN);
        $file = $this->input('file');
        $path = BACKUP_PATH . basename($file);

        if ($file && file_exists($path)) {
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            readfile($path);
            exit;
        }
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không tìm thấy file sao lưu.'];
        $this->redirect('/it/database');
    }

    /** POST /it/database/delete */
    public function deleteBackup(): void
    {
        Auth::requireRole(ROLE_IT, ROLE_ADMIN);
        $file = $this->input('file');
        $path = BACKUP_PATH . basename($file);

        if ($file && file_exists($path)) {
            unlink($path);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xóa bản sao lưu.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không thể xóa file.'];
        }
        $this->redirect('/it/database');
    }

    // ================================================================
    // DATABASE CLEANUP
    // ================================================================

    /**
     * POST /it/database/cleanup/all
     * Dọn toàn bộ data giao dịch — giữ nguyên users, shifts, menu, tables, QR
     */
    public function cleanupAll(): void
    {
        Auth::requireRole(ROLE_IT);

        $confirm = $this->input('confirm_text');
        if ($confirm !== 'XAC-NHAN-XOA-TOAN-BO') {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Xác nhận không đúng. Hủy thao tác.'];
            $this->redirect('/it/database');
        }

        try {
            $db = getDB();
            $db->exec('SET FOREIGN_KEY_CHECKS = 0');

            // Xóa theo thứ tự FK
            $tables = [
                'order_notifications',
                'realtime_notifications',
                'order_items',
                'orders',
                'support_requests',
                'customer_sessions',
                'table_status_history',
                'user_shifts',
            ];

            $counts = [];
            foreach ($tables as $t) {
                $cnt = (int) $db->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
                $db->exec("TRUNCATE TABLE `$t`");
                $counts[$t] = $cnt;
            }

            // Reset trạng thái bàn về available
            $db->exec("UPDATE `tables` SET status = 'available'");

            $db->exec('SET FOREIGN_KEY_CHECKS = 1');

            $total = array_sum($counts);
            $detail = implode(', ', array_map(fn($t, $c) => "$t ($c)", array_keys($counts), $counts));

            $_SESSION['flash'] = [
                'type'    => 'success',
                'message' => "✅ Đã dọn dẹp toàn bộ: {$total} bản ghi. Chi tiết: {$detail}. Bàn đã reset về sẵn sàng.",
            ];
        } catch (\Throwable $e) {
            $db->exec('SET FOREIGN_KEY_CHECKS = 1');
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lỗi dọn dẹp: ' . $e->getMessage()];
        }

        $this->redirect('/it/database');
    }

    /**
     * POST /it/database/cleanup/orders
     * Dọn lịch sử đặt bàn đã đóng + notifications — giữ lại bàn đang mở
     */
    public function cleanupOrders(): void
    {
        Auth::requireRole(ROLE_IT, ROLE_ADMIN);

        $confirm = $this->input('confirm_text');
        if ($confirm !== 'XOA-LICH-SU') {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Xác nhận không đúng. Hủy thao tác.'];
            $this->redirect('/it/database');
        }

        try {
            $db = getDB();
            $db->exec('SET FOREIGN_KEY_CHECKS = 0');

            // Chỉ xóa orders đã closed
            $closedOrders = $db->query("SELECT id FROM `orders` WHERE status = 'closed'")->fetchAll(PDO::FETCH_COLUMN);
            $deletedOrders = count($closedOrders);

            if (!empty($closedOrders)) {
                $ids = implode(',', array_map('intval', $closedOrders));
                $db->exec("DELETE FROM `order_items` WHERE order_id IN ($ids)");
                $db->exec("DELETE FROM `order_notifications` WHERE order_id IN ($ids)");
                $db->exec("DELETE FROM `orders` WHERE id IN ($ids)");
            }

            // Dọn notifications cũ (đã đọc hoặc > 24h)
            $ntfDel = $db->exec("DELETE FROM `realtime_notifications` WHERE is_delivered = 1 OR created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)");

            // Dọn customer sessions hết hạn
            $sesDel = $db->exec("DELETE FROM `customer_sessions` WHERE updated_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)");

            // Dọn table_status_history > 30 ngày
            $histDel = $db->exec("DELETE FROM `table_status_history` WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");

            // Dọn support_requests đã giải quyết
            $supDel = $db->exec("DELETE FROM `support_requests` WHERE status = 'completed'");

            $db->exec('SET FOREIGN_KEY_CHECKS = 1');

            $_SESSION['flash'] = [
                'type'    => 'success',
                'message' => "✅ Đã dọn lịch sử: {$deletedOrders} đơn hàng đóng, {$ntfDel} thông báo, {$sesDel} phiên KH, {$histDel} lịch sử bàn, {$supDel} yêu cầu hỗ trợ đã xử lý.",
            ];
        } catch (\Throwable $e) {
            $db->exec('SET FOREIGN_KEY_CHECKS = 1');
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lỗi dọn dẹp: ' . $e->getMessage()];
        }

        $this->redirect('/it/database');
    }

    /**
     * POST /it/database/cleanup/table
     * Dọn một bảng cụ thể (xóa toàn bộ hoặc theo điều kiện an toàn)
     */
    public function cleanupTable(): void
    {
        Auth::requireRole(ROLE_IT);

        $tableName = $this->input('table_name');
        $confirm   = $this->input('confirm_text');

        // Whitelist các bảng cho phép dọn
        $allowedTables = [
            'order_items'           => ['label' => 'Chi tiết món', 'condition' => "WHERE order_id IN (SELECT id FROM orders WHERE status='closed')"],
            'order_notifications'   => ['label' => 'Thông báo đơn hàng', 'condition' => ''],
            'realtime_notifications'=> ['label' => 'Thông báo realtime', 'condition' => ''],
            'orders'                => ['label' => 'Đơn hàng đã đóng', 'condition' => "WHERE status = 'closed'"],
            'support_requests'      => ['label' => 'Yêu cầu hỗ trợ', 'condition' => "WHERE status = 'completed'"],
            'customer_sessions'     => ['label' => 'Phiên khách (QR)', 'condition' => ''],
            'table_status_history'  => ['label' => 'Lịch sử trạng thái bàn', 'condition' => ''],
            'user_shifts'           => ['label' => 'Phân công ca (cũ)', 'condition' => "WHERE work_date < CURDATE()"],
        ];

        if (!isset($allowedTables[$tableName])) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bảng không hợp lệ.'];
            $this->redirect('/it/database');
        }

        $expectedConfirm = 'XOA-' . strtoupper(str_replace(['_', '-'], '-', $tableName));
        if ($confirm !== $expectedConfirm) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => "Xác nhận không đúng. Cần gõ: {$expectedConfirm}"];
            $this->redirect('/it/database');
        }

        try {
            $db  = getDB();
            $def = $allowedTables[$tableName];
            $db->exec('SET FOREIGN_KEY_CHECKS = 0');

            if ($def['condition'] === '') {
                $db->exec("TRUNCATE TABLE `{$tableName}`");
                $affected = '(toàn bộ)';
            } else {
                // Xử lý đặc biệt cho order_items (subquery không dùng được với DELETE trực tiếp)
                if ($tableName === 'order_items') {
                    $ids = $db->query("SELECT id FROM orders WHERE status='closed'")->fetchAll(PDO::FETCH_COLUMN);
                    if (!empty($ids)) {
                        $idList = implode(',', array_map('intval', $ids));
                        $affected = $db->exec("DELETE FROM `order_items` WHERE order_id IN ($idList)");
                    } else {
                        $affected = 0;
                    }
                } else {
                    $affected = $db->exec("DELETE FROM `{$tableName}` {$def['condition']}");
                }
            }

            $db->exec('SET FOREIGN_KEY_CHECKS = 1');
            $_SESSION['flash'] = [
                'type'    => 'success',
                'message' => "✅ Đã dọn bảng «{$def['label']}»: {$affected} bản ghi đã xóa.",
            ];
        } catch (\Throwable $e) {
            $db->exec('SET FOREIGN_KEY_CHECKS = 1');
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lỗi: ' . $e->getMessage()];
        }

        $this->redirect('/it/database');
    }
}
