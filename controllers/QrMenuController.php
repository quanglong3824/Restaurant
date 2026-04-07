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
                $this->view('404', ['message' => 'Mã QR không hợp lệ.']);
                return;
            }

            $qrTable = $this->qrModel->findByToken($token);
            if (!$qrTable || $qrTable['table_id'] != $tableId) {
                $this->view('404', ['message' => 'Mã QR không hợp lệ.']);
                return;
            }

            // --- CƠ CHẾ ĐỊNH DANH KHÁCH BỀN VỮNG (GOGI STYLE) ---
            $visitorToken = $_COOKIE['qr_visitor_token'] ?? bin2hex(random_bytes(16));
            setcookie('qr_visitor_token', $visitorToken, time() + (24 * 3600), "/", "", isset($_SERVER['HTTPS']), true);
            
            $this->setupCustomerSession($tableId, $token);
            $currentSessionId = session_id();

            // Kiểm tra xem session hiện tại có bị vô hiệu hoá (Timeout) không
            $activeSession = $this->sessionModel->findBySessionId($currentSessionId);
            if (!$activeSession) {
                // Nếu không tìm thấy session active, xoá cookie và bắt đầu mới
                setcookie(session_name(), '', time() - 3600, '/');
                session_destroy();
                $this->redirect("/qr/menu?table_id=$tableId&token=$token");
                return;
            }

            $table = $this->tableModel->findById($tableId);
            
            // Tìm đơn hàng đang mở
            $openOrder = $this->orderModel->findOpenOrderByTable($tableId);

            // Xử lý trạng thái bàn
            if ($table['status'] === 'occupied') {
                if ($openOrder) {
                    // Kiểm tra xem bàn có món chưa thanh toán (Confirmed) không
                    $confirmedItems = $this->orderModel->findAll(
                        "SELECT id FROM order_items WHERE order_id = ? AND status != 'cancelled' LIMIT 1", 
                        [$openOrder['id']]
                    );

                    // Nếu bàn bận (có món) và khách quét mới không phải khách đang ngồi
                    if ($confirmedItems && 
                        !empty($openOrder['session_id']) && 
                        $openOrder['session_id'] !== $visitorToken && 
                        $openOrder['session_id'] !== $currentSessionId) {
                        
                        $this->view('layouts/public', [
                            'view' => 'orders/table_busy',
                            'pageTitle' => 'Bàn đang bận',
                            'table' => $table,
                            'isCustomer' => true
                        ]);
                        return;
                    }

                    // Nếu là khách của bàn này hoặc bàn đang bận nhưng chưa có món (khách đang xem)
                    if (empty($openOrder['session_id']) || $openOrder['session_id'] === $currentSessionId) {
                        $this->orderModel->updateSession($openOrder['id'], $visitorToken);
                    }
                }
            } else {
                // Mở bàn mới
                $this->tableModel->open($tableId);
                $this->orderModel->create([
                    'table_id' => $tableId,
                    'order_source' => 'customer_qr',
                    'session_id' => $visitorToken,
                    'note' => 'Khách quét QR mở bàn'
                ]);
                $openOrder = $this->orderModel->findOpenOrderByTable($tableId);
            }

            // --- LẤY DỮ LIỆU HIỂN THỊ MENU ---
            $serviceType = ($table['type'] === 'room') ? 'room_service' : 'restaurant';
            $categories = $this->categoryModel->getAll();
            $menuItems = $this->menuModel->getAllActive($serviceType);
            
            $orderId = $openOrder ? $openOrder['id'] : 0;
            $orderItems = $orderId ? $this->orderModel->getItems($orderId) : [];

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
