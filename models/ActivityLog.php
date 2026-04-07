<?php
// ============================================================
// ActivityLog Model — Aurora Restaurant
// Ghi nhận toàn bộ hoạt động trong hệ thống
// ============================================================

class ActivityLog extends Model
{
    /**
     * Các loại hoạt động (action types)
     */
    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    const ACTION_VIEW = 'view';
    const ACTION_EXPORT = 'export';
    const ACTION_IMPORT = 'import';
    const ACTION_CONFIRM = 'confirm';
    const ACTION_CANCEL = 'cancel';
    const ACTION_TRANSFER = 'transfer';
    const ACTION_MERGE = 'merge';
    const ACTION_SPLIT = 'split';
    const ACTION_PAYMENT = 'payment';
    const ACTION_CALL_WAITER = 'call_waiter';
    const ACTION_REQUEST_BILL = 'request_bill';
    const ACTION_SCAN_QR = 'scan_qr';
    const ACTION_LOCATION_CHECK = 'location_check';
    const ACTION_SESSION_CREATE = 'session_create';
    const ACTION_SESSION_CLEAR = 'session_clear';
    const ACTION_UPLOAD = 'upload';
    const ACTION_DOWNLOAD = 'download';
    const ACTION_BACKUP = 'backup';
    const ACTION_CLEANUP = 'cleanup';
    const ACTION_ERROR = 'error';
    const ACTION_WARNING = 'warning';
    const ACTION_SYSTEM = 'system';

    /**
     * Các mức độ quan trọng
     */
    const LEVEL_INFO = 'info';
    const LEVEL_NOTICE = 'notice';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR = 'error';
    const LEVEL_CRITICAL = 'critical';

    /**
     * Ghi log hoạt động
     * 
     * @param string $action Loại hoạt động
     * @param string $entity Thực thể bị tác động (table, order, menu, user...)
     * @param int|null $entityId ID của thực thể
     * @param array $metadata Dữ liệu metadata (JSON)
     * @param string $level Mức độ quan trọng
     * @param int|null $userId ID người thực hiện (null = system)
     * @return int ID của log vừa tạo
     */
    public function log(
        string $action,
        string $entity,
        ?int $entityId = null,
        array $metadata = [],
        string $level = self::LEVEL_INFO,
        ?int $userId = null
    ): int {
        // Kiểm tra bảng tồn tại trước khi log
        try {
            $db = getDB();
            $stmt = $db->query("SHOW TABLES LIKE 'activity_logs'");
            if ($stmt->rowCount() === 0) {
                // Bảng chưa tồn tại, không log nữa
                return 0;
            }
        } catch (\Throwable $e) {
            // Lỗi khi kiểm tra bảng, không log nữa
            return 0;
        }

        // Tự động lấy user_id từ session nếu không truyền vào
        if ($userId === null && Auth::isLoggedIn()) {
            $userId = Auth::user()['id'];
        }

        // Lấy IP và User Agent
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        // Lấy URL hiện tại
        $requestUri = $_SERVER['REQUEST_URI'] ?? 'unknown';
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'unknown';

        try {
            $this->execute(
                "INSERT INTO activity_logs 
                (action, entity, entity_id, user_id, ip_address, user_agent, request_uri, request_method, metadata, level, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [
                    $action,
                    $entity,
                    $entityId,
                    $userId,
                    $ipAddress,
                    $userAgent,
                    $requestUri,
                    $requestMethod,
                    json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    $level
                ]
            );

            return (int) $this->lastInsertId();
        } catch (\Throwable $e) {
            // Lỗi khi insert log, bỏ qua để không làm gián đoạn luồng chính
            return 0;
        }
    }

    /**
     * Lấy danh sách logs với phân trang và lọc
     */
    public function getLogs(array $filters = [], int $page = 1, int $limit = 50): array
    {
        $where = ['1=1'];
        $params = [];

        // Filter by date range
        if (!empty($filters['from'])) {
            $where[] = "DATE(created_at) >= ?";
            $params[] = $filters['from'];
        }
        if (!empty($filters['to'])) {
            $where[] = "DATE(created_at) <= ?";
            $params[] = $filters['to'];
        }

        // Filter by action
        if (!empty($filters['action'])) {
            $where[] = "action = ?";
            $params[] = $filters['action'];
        }

        // Filter by entity
        if (!empty($filters['entity'])) {
            $where[] = "entity = ?";
            $params[] = $filters['entity'];
        }

        // Filter by entity_id
        if (isset($filters['entity_id']) && $filters['entity_id'] !== '') {
            $where[] = "entity_id = ?";
            $params[] = $filters['entity_id'];
        }

        // Filter by user_id
        if (isset($filters['user_id']) && $filters['user_id'] !== '') {
            $where[] = "user_id = ?";
            $params[] = $filters['user_id'];
        }

        // Filter by level
        if (!empty($filters['level'])) {
            $where[] = "level = ?";
            $params[] = $filters['level'];
        }

        // Filter by IP
        if (!empty($filters['ip_address'])) {
            $where[] = "ip_address = ?";
            $params[] = $filters['ip_address'];
        }

        $whereClause = implode(' AND ', $where);
        $offset = ($page - 1) * $limit;

        $sql = "SELECT al.*, 
                       u.name AS user_name, 
                       u.role AS user_role,
                       CASE al.entity
                           WHEN 'table' THEN (SELECT name FROM tables WHERE id = al.entity_id)
                           WHEN 'order' THEN (SELECT CONCAT('Order #', id) FROM orders WHERE id = al.entity_id)
                           WHEN 'menu_item' THEN (SELECT name FROM menu_items WHERE id = al.entity_id)
                           WHEN 'user' THEN (SELECT name FROM users WHERE id = al.entity_id)
                           ELSE NULL
                       END AS entity_label
                FROM activity_logs al
                LEFT JOIN users u ON u.id = al.user_id
                WHERE {$whereClause}
                ORDER BY al.created_at DESC
                LIMIT ? OFFSET ?";

        $params[] = $limit;
        $params[] = $offset;

        return $this->findAll($sql, $params);
    }

    /**
     * Đếm tổng số logs
     */
    public function countLogs(array $filters = []): int
    {
        try {
            $where = ['1=1'];
            $params = [];

            if (!empty($filters['from'])) {
                $where[] = "DATE(created_at) >= ?";
                $params[] = $filters['from'];
            }
            if (!empty($filters['to'])) {
                $where[] = "DATE(created_at) <= ?";
                $params[] = $filters['to'];
            }
            if (!empty($filters['action'])) {
                $where[] = "action = ?";
                $params[] = $filters['action'];
            }
            if (!empty($filters['entity'])) {
                $where[] = "entity = ?";
                $params[] = $filters['entity'];
            }
            if (isset($filters['user_id']) && $filters['user_id'] !== '') {
                $where[] = "user_id = ?";
                $params[] = $filters['user_id'];
            }
            if (!empty($filters['level'])) {
                $where[] = "level = ?";
                $params[] = $filters['level'];
            }

            $whereClause = implode(' AND ', $where);

            $result = $this->findOne("SELECT COUNT(*) as count FROM activity_logs WHERE {$whereClause}", $params);
            return (int) ($result['count'] ?? 0);
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Lấy các action types đã ghi nhận
     */
    public function getActionTypes(): array
    {
        try {
            $result = $this->findAll("SELECT DISTINCT action FROM activity_logs ORDER BY action");
            return array_column($result, 'action');
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Lấy thống kê hoạt động theo ngày
     */
    public function getStatsByDate(string $from, string $to): array
    {
        return $this->findAll(
            "SELECT DATE(created_at) AS date, 
                    action, 
                    COUNT(*) AS count
             FROM activity_logs
             WHERE DATE(created_at) BETWEEN ? AND ?
             GROUP BY DATE(created_at), action
             ORDER BY date DESC, count DESC",
            [$from, $to]
        );
    }

    /**
     * Lấy hoạt động gần đây của một entity
     */
    public function getRecentByEntity(string $entity, int $entityId, int $limit = 20): array
    {
        return $this->findAll(
            "SELECT al.*, u.name AS user_name
             FROM activity_logs al
             LEFT JOIN users u ON u.id = al.user_id
             WHERE al.entity = ? AND al.entity_id = ?
             ORDER BY al.created_at DESC
             LIMIT ?",
            [$entity, $entityId, $limit]
        );
    }

    /**
     * Lấy hoạt động của một user
     */
    public function getByUser(int $userId, int $limit = 50): array
    {
        return $this->findAll(
            "SELECT al.* FROM activity_logs al
             WHERE al.user_id = ?
             ORDER BY al.created_at DESC
             LIMIT ?",
            [$userId, $limit]
        );
    }

    /**
     * Xóa logs cũ (cleanup)
     * @param int $days Số ngày giữ lại
     * @return int Số dòng đã xóa
     */
    public function cleanupOldLogs(int $days = 90): int
    {
        return $this->execute(
            "DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)",
            [$days]
        );
    }

    /**
     * Helper: Log đăng nhập
     */
    public function logLogin(int $userId, bool $success = true, string $reason = ''): int
    {
        return $this->log(
            $success ? self::ACTION_LOGIN : self::ACTION_ERROR,
            'user',
            $userId,
            ['success' => $success, 'reason' => $reason],
            $success ? self::LEVEL_INFO : self::LEVEL_WARNING
        );
    }

    /**
     * Helper: Log đăng xuất
     */
    public function logLogout(int $userId): int
    {
        return $this->log(self::ACTION_LOGOUT, 'user', $userId, [], self::LEVEL_INFO);
    }

    /**
     * Helper: Log tạo mới
     */
    public function logCreate(string $entity, int $entityId, array $data = []): int
    {
        return $this->log(self::ACTION_CREATE, $entity, $entityId, ['data' => $data], self::LEVEL_INFO);
    }

    /**
     * Helper: Log cập nhật
     */
    public function logUpdate(string $entity, int $entityId, array $oldData = [], array $newData = []): int
    {
        return $this->log(self::ACTION_UPDATE, $entity, $entityId, [
            'old_data' => $oldData,
            'new_data' => $newData
        ], self::LEVEL_NOTICE);
    }

    /**
     * Helper: Log xóa
     */
    public function logDelete(string $entity, int $entityId, array $data = []): int
    {
        return $this->log(self::ACTION_DELETE, $entity, $entityId, ['deleted_data' => $data], self::LEVEL_WARNING);
    }

    /**
     * Helper: Log thanh toán
     */
    public function logPayment(int $orderId, float $amount, string $method): int
    {
        return $this->log(self::ACTION_PAYMENT, 'order', $orderId, [
            'amount' => $amount,
            'payment_method' => $method
        ], self::LEVEL_INFO);
    }

    /**
     * Helper: Log chuyển món
     */
    public function logTransfer(int $itemId, int $fromOrderId, int $toOrderId): int
    {
        return $this->log(self::ACTION_TRANSFER, 'order_item', $itemId, [
            'from_order_id' => $fromOrderId,
            'to_order_id' => $toOrderId
        ], self::LEVEL_NOTICE);
    }

    /**
     * Helper: Log tách bàn
     */
    public function logSplit(int $sourceOrderId, int $targetOrderId, array $itemIds): int
    {
        return $this->log(self::ACTION_SPLIT, 'order', $sourceOrderId, [
            'target_order_id' => $targetOrderId,
            'item_ids' => $itemIds
        ], self::LEVEL_NOTICE);
    }

    /**
     * Helper: Log ghép bàn
     */
    public function logMerge(int $childTableId, int $parentTableId): int
    {
        return $this->log(self::ACTION_MERGE, 'table', $childTableId, [
            'parent_table_id' => $parentTableId
        ], self::LEVEL_NOTICE);
    }

    /**
     * Helper: Log scan QR
     */
    public function logScanQr(int $tableId, string $sessionId): int
    {
        return $this->log(self::ACTION_SCAN_QR, 'table', $tableId, [
            'session_id' => $sessionId
        ], self::LEVEL_INFO);
    }

    /**
     * Helper: Log lỗi
     */
    public function logError(string $message, string $entity = 'system', ?int $entityId = null, array $context = []): int
    {
        return $this->log(self::ACTION_ERROR, $entity, $entityId, [
            'message' => $message,
            'context' => $context
        ], self::LEVEL_ERROR);
    }
}