<?php
// ============================================================
// OrderController — Waiter: Manage Order Items
// ============================================================

require_once BASE_PATH . '/models/Order.php';
require_once BASE_PATH . '/models/Table.php';
require_once BASE_PATH . '/models/MenuItem.php';

class OrderController extends Controller
{
    private Order $orderModel;
    private Table $tableModel;
    private MenuItem $menuModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->tableModel = new Table();
        $this->menuModel = new MenuItem();
    }

    /** GET /orders?table_id=&order_id= — Xem order của bàn, hoặc Danh sách tất cả bàn bận */
    public function index(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN, ROLE_IT);

        $tableId = (int) $this->input('table_id');

        if ($tableId === 0) {
            // View Danh sách Bàn Đang Bận
            $openOrders = $this->orderModel->getAllOpen();
            $allTables = $this->tableModel->getAll();
            $allAreas = array_unique(array_filter(array_column($allTables, 'area')));
            sort($allAreas);

            $this->view('layouts/waiter', [
                'view' => 'orders/list',
                'pageTitle' => 'Danh sách Bàn Đang Order',
                'orders' => $openOrders,
                'areas' => $allAreas
            ]);
            return;
        }

        $orderId = (int) $this->input('order_id');

        $table = $this->tableModel->findById($tableId);
        if (!$table) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bàn không tồn tại.'];
            $this->redirect('/tables');
        }

        // Lấy order đang mở (ưu tiên order_id nếu có)
        $order = $orderId
            ? $this->orderModel->findById($orderId)
            : $this->orderModel->getOpenByTable($tableId);

        $items = $order ? $this->orderModel->getItems($order['id']) : [];
        $total = $order ? $this->orderModel->getTotal($order['id']) : 0;

        $this->view('layouts/waiter', [
            'view' => 'orders/index',
            'pageTitle' => 'Order — ' . ($table['name'] ?? ''),
            'table' => $table,
            'order' => $order,
            'items' => $items,
            'total' => $total,
        ]);
    }

    /** POST /orders/add — Thêm món vào order */
    public function addItem(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $orderId = (int) $this->input('order_id');
        $menuItemId = (int) $this->input('menu_item_id');
        $qty = max(1, (int) $this->input('qty', 1));
        $note = trim((string) $this->input('note', ''));

        $item = $this->menuModel->findById($menuItemId);
        if (!$item || !$item['is_available']) {
            $this->json(['ok' => false, 'message' => 'Món không khả dụng.'], 400);
        }

        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['status'] !== 'open') {
            $this->json(['ok' => false, 'message' => 'Order không hợp lệ.'], 400);
        }

        $this->orderModel->addItem(
            $orderId,
            $menuItemId,
            $item['name'],
            $item['price'],
            $qty,
            $note
        );

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);

        $this->json([
            'ok' => true,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'item_count' => array_sum(array_column($items, 'quantity')),
        ]);
    }

    /** POST /orders/update — Cập nhật số lượng món */
    public function updateItem(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $itemId = (int) $this->input('item_id');
        $orderId = (int) $this->input('order_id');
        $qty = (int) $this->input('qty', 1);

        $this->orderModel->updateItem($itemId, $qty);

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);

        $this->json([
            'ok' => true,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'item_count' => array_sum(array_column($items, 'quantity')),
        ]);
    }

    /** POST /orders/remove — Xóa món khỏi order */
    public function removeItem(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $itemId = (int) $this->input('item_id');
        $orderId = (int) $this->input('order_id');

        $this->orderModel->removeItem($itemId);

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);

        $this->json([
            'ok' => true,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'item_count' => array_sum(array_column($items, 'quantity')),
        ]);
    }

    /** POST /orders/confirm — Xác nhận món (Gửi bếp) */
    public function confirmOrder(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $tableId = (int) $this->input('table_id');
        $orderId = (int) $this->input('order_id');

        $this->orderModel->confirmItems($orderId);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xác nhận các món được chọn! Chờ bếp chuẩn bị.'];
        $this->redirect('/orders?table_id=' . $tableId . '&order_id=' . $orderId);
    }

    /** GET /orders/print — In hóa đơn */
    public function print(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);
        $orderId = (int) $this->input('order_id');

        $order = $this->orderModel->findById($orderId);
        if (!$order) {
            die('Không tìm thấy hóa đơn.');
        }

        $table = $this->tableModel->findById($order['table_id']);
        $items = $this->orderModel->getItems($orderId);
        $total = $this->orderModel->getTotal($orderId);

        // Hiển thị view in không qua layout chung
        require_once BASE_PATH . '/views/orders/print.php';
    }
}
