<?php
// ============================================================
// AdminQrController — Aurora Restaurant
// ============================================================

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

        $this->render('admin/tables/qr_codes', [
            'qrCodes' => $qrCodes,
            'tables' => $tables
        ], 'admin');
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
        $this->render('admin/tables/qr_download', [
            'tableId' => $tableId,
            'token' => $token
        ], 'admin');
    }
}
