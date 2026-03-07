<?php
// ============================================================
// Order Model — Aurora Restaurant
// ============================================================

class Order extends Model
{
    /** Mở order mới cho bàn */
    public function create(int $tableId, ?int $waiterId = null, int $guestCount = 1, ?int $shiftId = null): int
    {
        $this->execute(
            "INSERT INTO orders (table_id, waiter_id, shift_id, guest_count, status, opened_at)
             VALUES (?, ?, ?, ?, 'open', NOW())",
            [$tableId, $waiterId, $shiftId, $guestCount]
        );
        return (int) $this->lastInsertId();
    }

    /** Lấy order đang mở của một bàn (không yêu cầu nhân viên) */
    public function findOpenOrderByTable(int $tableId): ?array
    {
        return $this->findOne(
            "SELECT * FROM orders WHERE table_id = ? AND status = 'open' ORDER BY opened_at DESC LIMIT 1",
            [$tableId]
        );
    }

    /** Lấy order đang mở của một bàn (JOIN chi tiết) */
    public function getOpenByTable(int $tableId): ?array
    {
        return $this->findOne(
            "SELECT o.*, u.name AS waiter_name, t.name AS table_name
             FROM orders o
             LEFT JOIN users u ON u.id = o.waiter_id
             JOIN tables t ON t.id = o.table_id
             WHERE o.table_id = ? AND o.status = 'open'
             ORDER BY o.opened_at DESC
             LIMIT 1",
            [$tableId]
        );
    }

    /** Lấy order theo ID */
    public function findById(int $id): ?array
    {
        return $this->findOne(
            "SELECT o.*, u.name AS waiter_name, t.name AS table_name
             FROM orders o
             LEFT JOIN users u ON u.id = o.waiter_id
             JOIN tables t ON t.id = o.table_id
             WHERE o.id = ?",
            [$id]
        );
    }

    /** Lấy các món trong order */
    public function getItems(int $orderId): array
    {
        return $this->findAll(
            "SELECT oi.*, m.image
             FROM order_items oi
             JOIN menu_items m ON m.id = oi.menu_item_id
             WHERE oi.order_id = ?
             ORDER BY oi.created_at",
            [$orderId]
        );
    }

    /** Thêm món vào order (hoặc tăng số lượng nếu đã có) */
    public function addItem(int $orderId, int $menuItemId, string $itemName, float $itemPrice, int $qty = 1, string $note = ''): void
    {
        $existing = $this->findOne(
            "SELECT id, quantity FROM order_items
             WHERE order_id = ? AND menu_item_id = ? AND note = ?",
            [$orderId, $menuItemId, $note]
        );

        if ($existing) {
            $this->execute(
                "UPDATE order_items SET quantity = quantity + ? WHERE id = ?",
                [$qty, $existing['id']]
            );
        } else {
            $this->execute(
                "INSERT INTO order_items (order_id, menu_item_id, item_name, item_price, quantity, note)
                 VALUES (?, ?, ?, ?, ?, ?)",
                [$orderId, $menuItemId, $itemName, $itemPrice, $qty, $note]
            );
        }
    }

    /** Cập nhật số lượng, nếu = 0 thì xóa */
    public function updateItem(int $itemId, int $qty): void
    {
        if ($qty <= 0) {
            $this->execute("DELETE FROM order_items WHERE id = ? AND status = 'draft'", [$itemId]);
        } else {
            $this->execute("UPDATE order_items SET quantity = ? WHERE id = ? AND status = 'draft'", [$qty, $itemId]);
        }
    }

    /** Xóa một dòng order item */
    public function removeItem(int $itemId): void
    {
        $this->execute("DELETE FROM order_items WHERE id = ? AND status = 'draft'", [$itemId]);
    }

    /** Đóng order & đóng bàn (Thanh toán) */
    public function close(int $orderId, string $paymentMethod): void
    {
        $this->execute(
            "UPDATE orders SET status = 'closed', closed_at = NOW(), 
             payment_method = ?, payment_status = 'paid' WHERE id = ?",
            [$paymentMethod, $orderId]
        );
    }

    public function cancel(int $orderId): void
    {
        // Use 'closed' instead of 'canceled' because the status ENUM only has 'open' and 'closed'
        $this->execute(
            "UPDATE orders SET status = 'closed', closed_at = NOW() WHERE id = ?",
            [$orderId]
        );

        try {
            // Attempt to update payment_status if the column exists, ignoring errors if it doesn't
            $this->execute(
                "UPDATE orders SET payment_status = 'canceled' WHERE id = ?",
                [$orderId]
            );
        } catch (\Exception $e) {
            // Do nothing if payment_status doesn't exist or doesn't support 'canceled'
        }
    }

    /** Lấy tất cả Order Đang Bận (Cho View Orders) */
    public function getAllOpen(): array
    {
        return $this->findAll(
            "SELECT o.*, u.name AS waiter_name, t.name AS table_name, t.area AS table_area,
                   (SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id) AS total,
                   (SELECT COUNT(oi.id) FROM order_items oi WHERE oi.order_id = o.id) AS item_count
             FROM orders o
             LEFT JOIN users u ON u.id = o.waiter_id
             JOIN tables t ON t.id = o.table_id
             WHERE o.status = 'open'
             ORDER BY o.opened_at DESC"
        );
    }

    /** Xác nhận các món Draft thành Confirmed (Gửi bếp) */
    public function confirmItems(int $orderId): void
    {
        $this->execute(
            "UPDATE order_items SET status = 'confirmed' WHERE order_id = ? AND status = 'draft'",
            [$orderId]
        );
    }

    /** Tính tổng tiền order */
    public function getTotal(int $orderId): float
    {
        $row = $this->findOne(
            "SELECT SUM(item_price * quantity) AS total FROM order_items WHERE order_id = ?",
            [$orderId]
        );
        return (float) ($row['total'] ?? 0);
    }

    /** Danh sách orders trong ngày (báo cáo) */
    public function getByDate(string $date): array
    {
        return $this->findAll(
            "SELECT o.*, u.name AS waiter_name, t.name AS table_name,
                    (SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id) AS total
             FROM orders o
             JOIN users u ON u.id = o.waiter_id
             JOIN tables t ON t.id = o.table_id
             WHERE DATE(o.opened_at) = ?
             ORDER BY o.opened_at DESC",
            [$date]
        );
    }

    /** Stat báo cáo */
    public function getStatsByDateRange(string $from, string $to): array
    {
        return $this->findOne(
            "SELECT
                COUNT(*) AS total_orders,
                COUNT(DISTINCT table_id) AS tables_served,
                SUM((SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id)) AS revenue
             FROM orders o
             WHERE DATE(opened_at) BETWEEN ? AND ?",
            [$from, $to]
        ) ?? [];
    }

    /** Orders theo tháng (chart) */
    public function getDailyRevenue(string $from, string $to): array
    {
        return $this->findAll(
            "SELECT DATE(opened_at) AS day,
                    COUNT(*) AS orders,
                    SUM((SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id)) AS revenue
             FROM orders o
             WHERE DATE(opened_at) BETWEEN ? AND ?
             GROUP BY DATE(opened_at)
             ORDER BY day",
            [$from, $to]
        );
    }
}
