<?php
// ============================================================
// AdminQrController — Aurora Restaurant
// ============================================================

require_once BASE_PATH . '/models/QrTable.php';
require_once BASE_PATH . '/models/Table.php';

class AdminQrController extends Controller
{
    private QrTable $qrModel;
    private Table $tableModel;

    public function __construct()
    {
        Auth::requireRole(['admin', 'it']);
        $this->qrModel = new QrTable();
        $this->tableModel = new Table();
    }

    public function index(): void
    {
        $qrCodes = $this->qrModel->getAllWithTableInfo();
        $tables = $this->tableModel->getAll();

        $this->view('layouts/admin', [
            'view' => 'admin/tables/qr_codes',
            'pageTitle' => 'Quản lý mã QR',
            'qrCodes' => $qrCodes,
            'tables' => $tables
        ]);
    }

    public function generate(): void
    {
        $tableId = (int)$_POST['table_id'];
        if (!$tableId) {
            $this->redirect('/admin/qr-codes');
            return;
        }

        // Generate a random unique token
        $token = bin2hex(random_bytes(16));
        $this->qrModel->generate($tableId, $token);

        $this->redirect('/admin/qr-codes');
    }

    public function download(): void
    {
        // Implementation for downloading/printing QR codes
        // This could use the phpqrcode library
        $tableId = (int)$_GET['table_id'];
        $token = $_GET['token'];

        // Redirect to a page that renders the QR code
        $this->view('layouts/admin', [
            'view' => 'admin/tables/qr_download',
            'pageTitle' => 'Tải mã QR',
            'tableId' => $tableId,
            'token' => $token
        ]);
    }

    public function delete(): void
    {
        $id = (int)$_POST['id'];
        if ($id) {
            $this->qrModel->execute("DELETE FROM qr_tables WHERE id = ?", [$id]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xóa mã QR thành công.'];
        }
        $this->redirect('/admin/qr-codes');
    }
}
