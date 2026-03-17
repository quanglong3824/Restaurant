<?php
// ============================================================
// QrOrderController — Aurora Restaurant
// ============================================================

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
        return $_SESSION['customer_table_id'];
    }

    /** Submit customer order */
    public function submit(): void
    {
        $tableId = $this->requireCustomer();
        $cartData = json_decode($_POST['cart'] ?? '[]', true);
        $notes = $_POST['notes'] ?? '';

        if (empty($cartData)) {
            $this->json(['error' => 'Giỏ hàng trống'], 400);
            return;
        }

        try {
            // Check if open order exists
            $order = $this->orderModel->findOpenByTable($tableId);
            $isNewOrder = false;

            if (!$order) {
                // Create new order
                $orderId = $this->orderModel->create([
                    'table_id' => $tableId,
                    'guest_count' => (int)($_POST['guest_count'] ?? 1),
                    'note' => $notes,
                    'order_source' => 'customer_qr',
                    'status' => 'open'
                ]);
                $isNewOrder = true;
                
                // Update table status to occupied
                $this->tableModel->open($tableId);
            } else {
                $orderId = $order['id'];
                // Optionally append notes if any
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
                    'customer_id' => session_id(),
                    'submitted_at' => date('Y-m-d H:i:s')
                ]);
            }

            // Create notification for waiters
            $this->notifModel->create([
                'order_id' => $orderId,
                'table_id' => $tableId,
                'notification_type' => $isNewOrder ? 'new_order' : 'order_item',
                'title' => $isNewOrder ? "Bàn $tableId: Order mới" : "Bàn $tableId: Thêm món mới",
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

    /** View order status */
    public function status(): void
    {
        $tableId = $this->requireCustomer();
        $order = $this->orderModel->findOpenByTable($tableId);
        
        if (!$order) {
            $this->render('orders/status', ['message' => 'Hiện tại chưa có order nào đang mở cho bàn này.']);
            return;
        }

        $items = $this->orderModel->getItems($order['id']);
        
        $this->render('orders/status', [
            'order' => $order,
            'items' => $items,
            'isCustomer' => true
        ], 'public');
    }

    /** View order history */
    public function history(): void
    {
        $tableId = $this->requireCustomer();
        // Just show items from current session if needed, or all items of the table
        $orders = $this->orderModel->getHistoryByTable($tableId, 5);
        
        $this->render('orders/history', [
            'orders' => $orders,
            'isCustomer' => true
        ], 'public');
    }
}
