<?php
// ============================================================
// MenuController — Waiter: View Digital Menu
// ============================================================

require_once BASE_PATH . '/models/MenuItem.php';
require_once BASE_PATH . '/models/MenuCategory.php';

class MenuController extends Controller
{
    /** GET /menu — Xem menu (phục vụ & khách hàng) */
    public function index(): void
    {
        $tableIdFromUrl = (int) $this->input('table_id');

        // Nếu qua QR (có table_id), lưu vào session và cho phép khách vào
        if ($tableIdFromUrl > 0) {
            $_SESSION['customer_table_id'] = $tableIdFromUrl;
            // Nếu khách vào, không bắt buộc login
        } else {
            // Nếu không có table_id, bắt buộc là nhân viên
            Auth::requireRole(ROLE_WAITER, ROLE_ADMIN, ROLE_IT);
        }

        $itemModel = new MenuItem();
        $categoryModel = new MenuCategory();

        $categories = $categoryModel->getActive();
        $grouped = $itemModel->getGroupedByCategory();

        // Ưu tiên lấy table_id từ session khách nếu có
        $tableId = $tableIdFromUrl ?: ($_SESSION['customer_table_id'] ?? 0);
        $orderId = (int) ($this->input('order_id') ?? 0);

        require_once BASE_PATH . '/models/Order.php';
        require_once BASE_PATH . '/models/Table.php';
        $tableModel = new Table();
        $orderModel = new Order();
        $allTables = $tableModel->getAll();

        // LOGIC TỰ ĐỘNG CHO KHÁCH: Nếu là khách (không có orderId nhưng có tableId)
        if ($tableId > 0 && $orderId === 0) {
            // Xem bàn này có order nào đang 'open' không
            $existingOrder = $orderModel->findOpenOrderByTable($tableId);
            if ($existingOrder) {
                $orderId = (int) $existingOrder['id'];
            } else if (!Auth::isLoggedIn()) {
                // Nếu chưa có và là khách truy cập -> Tự động mở bàn/tạo order nháp
                $tableModel->open($tableId);
                $orderId = $orderModel->create($tableId, null, 1);
            }
        }

        $orderItems = [];
        $orderTotal = 0;
        $order = null;

        if ($orderId > 0) {
            $order = $orderModel->findById($orderId);
            $orderItems = $orderModel->getItems($orderId);
            $totalInfo = $orderModel->getTotal($orderId);
            $orderTotal = is_array($totalInfo) ? ($totalInfo['total'] ?? 0) : $totalInfo;
        }

        // Chọn layout: Nhân viên (waiter) hoặc Khách hàng (public)
        $layout = Auth::isLoggedIn() ? 'layouts/waiter' : 'layouts/public';

        $this->view($layout, [
            'view' => 'menu/index',
            'pageTitle' => $tableId > 0 ? "Bàn {$tableId} - Gọi Món" : 'Menu',
            'categories' => $categories,
            'grouped' => $grouped,
            'tableId' => $tableId,
            'orderId' => $orderId,
            'order' => $order,
            'orderItems' => $orderItems,
            'orderTotal' => $orderTotal,
            'allTables' => $allTables,
            'isCustomer' => !Auth::isLoggedIn()
        ]);
    }
}
