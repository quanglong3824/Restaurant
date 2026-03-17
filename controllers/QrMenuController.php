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

            // Get open order for this table if exists
            $openOrder = $this->orderModel->findOpenOrderByTable($tableId);
            $orderId = $openOrder ? $openOrder['id'] : 0;

            // Notify staff about QR scan
            $this->notifModel->create([
                'order_id' => $orderId,
                'table_id' => $tableId,
                'notification_type' => 'scan_qr',
                'title' => "Bàn " . ($table['name'] ?? $tableId) . ": Khách đang xem menu",
                'message' => "Khách vừa quét mã QR tại bàn " . ($table['name'] ?? $tableId)
            ]);
            
            // --- TEST MODE: Chế độ test chỉ hiện số bàn ---
            echo "<!DOCTYPE html><html lang='vi'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Quét QR Thành Công</title></head>";
            echo "<body style='background-color:#0f172a; color:#f8fafc; font-family:sans-serif; text-align:center; padding-top:20vh;'>";
            echo "<i class='fas fa-check-circle' style='font-size:4rem; color:#10b981; margin-bottom:20px;'></i>";
            echo "<h1 style='color:#c5a059; font-size:2rem; margin-bottom:10px;'>ĐÃ QUÉT MÃ QR</h1>";
            echo "<h2 style='font-size:3rem; margin:0;'>BÀN " . e($table['name'] ?? $tableId) . "</h2>";
            echo "<p style='color:#94a3b8; margin-top:20px;'>Hệ thống đã ghi nhận. Vui lòng đợi trong giây lát...</p>";
            echo "</body></html>";
            exit;
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

        // Implementation for adding to cart
        // In this system, we can either use PHP Session for cart
        // Or directly create/update a 'draft' order in the database
        
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
