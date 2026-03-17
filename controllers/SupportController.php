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

        $id = (int) $this->input('id');
        if ($id > 0) {
            // Lấy thông tin yêu cầu trước khi resolve
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM support_requests WHERE id = ?");
            $stmt->execute([$id]);
            $request = $stmt->fetch();

            if ($request && $request['type'] === 'new_order') {
                // Tự động xác nhận tất cả các món đang pending của bàn này
                require_once BASE_PATH . '/models/Order.php';
                $orderModel = new Order();
                $order = $orderModel->findOpenOrderByTable($request['table_id']);
                if ($order) {
                    $orderModel->confirmPendingItems($order['id']);
                }
            }

            $this->supportModel->resolveRequest($id);
            echo json_encode(['ok' => true]);
        } else {
            echo json_encode(['ok' => false]);
        }
    }
}
