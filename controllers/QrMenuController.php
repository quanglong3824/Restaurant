<?php
// ============================================================
// QrMenuController — Aurora Restaurant
// ============================================================

class QrMenuController extends Controller
{
    private QrTable $qrModel;
    private Table $tableModel;
    private MenuItem $menuModel;
    private MenuCategory $categoryModel;
    private Order $orderModel;
    private CustomerSession $sessionModel;

    public function __construct()
    {
        $this->qrModel = new QrTable();
        $this->tableModel = new Table();
        $this->menuModel = new MenuItem();
        $this->categoryModel = new MenuCategory();
        $this->orderModel = new Order();
        $this->sessionModel = new CustomerSession();
    }

    /** View menu for customer */
    public function index(): void
    {
        $tableId = (int)($_GET['table_id'] ?? 0);
        $token = $_GET['token'] ?? '';

        if (!$tableId || !$token) {
            $this->render('errors/404', ['message' => 'Mã QR không hợp lệ hoặc thiếu thông tin bàn.']);
            return;
        }

        $qrTable = $this->qrModel->findByToken($token);
        if (!$qrTable || $qrTable['table_id'] != $tableId) {
            $this->render('errors/404', ['message' => 'Mã QR đã hết hạn hoặc không hợp lệ.']);
            return;
        }

        // Increment scan count
        $this->qrModel->incrementScanCount($qrTable['id']);

        // Set customer session
        $this->setupCustomerSession($tableId, $token);

        $table = $this->tableModel->findById($tableId);
        $categories = $this->categoryModel->getAll();
        $menuItems = $this->menuModel->getAllActive();

        // Get open order for this table if exists
        $openOrder = $this->orderModel->findOpenByTable($tableId);
        
        $this->render('menu/customer', [
            'table' => $table,
            'categories' => $categories,
            'menuItems' => $menuItems,
            'openOrder' => $openOrder,
            'isCustomer' => true
        ], 'public');
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
