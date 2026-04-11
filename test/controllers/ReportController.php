<?php
/**
 * ReportController - Quản lý báo cáo thống kê
 * Aurora Restaurant POS System
 */
require_once BASE_PATH . '/models/Order.php';
require_once BASE_PATH . '/models/MenuItem.php';

class ReportController extends Controller
{
    /**
     * Hiển thị trang báo cáo thống kê
     */
    public function index(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $orderModel = new Order();
        $itemModel = new MenuItem();

        // Lấy ngày từ request hoặc mặc định
        $today = date('Y-m-d');
        $from = $this->input('from', date('Y-m-01'));  // Đầu tháng hiện tại
        $to = $this->input('to', $today);

        // Lấy dữ liệu thống kê
        $stats = $orderModel->getStatsByDateRange($from, $to);
        $daily = $orderModel->getDailyRevenue($from, $to);
        $topItems = $itemModel->getTopItems(10, $from, $to);
        $todayOrders = $orderModel->getByDate($today);

        $this->view('layouts/admin', [
            'view' => 'reports/index',
            'pageTitle' => 'Báo cáo thống kê',
            'pageSubtitle' => "Từ " . date('d/m/Y', strtotime($from)) . " đến " . date('d/m/Y', strtotime($to)),
            'stats' => $stats,
            'daily' => $daily,
            'topItems' => $topItems,
            'todayOrders' => $todayOrders,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
