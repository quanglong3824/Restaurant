<?php
// ============================================================
// SupportController — Handle Customer Requests
// ============================================================

require_once BASE_PATH . '/models/Support.php';

class SupportController extends Controller
{
    private Support $supportModel;

    public function __construct()
    {
        $this->supportModel = new Support();
    }

    /** POST /support/request */
    public function makeRequest(): void
    {
        header('Content-Type: application/json');
        $tableId = (int) $this->input('table_id');
        $type = $this->input('type'); // 'support' or 'payment'

        if ($tableId <= 0 || !in_array($type, ['support', 'payment'])) {
            echo json_encode(['ok' => false, 'message' => 'Dữ liệu không hợp lệ.']);
            return;
        }

        $this->supportModel->createRequest($tableId, $type);
        echo json_encode(['ok' => true, 'message' => 'Đã gửi yêu cầu thành công. Nhân viên sẽ đến ngay.']);
    }

    /** GET /support/pending (For Waiter API Polling) */
    public function getPending(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN, ROLE_IT);
        header('Content-Type: application/json');
        
        $requests = $this->supportModel->getPendingRequests();
        echo json_encode(['ok' => true, 'data' => $requests]);
    }

    /** POST /support/resolve */
    public function resolve(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN, ROLE_IT);
        header('Content-Type: application/json');

        $id = $this->input('id');
        if ($id) {
            // Lấy thông tin yêu cầu trước khi resolve để xử lý logic phụ
            // Nếu ID có tiền tố on_ (order_notification), ta cần xử lý thêm nếu là new_order
            if (strpos($id, 'on_') === 0) {
                $numericId = (int)str_replace('on_', '', $id);
                $db = getDB();
                $stmt = $db->prepare("SELECT * FROM order_notifications WHERE id = ?");
                $stmt->execute([$numericId]);
                $notif = $stmt->fetch();

                if ($notif && $notif['notification_type'] === 'new_order') {
                    require_once BASE_PATH . '/models/Order.php';
                    $orderModel = new Order();
                    $orderModel->confirmPendingItems($notif['order_id']);
                }
            } elseif (strpos($id, 'sr_') === 0 || is_numeric($id)) {
                // Logic cũ cho support_requests
                $numericId = (int)str_replace('sr_', '', $id);
                $db = getDB();
                $stmt = $db->prepare("SELECT * FROM support_requests WHERE id = ?");
                $stmt->execute([$numericId]);
                $request = $stmt->fetch();

                if ($request && $request['type'] === 'new_order') {
                    require_once BASE_PATH . '/models/Order.php';
                    $orderModel = new Order();
                    $order = $orderModel->findOpenOrderByTable($request['table_id']);
                    if ($order) {
                        $orderModel->confirmPendingItems($order['id']);
                    }
                }
            }

            $this->supportModel->resolveRequest($id);
            echo json_encode(['ok' => true]);
        } else {
            echo json_encode(['ok' => false]);
        }
    }
}
