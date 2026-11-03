<?php
/**
 * =======================================================================
 * LỚP MODEL QUẢN LÝ ĐƠN HÀNG (ORDERMODEL.PHP)
 * =======================================================================
 */

require_once ROOT_PATH . '/models/Database.php';

class OrderModel {
    /**
     * Tạo đơn hàng mới kèm theo chi tiết các món hàng (sử dụng Transaction)
     */
    public static function createOrder(array $orderData, array $items): int {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();

        try {
            // 1. Tạo bản ghi đơn hàng
            $sql = "INSERT INTO orders (
                        order_code, user_id, customer_name, customer_phone, customer_email,
                        shipping_address, shipping_method, shipping_fee, payment_method,
                        payment_type, payment_status, subtotal, discount_amount, total_amount,
                        note, status
                    ) VALUES (
                        :order_code, :user_id, :customer_name, :customer_phone, :customer_email,
                        :shipping_address, :shipping_method, :shipping_fee, :payment_method,
                        :payment_type, :payment_status, :subtotal, :discount_amount, :total_amount,
                        :note, :status
                    )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'order_code'       => $orderData['order_code'],
                'user_id'          => $orderData['user_id'] ?? null,
                'customer_name'    => $orderData['customer_name'],
                'customer_phone'   => $orderData['customer_phone'],
                'customer_email'   => $orderData['customer_email'] ?? null,
                'shipping_address' => $orderData['shipping_address'],
                'shipping_method'  => $orderData['shipping_method'] ?? 'standard',
                'shipping_fee'     => $orderData['shipping_fee'] ?? 0,
                'payment_method'   => $orderData['payment_method'] ?? 'bank_transfer',
                'payment_type'     => $orderData['payment_type'] ?? 'full',
                'payment_status'   => $orderData['payment_status'] ?? 'pending',
                'subtotal'         => $orderData['subtotal'],
                'discount_amount'  => $orderData['discount_amount'] ?? 0,
                'total_amount'     => $orderData['total_amount'],
                'note'             => $orderData['note'] ?? null,
                'status'           => 'pending'
            ]);

            $orderId = (int)$pdo->lastInsertId();

            // 2. Chèn từng món vào order_items
            $itemSql = "INSERT INTO order_items (
                            order_id, product_id, product_name, product_image,
                            price, quantity, total, gift_info
                        ) VALUES (
                            :order_id, :product_id, :product_name, :product_image,
                            :price, :quantity, :total, :gift_info
                        )";
            $itemStmt = $pdo->prepare($itemSql);

            foreach ($items as $item) {
                $price = $item['sale_price'] ?? $item['price'];
                $qty = (int)($item['quantity'] ?? 1);
                $total = $price * $qty;

                $itemStmt->execute([
                    'order_id'      => $orderId,
                    'product_id'    => $item['id'],
                    'product_name'  => $item['name'],
                    'product_image' => $item['image'],
                    'price'         => $price,
                    'quantity'      => $qty,
                    'total'         => $total,
                    'gift_info'     => $item['gift_info'] ?? 'Phiếu mua hàng mua Pin tiểu trị giá 20.000đ'
                ]);

                // Tăng số lượng đã bán của sản phẩm
                $updateSoldSql = "UPDATE products SET sold_count = sold_count + :qty WHERE id = :pid";
                $updateSoldStmt = $pdo->prepare($updateSoldSql);
                $updateSoldStmt->execute(['qty' => $qty, 'pid' => $item['id']]);
            }

            $pdo->commit();
            return $orderId;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Lấy đơn hàng theo ID
     */
    public static function findById(int $id): ?array {
        $sql = "SELECT * FROM orders WHERE id = :id LIMIT 1";
        return Database::fetchOne($sql, ['id' => $id]);
    }

    /**
     * Lấy đơn hàng theo Mã đơn
     */
    public static function findByCode(string $orderCode): ?array {
        $sql = "SELECT * FROM orders WHERE order_code = :code LIMIT 1";
        return Database::fetchOne($sql, ['code' => $orderCode]);
    }

    /**
     * Lấy danh sách sản phẩm trong đơn hàng
     */
    public static function getOrderItems(int $orderId): array {
        $sql = "SELECT * FROM order_items WHERE order_id = :order_id";
        return Database::fetchAll($sql, ['order_id' => $orderId]);
    }

    /**
     * Lấy lịch sử đơn hàng của người dùng
     */
    public static function getOrdersByUser(int $userId): array {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC";
        return Database::fetchAll($sql, ['user_id' => $userId]);
    }

    /**
     * Lấy toàn bộ đơn hàng cho Admin
     */
    public static function getAll(): array {
        $sql = "SELECT * FROM orders ORDER BY id DESC";
        return Database::fetchAll($sql);
    }

    /**
     * Cập nhật trạng thái đơn hàng (Đang xử lý, Đang giao, Hoàn thành, Hủy)
     */
    public static function updateStatus(int $id, string $status): bool {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        return Database::execute($sql, ['id' => $id, 'status' => $status]) >= 0;
    }

    /**
     * Cập nhật trạng thái thanh toán
     */
    public static function updatePaymentStatus(int $id, string $paymentStatus): bool {
        $sql = "UPDATE orders SET payment_status = :payment_status WHERE id = :id";
        return Database::execute($sql, ['id' => $id, 'payment_status' => $paymentStatus]) >= 0;
    }

    /**
     * Lấy số liệu thống kê cho Dashboard Admin
     */
    public static function getDashboardStats(): array {
        $stats = [
            'total_revenue'   => 0,
            'total_orders'    => 0,
            'completed_orders'=> 0,
            'pending_orders'  => 0,
            'total_users'     => 0,
            'total_products'  => 0,
            'recent_orders'   => []
        ];

        $revResult = Database::fetchOne("SELECT SUM(total_amount) as revenue FROM orders WHERE status = 'completed' OR payment_status = 'paid'");
        $stats['total_revenue'] = (float)($revResult['revenue'] ?? 0);

        $orderCount = Database::fetchOne("SELECT COUNT(*) as cnt FROM orders");
        $stats['total_orders'] = (int)($orderCount['cnt'] ?? 0);

        $compCount = Database::fetchOne("SELECT COUNT(*) as cnt FROM orders WHERE status = 'completed'");
        $stats['completed_orders'] = (int)($compCount['cnt'] ?? 0);

        $pendCount = Database::fetchOne("SELECT COUNT(*) as cnt FROM orders WHERE status = 'pending'");
        $stats['pending_orders'] = (int)($pendCount['cnt'] ?? 0);

        $userCount = Database::fetchOne("SELECT COUNT(*) as cnt FROM users WHERE role = 'user'");
        $stats['total_users'] = (int)($userCount['cnt'] ?? 0);

        $prodCount = Database::fetchOne("SELECT COUNT(*) as cnt FROM products");
        $stats['total_products'] = (int)($prodCount['cnt'] ?? 0);

        $stats['recent_orders'] = Database::fetchAll("SELECT * FROM orders ORDER BY id DESC LIMIT 5");

        return $stats;
    }
}
