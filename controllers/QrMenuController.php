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

            $table = $this->tableModel->findById($tableId);
            if (!$table) {
                $this->view('404', ['message' => 'Không tìm thấy thông tin bàn.']);
                return;
            }

            $categories = $this->categoryModel->getAll();
            $menuItems = $this->menuModel->getAllActive();

            // Get open order for this table if exists, or create one immediately
            $openOrder = $this->orderModel->findOpenOrderByTable($tableId);
            
            if (!$openOrder) {
                // Mark table as busy immediately upon scan
                $this->tableModel->open($tableId);
                $orderId = $this->orderModel->create([
                    'table_id' => $tableId,
                    'order_source' => 'customer_qr',
                    'status' => 'open'
                ]);
            } else {
                $orderId = $openOrder['id'];
            }

            // Notify staff about QR scan
            $this->notifModel->create([
                'order_id' => $orderId,
                'table_id' => $tableId,
                'notification_type' => 'scan_qr',
                'title' => "Bàn " . ($table['name'] ?? $tableId) . ": Khách đang xem menu",
                'message' => "Khách vừa quét mã QR tại bàn " . ($table['name'] ?? $tableId)
            ]);
            
            $this->view('layouts/public', [
                'view' => 'menu/customer',
                'pageTitle' => 'Thực đơn bàn ' . ($table['name'] ?? $tableId),
                'table' => $table,
                'categories' => $categories,
                'menuItems' => $menuItems,
                'openOrder' => $openOrder,
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sessionId = session_id();
        $existingSession = $this->sessionModel->findBySessionId($sessionId);

        if (!$existingSession) {
            $this->sessionModel->create([
                'session_id' => $sessionId,
                'table_id' => $tableId
            ]);
        } else {
            $this->sessionModel->updateActivity($sessionId);
        }

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
