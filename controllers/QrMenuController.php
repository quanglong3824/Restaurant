<?php
// ============================================================
// QrMenuController — Aurora Restaurant
// ============================================================

require_once BASE_PATH . '/models/QrTable.php';
require_once BASE_PATH . '/models/Table.php';
require_once BASE_PATH . '/models/MenuItem.php';
require_once BASE_PATH . '/models/MenuCategory.php';
require_once BASE_PATH . '/models/Order.php';
require_once BASE_PATH . '/models/CustomerSession.php';
require_once BASE_PATH . '/models/OrderNotification.php';

class QrMenuController extends Controller
{
    private QrTable $qrModel;
    private Table $tableModel;
    private MenuItem $menuModel;
    private MenuCategory $categoryModel;
    private Order $orderModel;
    private CustomerSession $sessionModel;
    private OrderNotification $notifModel;

    public function __construct()
    {
        $this->qrModel = new QrTable();
        $this->tableModel = new Table();
        $this->menuModel = new MenuItem();
        $this->categoryModel = new MenuCategory();
        $this->orderModel = new Order();
        $this->sessionModel = new CustomerSession();
        $this->notifModel = new OrderNotification();
    }

    /** Handle short QR links: /q?t=TOKEN */
    public function shortLink(): void
    {
        $token = $_GET['t'] ?? '';
        if (!$token) {
            $this->view('404', ['message' => 'Mã QR thiếu mã định danh.']);
            return;
        }

        $qrTable = $this->qrModel->findByToken($token);
        if (!$qrTable) {
            $this->view('404', ['message' => 'Mã QR không tồn tại hoặc đã hết hạn.']);
            return;
        }

        // Redirect to full menu URL
        $this->redirect("/qr/menu?table_id=" . $qrTable['table_id'] . "&token=" . $qrTable['qr_hash']);
    }

    /** View menu for customer */
    public function index(): void
    {
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();

            $tableId = (int)($_GET['table_id'] ?? 0);
            $token = $_GET['token'] ?? '';

            if (!$tableId || !$token) {
                $this->view('404', ['message' => 'Mã QR không hợp lệ hoặc thiếu thông tin bàn.']);
                return;
            }

            $qrTable = $this->qrModel->findByToken($token);
            if (!$qrTable || $qrTable['table_id'] != $tableId) {
                $this->view('404', ['message' => 'Mã QR đã hết hạn hoặc không hợp lệ.']);
                return;
            }

            // Increment scan count
            $this->qrModel->incrementScanCount($qrTable['id']);

            // Set customer session
            $this->setupCustomerSession($tableId, $token);
            $currentSessionId = session_id();

            $table = $this->tableModel->findById($tableId); // Reload table status
            if (!$table) {
                $this->view('404', ['message' => 'Không tìm thấy thông tin bàn.']);
                return;
            }

            // --- ĐƠN GIẢN HÓA LOGIC QR ---
            $openOrder = $this->orderModel->findOpenOrderByTable($tableId);
            $currentSessionId = session_id();

            // 1. Nếu bàn đang bận (occupied)
            if ($table['status'] === 'occupied') {
                if ($openOrder) {
                    // Nếu order đã có session_id và KHÔNG trùng với session hiện tại -> Bàn đang bận
                    if (!empty($openOrder['session_id']) && $openOrder['session_id'] !== $currentSessionId) {
                        $this->view('404', ['message' => 'Bàn này đang bận. Vui lòng liên hệ nhân viên hoặc chọn bàn khác.']);
                        return;
                    }
                    
                    // Nếu order chưa có session_id (nhân viên mở tay) -> Gán session hiện tại vào làm chủ bàn
                    if (empty($openOrder['session_id'])) {
                        $this->orderModel->updateSession($openOrder['id'], $currentSessionId);
                    }
                } else {
                    // Trường hợp hiếm: Bàn ghi occupied nhưng không thấy order mở -> Reset về available
                    $this->tableModel->update($tableId, ['status' => 'available']);
                    $this->redirect("/qr/menu?table_id=$tableId&token=$token");
                    return;
                }
            } 
            // 2. Nếu bàn đang trống (available) -> Mở bàn mới
            else {
                $this->tableModel->open($tableId);
                $this->orderModel->create([
                    'table_id' => $tableId,
                    'waiter_id' => null,
                    'guest_count' => 1,
                    'order_source' => 'customer_qr',
                    'session_id' => $currentSessionId,
                    'note' => 'Khách quét QR mở bàn'
                ]);
                // Lấy lại order vừa tạo
                $openOrder = $this->orderModel->findOpenOrderByTable($tableId);
            }

            // --- LẤY DỮ LIỆU HIỂN THỊ MENU ---
            $categories = $this->categoryModel->getAll();
            $menuItems = $this->menuModel->getAllActive();
            
            $orderId = $openOrder ? $openOrder['id'] : 0;
            $orderItems = $orderId ? $this->orderModel->getItems($orderId) : [];

            // Notify staff about QR scan (chỉ báo một lần hoặc mỗi lần quét tùy nhu cầu)
            if ($orderId) {
                $this->notifModel->create([
                    'order_id' => $orderId,
                    'table_id' => $tableId,
                    'type' => 'call_waiter',
                    'message' => "Khách tại {$table['name']} vừa truy cập menu qua QR."
                ]);
            }

            // Notify staff about QR scan
            $this->notifModel->create([
                'order_id' => $orderId ?: null,
                'table_id' => $tableId,
                'notification_type' => 'scan_qr',
                'title' => "Khách xem menu",
                'message' => "Bàn " . ($table['name'] ?? $tableId) . " vừa quét mã xem thực đơn."
            ]);
            
            $this->view('layouts/public', [
                'view' => 'menu/customer',
                'pageTitle' => 'Thực đơn ' . ($table['name'] ?? $tableId),
                'table' => $table,
                'categories' => $categories,
                'menuItems' => $menuItems,
                'orderId' => $orderId,
                'orderItems' => $orderItems,
                'token' => $token,
                'isCustomer' => true
            ]);
        } catch (\Throwable $e) {
            echo "<h1>Hệ thống gặp lỗi (500)</h1>";
            echo "<p>Lỗi: " . $e->getMessage() . "</p>";
            echo "<p>File: " . $e->getFile() . " trên dòng " . $e->getLine() . "</p>";
            exit;
        }
    }

    private function setupCustomerSession(int $tableId, string $token): void
    {
        // Set session cookie lifetime to 24 hours
        $lifetime = 24 * 3600;
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => $lifetime,
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'],
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            session_start();
        }

        $sessionId = session_id();
        
        // Always call create() which uses ON DUPLICATE KEY UPDATE internally
        // to handle existing/expired/inactive sessions gracefully
        $this->sessionModel->create([
            'session_id' => $sessionId,
            'table_id' => $tableId
        ]);

        $_SESSION['customer_table_id'] = $tableId;
        $_SESSION['qr_token'] = $token;
    }

    /** Add item to cart (temporary session or draft order) */
    public function addToCart(): void
    {
        $this->requireCustomer();
        
        $tableId = $_SESSION['customer_table_id'];
        $menuItemId = (int)$_POST['menu_item_id'];
        $quantity = (int)($_POST['quantity'] ?? 1);
        $note = $_POST['note'] ?? '';

        $this->json(['success' => true, 'message' => 'Đã thêm vào giỏ hàng']);
    }

    private function requireCustomer(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['customer_table_id'])) {
            $this->json(['error' => 'Vui lòng quét mã QR để tiếp tục'], 401);
            exit;
        }
    }
}
