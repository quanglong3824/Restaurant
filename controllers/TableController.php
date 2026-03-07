<?php
// ============================================================
// TableController — Waiter: Table Map
// ============================================================

require_once BASE_PATH . '/models/Table.php';
require_once BASE_PATH . '/models/Order.php';

class TableController extends Controller
{
    private Table $tableModel;
    private Order $orderModel;

    public function __construct()
    {
        $this->tableModel = new Table();
        $this->orderModel = new Order();
    }

    /** GET /tables — Sơ đồ bàn */
    public function index(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN, ROLE_IT);

        $grouped = $this->tableModel->getAllGroupedByArea();
        $counts = $this->tableModel->countByStatus();

        $this->view('layouts/waiter', [
            'view' => 'tables/index',
            'pageTitle' => 'Sơ đồ Bàn',
            'grouped' => $grouped,
            'counts' => $counts,
            'tableModel' => $this->tableModel,
        ]);
    }

    /** POST /tables/open — Mở bàn, tạo order */
    public function open(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $tableId = (int) $this->input('table_id');
        $guestCount = max(1, (int) $this->input('guest_count', 1));
        $waiterId = Auth::user()['id'];
        $shiftId = $_SESSION['user_shift_id'] ?? null;

        $table = $this->tableModel->findById($tableId);
        if (!$table || $table['status'] === 'occupied') {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bàn không hợp lệ hoặc đã có khách.'];
            $this->redirect('/tables');
        }

        try {
            $this->tableModel->open($tableId);
            $orderId = $this->orderModel->create($tableId, $waiterId, $guestCount, $shiftId);

            // Redirect to orders page
            $this->redirect('/orders?table_id=' . $tableId . '&order_id=' . $orderId);
        } catch (\Exception $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lỗi khi mở bàn: ' . $e->getMessage()];
            $this->redirect('/tables');
        }
    }

    /** POST /tables/merge — Ghép bàn */
    public function merge(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $childId = (int) $this->input('child_id');
        $parentId = (int) $this->input('parent_id');
        $redirectUrl = (string) $this->input('redirect', '/tables');

        if ($childId === $parentId) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không thể ghép bàn với chính nó.'];
            $this->redirect($redirectUrl);
        }

        $ok = $this->tableModel->mergeTable($childId, $parentId);
        if ($ok) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã ghép bàn thành công.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bàn đang có khách, không thể ghép.'];
        }

        $this->redirect($redirectUrl);
    }

    /** POST /tables/unmerge — Hủy ghép bàn */
    public function unmerge(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $childId = (int) $this->input('table_id');
        $redirectUrl = (string) $this->input('redirect', '/tables');

        $this->tableModel->unmergeTable($childId);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã tách bàn.'];
        $this->redirect($redirectUrl);
    }

    /** POST /tables/transfer — Chuyển bàn */
    public function transfer(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $fromTableId = (int) $this->input('from_table_id');
        $toTableId = (int) $this->input('to_table_id');

        if ($fromTableId === $toTableId) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Bạn đang chọn cùng một bàn.'];
            $this->redirect('/tables');
        }

        $toTable = $this->tableModel->findById($toTableId);
        if (!$toTable || $toTable['status'] === 'occupied' || $toTable['parent_id'] !== null) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bàn đích không hợp lệ hoặc đang có khách.'];
            $this->redirect('/tables');
        }

        $order = $this->orderModel->findOpenOrderByTable($fromTableId);
        if ($order) {
            // Update table_id in orders
            $db = getDB();
            $db->prepare("UPDATE orders SET table_id = ? WHERE id = ?")->execute([$toTableId, $order['id']]);

            // Cập nhật trạng thái bàn
            $this->tableModel->close($fromTableId); // Bàn cũ thành trống
            $this->tableModel->open($toTableId);    // Bàn mới thành bận

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Chuyển bàn thành công.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bàn cũ không có order nào để chuyển.'];
        }

        $this->redirect('/tables');
    }

    /** POST /tables/close — Đóng bàn */
    public function close(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $tableId = (int) $this->input('table_id');
        $orderId = (int) $this->input('order_id');
        $paymentMethod = (string) $this->input('payment_method', 'cash');
        $isAjax = $this->input('ajax') === '1';

        if (!in_array($paymentMethod, ['cash', 'transfer', 'card'])) {
            $paymentMethod = 'cash';
        }

        try {
            $totalInfo = $this->orderModel->getTotal($orderId);
            $total = is_array($totalInfo) ? ($totalInfo['total'] ?? 0) : $totalInfo;

            if ($total == 0) {
                if ($orderId > 0) {
                    $this->orderModel->cancel($orderId);
                }
                $this->tableModel->close($tableId);
                $message = 'Đã huỷ bàn thành công vì chưa gọi món.';
                $type = 'info';
            } else {
                $this->orderModel->close($orderId, $paymentMethod);
                $this->tableModel->close($tableId);
                $message = 'Đã đóng bàn và thanh toán thành công.';
                $type = 'success';
            }

            if ($isAjax) {
                $this->json(['ok' => true, 'message' => $message]);
                return;
            }
            $_SESSION['flash'] = ['type' => $type, 'message' => $message];
        } catch (\Exception $e) {
            if ($isAjax) {
                $this->json(['ok' => false, 'message' => $e->getMessage()]);
                return;
            }
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lỗi đóng bàn: ' . $e->getMessage()];
        }

        $this->redirect('/tables');
    }
}
