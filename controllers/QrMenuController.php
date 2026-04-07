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

            // --- CƠ CHẾ ĐỊNH DANH KHÁCH SIÊU BỀN VỮNG (COOKIE + LOCALSTORAGE FALLBACK) ---
            $visitorToken = $_COOKIE['qr_visitor_token'] ?? $_GET['_vt'] ?? '';

            // Nếu hoàn toàn không có token, thử dùng JS để khôi phục từ localStorage của thiết bị
            if (empty($visitorToken) && !isset($_GET['_no_js'])) {
                echo "
                <script>
                    (function(){
                        // Tìm bất kỳ token nào đã lưu trên thiết bị này (qua các bàn khác)
                        var _v = localStorage.getItem('qr_global_device_id');
                        if (!_v) {
                             // Fallback: tìm trong các key cũ theo table
                             for (var i = 0; i < localStorage.length; i++) {
                                 var k = localStorage.key(i);
                                 if (k && k.indexOf('qr_vt_') === 0) {
                                     _v = localStorage.getItem(k);
                                     break;
                                 }
                             }
                        }
                        
                        var url = new URL(window.location.href);
                        if (_v) {
                            url.searchParams.set('_vt', _v);
                            window.location.replace(url.toString());
                        } else {
                            // Không tìm thấy -> đi tiếp để server sinh token mới
                            url.searchParams.set('_no_js', '1');
                            window.location.replace(url.toString());
                        }
                    })();
                </script>
                <div style='text-align:center; padding:50px; font-family:sans-serif; color:#666;'>
                    Đang kết nối hệ thống...
                </div>";
                exit;
            }

            if (empty($visitorToken)) {
                $visitorToken = bin2hex(random_bytes(16));
            }

            // Đồng bộ Token vào cookie (cho các request sau) và JS (cho localStorage sau)
            setcookie('qr_visitor_token', $visitorToken, [
                'expires' => time() + (365 * 86400), // 1 năm
                'path' => '/',
                'samesite' => 'Lax',
                'secure' => isset($_SERVER['HTTPS'])
            ]);

            $this->setupCustomerSession($tableId, $token);
            $currentSessionId = session_id();

            // Kiểm tra session active
            $activeSession = $this->sessionModel->findBySessionId($currentSessionId);
            if (!$activeSession) {
                setcookie(session_name(), '', time() - 3600, '/');
                session_destroy();
                $this->redirect("/qr/menu?table_id=$tableId&token=$token");
                return;
            }

            $table = $this->tableModel->findById($tableId);

            // Tìm đơn hàng đang mở
            $openOrder = $this->orderModel->findOpenOrderByTable($tableId);

            // ── XỬ LÝ TRẠNG THÁI BÀN ────────────────────────────────
            if ($table['status'] === 'occupied' && $openOrder) {

                $confirmedItems = $this->orderModel->findAll(
                    "SELECT id FROM order_items WHERE order_id = ? AND status != 'cancelled' LIMIT 1",
                    [$openOrder['id']]
                );

                // Kiểm tra xem đây có phải cùng thiết bị không
                // Nhận diện: visitorToken khớp, HOẶC session PHP khớp,
                // HOẶC order được tạo từ cùng QR token (同一 table + token = same QR source)
                $storedSession   = $openOrder['session_id'] ?? '';
                $isSameDevice    = ($storedSession === $visitorToken)
                                || ($storedSession === $currentSessionId)
                                || empty($storedSession);

                if ($confirmedItems && !$isSameDevice) {
                    // Bàn thực sự đang bận bởi người khác → chặn
                    $this->view('layouts/public', [
                        'view'      => 'orders/table_busy',
                        'pageTitle' => 'Bàn đang bận',
                        'table'     => $table,
                        'isCustomer'=> true
                    ]);
                    return;
                }

                // Cùng thiết bị hoặc session mới của cùng khách
                // → cập nhật session_id về visitor token hiện tại
                $this->orderModel->updateSession($openOrder['id'], $visitorToken);

            } elseif ($table['status'] !== 'occupied') {
                // Bàn chưa mở → tạo order mới
                $this->tableModel->open($tableId);
                $this->orderModel->create([
                    'table_id'     => $tableId,
                    'order_source' => 'customer_qr',
                    'session_id'   => $visitorToken,
                    'note'         => 'Khách quét QR mở bàn'
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

    /** View all active sessions (tables) for this device */
    public function sessions(): void
    {
        $visitorToken = $_COOKIE['qr_visitor_token'] ?? '';
        
        if (empty($visitorToken)) {
            $this->view('layouts/public', [
                'view' => 'menu/no_session',
                'pageTitle' => 'Không tìm thấy phiên làm việc',
                'isCustomer' => true
            ]);
            return;
        }

        $activeOrders = $this->orderModel->findBySessionId($visitorToken);
        
        $this->view('layouts/public', [
            'view' => 'menu/sessions',
            'pageTitle' => 'Quản lý phiên đặt món',
            'orders' => $activeOrders,
            'visitorToken' => $visitorToken,
            'isCustomer' => true
        ]);
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
