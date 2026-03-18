<?php
// ============================================================
// QrOrderController — Aurora Restaurant
// ============================================================

require_once BASE_PATH . '/models/Order.php';
require_once BASE_PATH . '/models/Table.php';
require_once BASE_PATH . '/models/CustomerSession.php';
require_once BASE_PATH . '/models/OrderNotification.php';

class QrOrderController extends Controller
{
    private Order $orderModel;
    private Table $tableModel;
    private CustomerSession $sessionModel;
    private OrderNotification $notifModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->tableModel = new Table();
        $this->sessionModel = new CustomerSession();
        $this->notifModel = new OrderNotification();
    }

    private function requireCustomer(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['customer_table_id'])) {
            $this->json(['error' => 'Vui lòng quét mã QR để tiếp tục'], 401);
            exit;
        }
        return (int)$_SESSION['customer_table_id'];
    }

    /** Submit customer order */
    public function submit(): void
    {
        $tableId = $this->requireCustomer();
        $currentSessionId = session_id();
        $cartData = json_decode($_POST['cart'] ?? '[]', true);
        $notes = $_POST['notes'] ?? '';

        if (empty($cartData)) {
            $this->json(['error' => 'Giỏ hàng trống'], 400);
            return;
        }

        try {
            // Check table status
            $table = $this->tableModel->findById($tableId);
            if (!$table) {
                $this->json(['error' => 'Không tìm thấy bàn'], 404);
                return;
            }

            // --- KIỂM TRA SESSION ĐÃ HOÀN TẤT CHƯA ---
            $lastOrder = $this->orderModel->findLastOrderByTable($tableId);
            if ($lastOrder && $lastOrder['status'] === 'closed' && $lastOrder['session_id'] === $currentSessionId) {
                $closedTime = strtotime($lastOrder['closed_at'] ?? $lastOrder['updated_at']);
                $minutesSinceClose = (time() - $closedTime) / 60;
                
                // Chỉ chặn nếu vừa đóng trong vòng 30 phút
                if ($minutesSinceClose < 30) {
                    $this->json(['error' => 'Phiên làm việc vừa kết thúc. Vui lòng đợi hoặc liên hệ nhân viên nếu muốn đặt lượt mới.'], 403);
                    return;
                }
            }

            // Check if open order exists
            $order = $this->orderModel->findOpenOrderByTable($tableId);
            $isNewOrder = false;

            if (!$order) {
                // If table is available, we open it
                if ($table['status'] === 'available') {
                    $this->tableModel->open($tableId);
                }

                // Create new order with session_id
                $orderId = $this->orderModel->create([
                    'table_id' => $tableId,
                    'guest_count' => (int)($_POST['guest_count'] ?? 1),
                    'note' => $notes,
                    'order_source' => 'customer_qr',
                    'session_id' => $currentSessionId,
                    'status' => 'open'
                ]);
                $isNewOrder = true;
            } else {
                $orderId = $order['id'];
                
                // Append notes if any
                if ($notes) {
                    $this->orderModel->appendNote($orderId, $notes);
                }
            }

            // Add items to order
            foreach ($cartData as $item) {
                $this->orderModel->addItem($orderId, [
                    'menu_item_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'note' => $item['note'] ?? '',
                    'status' => 'pending',
                    'customer_id' => $currentSessionId,
                    'submitted_at' => date('Y-m-d H:i:s')
                ]);
            }

            // Create notification for waiters
            $this->notifModel->create([
                'order_id' => $orderId,
                'table_id' => $tableId,
                'notification_type' => $isNewOrder ? 'new_order' : 'order_item',
                'title' => $isNewOrder ? "Bàn " . ($table['name'] ?? $tableId) . ": Order mới" : "Bàn " . ($table['name'] ?? $tableId) . ": Thêm món mới",
                'message' => $isNewOrder ? "Khách đã gửi order mới qua QR." : "Khách đã gửi thêm món qua QR."
            ]);

            $this->json([
                'success' => true, 
                'order_id' => $orderId, 
                'message' => 'Gửi order thành công! Vui lòng chờ nhân viên xác nhận.'
            ]);

        } catch (Exception $e) {
            $this->json(['error' => 'Lỗi xử lý order: ' . $e->getMessage()], 500);
        }
    }

    /** Clear customer session for this table to allow new session */
    public function clearSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Clear customer-specific data
        unset($_SESSION['customer_table_id']);
        unset($_SESSION['customer_token']);
        
        // Regenerate session ID to get a fresh start from the server perspective
        session_regenerate_id(true);
        
        $this->json(['success' => true, 'message' => 'Đã làm mới phiên làm việc.']);
    }

    /** View order status */
    public function status(): void
    {
        $tableId = $this->requireCustomer();
        $order = $this->orderModel->findOpenOrderByTable($tableId);
        
        if (!$order) {
            $this->view('layouts/public', [
                'view' => 'orders/status',
                'pageTitle' => 'Trạng thái Order',
                'message' => 'Hiện tại chưa có order nào đang mở cho bàn này.'
            ]);
            return;
        }

        $items = $this->orderModel->getItems($order['id']);
        
        $this->view('layouts/public', [
            'view' => 'orders/status',
            'pageTitle' => 'Trạng thái Order #' . $order['id'],
            'order' => $order,
            'items' => $items,
            'isCustomer' => true
        ]);
    }

    /** View order history */
    public function history(): void
    {
        $tableId = $this->requireCustomer();
        // Just show items from current session if needed, or all items of the table
        $orders = $this->orderModel->getHistoryByTable($tableId, 5);
        
        $this->view('layouts/public', [
            'view' => 'orders/history',
            'pageTitle' => 'Lịch sử gọi món',
            'orders' => $orders,
            'isCustomer' => true
        ]);
    }
}
