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

        // Nút 'Vào Bàn & Lưu Order' từ trang Menu (Showcase)
        $directMenuItemId = (int) $this->input('direct_menu_item_id', 0);
        if ($directMenuItemId > 0) {
            require_once BASE_PATH . '/models/MenuItem.php';
            $menuModel = new MenuItem();
            $item = $menuModel->findById($directMenuItemId);
            if ($item && $item['is_available']) {
                $this->orderModel->addItem($orderId, $item['id'], $item['name'], $item['price'], 1, '');
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã mở bàn và thêm "' . e($item['name']) . '" vào giỏ.'];
                $this->redirect('/menu?table_id=' . $tableId . '&order_id=' . $orderId);
            }
        }

        $this->redirect('/orders?table_id=' . $tableId . '&order_id=' . $orderId);
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
