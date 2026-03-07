<?php
// ============================================================
// OrderController — Waiter: Manage Order Items
// ============================================================

require_once BASE_PATH . '/models/Order.php';
require_once BASE_PATH . '/models/Table.php';
require_once BASE_PATH . '/models/MenuItem.php';
require_once BASE_PATH . '/models/MenuSet.php';

class OrderController extends Controller
{
    private Order $orderModel;
    private Table $tableModel;
    private MenuItem $menuModel;
    private MenuSet $setModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->tableModel = new Table();
        $this->menuModel = new MenuItem();
        $this->setModel = new MenuSet();
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
        $tableDisplayName = $this->tableModel->getFullDisplayName($tableId);
        $grouped = $this->tableModel->getAllGroupedByArea();

        // Tích hợp logic gợi ý ghép bàn liên tục (persistent banner)
        $mergeSuggestion = null;
        if ($order && empty($table['parent_id']) && (int) $order['guest_count'] > 0) {
            // Tính tổng sức chứa (bao gồm bàn chính và các bàn ghép)
            $totalCapacity = (int) $table['capacity'];
            $children = $this->tableModel->getMergedTables($tableId);
            foreach ($children as $child) {
                $totalCapacity += (int) $child['capacity'];
            }

            if ((int) $order['guest_count'] > $totalCapacity) {
                $extraGuests = (int) $order['guest_count'] - $totalCapacity;
                $tableNeeded = ceil($extraGuests / 4);

                $db = getDB();
                $stmt = $db->prepare(
                    "SELECT name FROM tables 
                     WHERE area = ? AND status = 'available' AND parent_id IS NULL AND id != ?
                     ORDER BY sort_order, name
                     LIMIT ?"
                );
                $stmt->bindValue(1, $table['area']);
                $stmt->bindValue(2, $tableId, PDO::PARAM_INT);
                $stmt->bindValue(3, $tableNeeded, PDO::PARAM_INT);
                $stmt->execute();
                $availableInArea = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($availableInArea)) {
                    $availableNames = array_column($availableInArea, 'name');
                    $suggestionStr = implode(', ', $availableNames);
                    $mergeSuggestion = "Số lượng khách ({$order['guest_count']}) đang vượt quá sức chứa ({$totalCapacity}). Gợi ý: Hãy bấm 'Ghép bàn' thêm với bàn <strong>{$suggestionStr}</strong> ở cùng khu vực!";
                } else {
                    $mergeSuggestion = "Số lượng khách ({$order['guest_count']}) đang vượt quá sức chứa ({$totalCapacity}). Gợi ý: Hãy bấm 'Ghép bàn' thêm <strong>{$tableNeeded} bàn nữa</strong> để đủ chỗ ngồi!";
                }
            }
        }

        $this->view('layouts/waiter', [
            'view' => 'orders/index',
            'pageTitle' => 'Order — ' . $tableDisplayName,
            'table' => $table,
            'table_display_name' => $tableDisplayName,
            'order' => $order,
            'items' => $items,
            'total' => $total,
            'grouped' => $grouped,
            'mergeSuggestion' => $mergeSuggestion,
        ]);
    }

    /** POST /orders/add — Thêm món vào order */
    public function addItem(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $orderId = (int) $this->input('order_id');
        $menuItemId = (int) $this->input('menu_item_id');
        $qty = max(0, (int) $this->input('qty', 1));
        $note = trim((string) $this->input('note', ''));

        if ($menuItemId > 0) {
            $item = $this->menuModel->findById($menuItemId);
            if (!$item || !$item['is_available']) {
                $this->json(['ok' => false, 'message' => 'Món không khả dụng.'], 400);
            }

            $order = $this->orderModel->findById($orderId);
            if (!$order || $order['status'] !== 'open') {
                $this->json(['ok' => false, 'message' => 'Order không hợp lệ.'], 400);
            }

            if ($qty > 0) {
                $this->orderModel->addItem(
                    $orderId,
                    $menuItemId,
                    $item['name'],
                    $item['price'],
                    $qty,
                    $note
                );
            }
        }

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);

        // Format items for JS
        foreach ($items as &$it) {
            $it['price_fmt'] = formatPrice($it['item_price']);
            $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
        }

        $this->json([
            'ok' => true,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
            'item_count' => array_sum(array_column($items, 'quantity')),
        ]);
    }

    /** POST /orders/add-set — Thêm set/combo vào order */
    public function addSet(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $orderId = (int) $this->input('order_id');
        $setId = (int) $this->input('set_id');
        $items = $this->input('items', []);

        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['status'] !== 'open') {
            $this->json(['ok' => false, 'message' => 'Order không hợp lệ.'], 400);
        }

        $set = $this->setModel->findById($setId);
        if (!$set || !$set['is_active']) {
            $this->json(['ok' => false, 'message' => 'Set không tồn tại hoặc không khả dụng.'], 400);
        }

        // Add each item from the set
        foreach ($items as $itemData) {
            $menuItemId = (int) ($itemData['menu_item_id'] ?? 0);
            $qty = max(1, (int) ($itemData['quantity'] ?? 1));

            $menuItem = $this->menuModel->findById($menuItemId);
            if ($menuItem) {
                $this->orderModel->addItem(
                    $orderId,
                    $menuItemId,
                    $menuItem['name'],
                    $menuItem['price'],
                    $qty,
                    "Set: {$set['name']}"
                );
            }
        }

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);

        // Format items for JS
        foreach ($items as &$it) {
            $it['price_fmt'] = formatPrice($it['item_price']);
            $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
        }

        $this->json([
            'ok' => true,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
            'item_count' => array_sum(array_column($items, 'quantity')),
        ]);
    }

    /** POST /orders/update — Cập nhật số lượng món */
    public function updateItem(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $itemId = (int) $this->input('item_id');
        $orderId = (int) $this->input('order_id');
        $qtyInput = $this->input('qty');

        $qty = 0;
        if (strpos((string) $qtyInput, 'delta:') === 0) {
            $delta = (int) str_replace('delta:', '', (string) $qtyInput);
            // Get current qty
            $db = getDB();
            $stmt = $db->prepare("SELECT quantity FROM order_items WHERE id = ?");
            $stmt->execute([$itemId]);
            $current = $stmt->fetchColumn();
            $qty = max(0, $current + $delta);
        } else {
            $qty = max(0, (int) $qtyInput);
        }

        $this->orderModel->updateItem($itemId, $qty);

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);

        // Format items for JS
        foreach ($items as &$it) {
            $it['price_fmt'] = formatPrice($it['item_price']);
            $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
        }

        $this->json([
            'ok' => true,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
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

        // Format items for JS
        foreach ($items as &$it) {
            $it['price_fmt'] = formatPrice($it['item_price']);
            $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
        }

        $this->json([
            'ok' => true,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
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
        $tableDisplayName = $this->tableModel->getFullDisplayName($order['table_id']);
        $items = $this->orderModel->getItems($orderId);
        $total = $this->orderModel->getTotal($orderId);

        // Hiển thị view in không qua layout chung
        require_once BASE_PATH . '/views/orders/print.php';
    }
}
