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
        ]);
    }

    /** POST /tables/open — Mở bàn, tạo order */
    public function open(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $tableId = (int) $this->input('table_id');
        $guestCount = max(1, (int) $this->input('guest_count', 1));
        $waiterId = Auth::user()['id'];

        $table = $this->tableModel->findById($tableId);
        if (!$table || $table['status'] === 'occupied') {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bàn không hợp lệ hoặc đã có khách.'];
            $this->redirect('/tables');
        }

        $this->tableModel->open($tableId);
        $orderId = $this->orderModel->create($tableId, $waiterId, $guestCount);

        // Logic tự động ghép phòng VIP (Ghép 1+2, 3+4)
        // Ví dụ: Nếu mở bàn "Vip 1.x" bất kỳ, có thể gợi ý hoặc tự động ghép
        // Ở đây ta xử lý ghép bàn theo yêu cầu người dùng
        
        $this->redirect('/orders?table_id=' . $tableId . '&order_id=' . $orderId);
    }

    /** POST /tables/merge — Ghép bàn */
    public function merge(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $childId = (int) $this->input('child_id');
        $parentId = (int) $this->input('parent_id');

        if ($childId === $parentId) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không thể ghép bàn với chính nó.'];
            $this->redirect('/tables');
        }

        $ok = $this->tableModel->mergeTable($childId, $parentId);
        if ($ok) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã ghép bàn thành công.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bàn đang có khách, không thể ghép.'];
        }
        $this->redirect('/tables');
    }

    /** POST /tables/unmerge — Hủy ghép bàn */
    public function unmerge(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $childId = (int) $this->input('table_id');
        $this->tableModel->unmergeTable($childId);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã tách bàn.'];
        $this->redirect('/tables');
    }

    /** POST /tables/close — Đóng bàn */
    public function close(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $tableId = (int) $this->input('table_id');
        $orderId = (int) $this->input('order_id');
        $paymentMethod = (string) $this->input('payment_method', 'cash');

        if (!in_array($paymentMethod, ['cash', 'transfer', 'card'])) {
            $paymentMethod = 'cash';
        }

        $totalInfo = $this->orderModel->getTotal($orderId);
        $total = is_array($totalInfo) ? ($totalInfo['total'] ?? 0) : $totalInfo;

        if ($total == 0) {
            $this->orderModel->cancel($orderId);
            $this->tableModel->close($tableId);
            $_SESSION['flash'] = ['type' => 'info', 'message' => 'Đã huỷ bàn thành công vì chưa gọi món.'];
        } else {
            $this->orderModel->close($orderId, $paymentMethod);
            $this->tableModel->close($tableId);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã đóng bàn và thanh toán thành công.'];
        }

        $this->redirect('/tables');
    }
}
