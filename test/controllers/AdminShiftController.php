<?php
// ============================================================
// AdminShiftController — Quản lý Ca trực
// ============================================================

require_once BASE_PATH . '/models/User.php';

class AdminShiftController extends Controller
{
    /** GET /admin/shifts */
    public function index(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $db = getDB();

        // Pagination for shifts
        $page = max(1, (int) $this->input('page', 1));
        $limit = 10;
        
        $allShifts = $db->query("SELECT * FROM shifts ORDER BY start_time ASC")->fetchAll(PDO::FETCH_ASSOC);
        $total = count($allShifts);
        $offset = ($page - 1) * $limit;
        $shifts = array_slice($allShifts, $offset, $limit);

        $userModel = new User();
        $users = $userModel->getAll();

        // Lấy danh sách phân công — dùng prepare để tránh lỗi 500
        $today = date('Y-m-d');
        $stmt = $db->prepare(
            "SELECT us.*, u.name as user_name, u.role as user_role, s.name as shift_name,
                    s.start_time, s.end_time
              FROM user_shifts us
              JOIN users u ON u.id = us.user_id
              JOIN shifts s ON s.id = us.shift_id
              WHERE us.work_date = ?
              ORDER BY s.start_time ASC, u.name ASC"
        );
        $stmt->execute([$today]);
        $allAssignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Pagination for assignments
        $assignPage = max(1, (int) $this->input('assign_page', 1));
        $assignLimit = 15;
        $assignTotal = count($allAssignments);
        $assignOffset = ($assignPage - 1) * $assignLimit;
        $assignments = array_slice($allAssignments, $assignOffset, $assignLimit);

        // Lịch sử phân công 7 ngày gần nhất (thống kê)
        $stmtHistory = $db->prepare(
            "SELECT us.work_date, COUNT(*) as total_assignments,
                    GROUP_CONCAT(DISTINCT u.name ORDER BY u.name SEPARATOR ', ') as staff_names
              FROM user_shifts us
              JOIN users u ON u.id = us.user_id
              WHERE us.work_date >= DATE_SUB(?, INTERVAL 7 DAY) AND us.work_date <= ?
              GROUP BY us.work_date
              ORDER BY us.work_date DESC"
        );
        $stmtHistory->execute([$today, $today]);
        $recentHistory = $stmtHistory->fetchAll(PDO::FETCH_ASSOC);

        $this->view('layouts/admin', [
            'view'          => 'admin/shifts/index',
            'pageTitle'     => 'Quản lý Ca trực',
            'shifts'        => $shifts,
            'users'         => $users,
            'assignments'   => $assignments,
            'recentHistory' => $recentHistory,
            'today'         => $today,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' => ceil($total / $limit),
            ],
            'assignmentPagination' => [
                'page' => $assignPage,
                'limit' => $assignLimit,
                'total' => $assignTotal,
                'totalPages' => ceil($assignTotal / $assignLimit),
            ],
        ]);
    }

    /** POST /admin/shifts/store (Thêm Ca) */
    public function store(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $name      = trim((string) $this->input('name'));
        $startTime = $this->input('start_time');
        $endTime   = $this->input('end_time');

        if ($name && $startTime && $endTime) {
            $db = getDB();
            $db->prepare("INSERT INTO shifts (name, start_time, end_time) VALUES (?, ?, ?)")
               ->execute([$name, $startTime, $endTime]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã thêm ca trực: ' . $name];
        } else {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Vui lòng điền đầy đủ thông tin ca trực.'];
        }
        $this->redirect('/admin/shifts');
    }

    /** POST /admin/shifts/delete (Xóa Ca) */
    public function delete(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        $id = (int) $this->input('id');

        $db = getDB();
        // Kiểm tra xem ca có đang được dùng không
        $inUse = $db->prepare("SELECT COUNT(*) FROM user_shifts WHERE shift_id = ? AND work_date >= CURDATE()");
        $inUse->execute([$id]);
        $count = (int) $inUse->fetchColumn();

        if ($count > 0) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => "Ca này đang có {$count} phân công từ hôm nay trở đi. Vẫn xóa? Dữ liệu phân công sẽ bị xóa theo (CASCADE)."];
        }

        $db->prepare("DELETE FROM shifts WHERE id = ?")->execute([$id]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xóa ca trực.'];
        $this->redirect('/admin/shifts');
    }

    /** POST /admin/shifts/assign (Phân công nhân viên) */
    public function assign(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $userId  = (int) $this->input('user_id');
        $shiftId = (int) $this->input('shift_id');
        $date    = $this->input('work_date', date('Y-m-d'));

        if ($userId && $shiftId && $date) {
            $db = getDB();
            // FIX: prepare/execute thay vì query() với params
            $check = $db->prepare("SELECT id FROM user_shifts WHERE user_id = ? AND shift_id = ? AND work_date = ?");
            $check->execute([$userId, $shiftId, $date]);
            $exists = $check->fetch();

            if (!$exists) {
                $db->prepare("INSERT INTO user_shifts (user_id, shift_id, work_date) VALUES (?, ?, ?)")
                   ->execute([$userId, $shiftId, $date]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã phân công ca thành công.'];
            } else {
                $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Nhân viên này đã được phân công ca này rồi.'];
            }
        } else {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Thông tin phân công không hợp lệ.'];
        }
        $this->redirect('/admin/shifts');
    }

    /** POST /admin/shifts/remove_assign (Hủy phân công) */
    public function removeAssign(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        $id = (int) $this->input('id');

        $db = getDB();
        $db->prepare("DELETE FROM user_shifts WHERE id = ?")->execute([$id]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã hủy phân công.'];
        $this->redirect('/admin/shifts');
    }
}
