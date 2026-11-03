<?php
/**
 * =======================================================================
 * CONTROLLER GIỎ HÀNG & THANH TOÁN (CARTCONTROLLER.PHP)
 * Phụ trách thêm vào giỏ, cập nhật, xóa, trang Giỏ hàng và trang Thanh toán
 * Giao diện và luồng xử lý khớp chính xác ảnh 3, ảnh 4 và ảnh 5
 * =======================================================================
 */

require_once ROOT_PATH . '/models/ProductModel.php';
require_once ROOT_PATH . '/models/OrderModel.php';

class CartController {
    /**
     * Khởi tạo session giỏ hàng nếu chưa có
     */
    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Hiển thị trang Giỏ hàng (Giao diện ảnh 3)
     */
    public function index() {
        $cartItems = $_SESSION['cart'];
        $cartTotal = getCartTotal();
        $cartCount = getCartCount();

        // Mặc định mẫu sản phẩm nồi cơm điện nếu giỏ hàng rỗng để người dùng trải nghiệm ngay
        if (empty($cartItems)) {
            $defaultProduct = ProductModel::findById(1);
            if ($defaultProduct) {
                $_SESSION['cart'][1] = [
                    'id'          => $defaultProduct['id'],
                    'name'        => $defaultProduct['name'],
                    'image'       => $defaultProduct['image'],
                    'price'       => $defaultProduct['price'],
                    'sale_price'  => $defaultProduct['sale_price'] ?? $defaultProduct['price'],
                    'quantity'    => 1,
                    'gift_info'   => $defaultProduct['gift_info'] ?: 'Phiếu mua hàng mua Pin tiểu (đơn hàng từ 80.000đ trở lên) trị giá 20.000đ'
                ];
                $cartItems = $_SESSION['cart'];
                $cartTotal = getCartTotal();
                $cartCount = getCartCount();
            }
        }

        $pageTitle = "Giỏ hàng của bạn - Điện máy XANH";

        require_once VIEW_PATH . '/client/layouts/header.php';
        require_once VIEW_PATH . '/client/pages/cart.php';
        require_once VIEW_PATH . '/client/layouts/footer.php';
    }

    /**
     * Thêm sản phẩm vào giỏ hàng (Hỗ trợ cả Form thường và AJAX)
     */
    public function add() {
        $productId = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
        $quantity = isset($_REQUEST['quantity']) ? max(1, (int)$_REQUEST['quantity']) : 1;
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        $product = ProductModel::findById($productId);
        if (!$product) {
            if ($isAjax) {
                sendJsonResponse(['success' => false, 'message' => 'Sản phẩm không tồn tại!'], 404);
            }
            setFlashMessage('error', 'Sản phẩm không tồn tại!');
            redirect(APP_URL . '/index.php');
        }

        // Cập nhật số lượng nếu đã có trong giỏ
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'id'          => $product['id'],
                'name'        => $product['name'],
                'image'       => $product['image'],
                'price'       => $product['price'],
                'sale_price'  => $product['sale_price'] ?? $product['price'],
                'quantity'    => $quantity,
                'gift_info'   => $product['gift_info'] ?: 'Phiếu mua hàng mua Pin tiểu (đơn hàng từ 80.000đ trở lên) trị giá 20.000đ'
            ];
        }

        if ($isAjax) {
            sendJsonResponse([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng thành công!',
                'cartCount' => getCartCount(),
                'cartTotal' => formatCurrency(getCartTotal())
            ]);
        }

        // Nếu là nút "Mua ngay" thì chuyển thẳng đến giỏ hàng
        redirect(APP_URL . '/index.php?act=cart');
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update() {
        $productId = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
        $quantity = isset($_REQUEST['quantity']) ? (int)$_REQUEST['quantity'] : 1;
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if (isset($_SESSION['cart'][$productId])) {
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId]['quantity'] = $quantity;
            }
        }

        if ($isAjax) {
            $itemTotal = isset($_SESSION['cart'][$productId]) 
                ? formatCurrency(($_SESSION['cart'][$productId]['sale_price'] ?? $_SESSION['cart'][$productId]['price']) * $_SESSION['cart'][$productId]['quantity'])
                : '0₫';

            sendJsonResponse([
                'success'   => true,
                'cartCount' => getCartCount(),
                'cartTotal' => formatCurrency(getCartTotal()),
                'itemTotal' => $itemTotal
            ]);
        }

        redirect(APP_URL . '/index.php?act=cart');
    }

    /**
     * Xóa 1 sản phẩm khỏi giỏ hàng
     */
    public function remove() {
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            setFlashMessage('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
        }
        redirect(APP_URL . '/index.php?act=cart');
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear() {
        $_SESSION['cart'] = [];
        setFlashMessage('success', 'Đã làm trống giỏ hàng!');
        redirect(APP_URL . '/index.php?act=cart');
    }

    /**
     * Hiển thị trang Thanh toán (Giao diện ảnh 4 & 5)
     */
    public function checkout() {
        $cartItems = $_SESSION['cart'];
        if (empty($cartItems)) {
            setFlashMessage('error', 'Giỏ hàng của bạn đang trống! Hãy chọn sản phẩm trước khi thanh toán.');
            redirect(APP_URL . '/index.php?act=cart');
        }

        $cartTotal = getCartTotal();
        $user = currentUser();

        // Mặc định địa chỉ và thông tin theo ảnh 4 nếu chưa có
        $defaultCustomer = [
            'name'    => $user['fullname'] ?? 'Anh Ngô Quang Minh',
            'phone'   => $user['phone'] ?? '0342586038',
            'email'   => $user['email'] ?? 'minh.ngo@example.com',
            'address' => $user['address'] ?? 'ngõ 114, Phường Phan Đình Phùng, Tỉnh Thái Nguyên'
        ];

        $pageTitle = "Thanh toán đơn hàng - Điện máy XANH";

        require_once VIEW_PATH . '/client/layouts/header.php';
        require_once VIEW_PATH . '/client/pages/checkout.php';
        require_once VIEW_PATH . '/client/layouts/footer.php';
    }

    /**
     * Xử lý xác nhận đặt hàng và lưu vào cơ sở dữ liệu
     */
    public function processOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(APP_URL . '/index.php?act=checkout');
        }

        $cartItems = $_SESSION['cart'];
        if (empty($cartItems)) {
            setFlashMessage('error', 'Giỏ hàng rỗng, không thể tạo đơn hàng!');
            redirect(APP_URL . '/index.php?act=cart');
        }

        $fullname = sanitizeInput($_POST['customer_name'] ?? '');
        $phone = sanitizeInput($_POST['customer_phone'] ?? '');
        $email = sanitizeInput($_POST['customer_email'] ?? '');
        $address = sanitizeInput($_POST['shipping_address'] ?? '');
        $shippingMethod = sanitizeInput($_POST['shipping_method'] ?? 'standard');
        $paymentMethod = sanitizeInput($_POST['payment_method'] ?? 'bank_transfer');
        $paymentType = sanitizeInput($_POST['payment_type'] ?? 'full');
        $note = sanitizeInput($_POST['note'] ?? '');

        if (empty($fullname) || empty($phone) || empty($address)) {
            setFlashMessage('error', 'Vui lòng điền đầy đủ họ tên, số điện thoại và địa chỉ giao hàng!');
            redirect(APP_URL . '/index.php?act=checkout');
        }

        $subtotal = getCartTotal();
        $shippingFee = ($shippingMethod === 'express') ? 10000 : 0;
        $totalAmount = $subtotal + $shippingFee;

        // Mã đơn hàng ngẫu nhiên: DMX + ngày giờ + 4 số ngẫu nhiên
        $orderCode = 'DMX' . date('YmdHis') . rand(100, 999);
        $user = currentUser();

        $orderData = [
            'order_code'       => $orderCode,
            'user_id'          => $user['id'] ?? null,
            'customer_name'    => $fullname,
            'customer_phone'   => $phone,
            'customer_email'   => $email,
            'shipping_address' => $address,
            'shipping_method'  => $shippingMethod,
            'shipping_fee'     => $shippingFee,
            'payment_method'   => $paymentMethod,
            'payment_type'     => $paymentType,
            'payment_status'   => ($paymentMethod === 'bank_transfer') ? 'paid' : 'pending',
            'subtotal'         => $subtotal,
            'discount_amount'  => 0,
            'total_amount'     => $totalAmount,
            'note'             => $note
        ];

        try {
            $orderId = OrderModel::createOrder($orderData, $cartItems);

            // Làm trống giỏ hàng sau khi đặt thành công
            $_SESSION['cart'] = [];
            $_SESSION['last_order'] = [
                'order_id'   => $orderId,
                'order_code' => $orderCode,
                'total'      => $totalAmount,
                'name'       => $fullname,
                'phone'      => $phone,
                'address'    => $address,
                'method'     => $paymentMethod
            ];

            setFlashMessage('success', "Đặt hàng thành công! Mã đơn của bạn là #{$orderCode}");
            redirect(APP_URL . '/index.php?act=order_success&code=' . $orderCode);

        } catch (Exception $e) {
            setFlashMessage('error', 'Lỗi khi lưu đơn hàng: ' . $e->getMessage());
            redirect(APP_URL . '/index.php?act=checkout');
        }
    }

    /**
     * Trang thông báo đặt hàng thành công
     */
    public function orderSuccess() {
        $orderCode = sanitizeInput($_GET['code'] ?? '');
        $order = OrderModel::findByCode($orderCode);

        if (!$order && isset($_SESSION['last_order'])) {
            $order = OrderModel::findById($_SESSION['last_order']['order_id']);
        }

        $items = $order ? OrderModel::getOrderItems($order['id']) : [];
        $pageTitle = "Đặt hàng thành công - Điện máy XANH";

        require_once VIEW_PATH . '/client/layouts/header.php';
        ?>
        <div class="container my-5" style="max-width: 800px; padding: 40px 20px;">
            <div style="background: white; border-radius: 16px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); text-align: center;">
                <div style="width: 80px; height: 80px; background: #e8f5e9; color: #2e7d32; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 36px; margin: 0 auto 20px;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h1 style="color: #0071e3; font-size: 26px; font-weight: 700; margin-bottom: 10px;">ĐẶT HÀNG THÀNH CÔNG!</h1>
                <p style="color: #555; font-size: 15px; margin-bottom: 25px;">
                    Cảm ơn Quý khách <strong><?= htmlspecialchars($order['customer_name'] ?? 'Quý khách') ?></strong> đã tin tưởng mua sắm tại <strong>Điện máy XANH</strong>.
                </p>

                <div style="background: #f8f9fa; border: 1px dashed #0071e3; border-radius: 12px; padding: 20px; text-align: left; margin-bottom: 30px;">
                    <p style="margin-bottom: 8px;"><strong>Mã đơn hàng:</strong> <span style="color: #d70018; font-weight: 700;"><?= htmlspecialchars($order['order_code'] ?? $orderCode) ?></span></p>
                    <p style="margin-bottom: 8px;"><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['customer_phone'] ?? '') ?></p>
                    <p style="margin-bottom: 8px;"><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($order['shipping_address'] ?? '') ?></p>
                    <p style="margin-bottom: 8px;"><strong>Phương thức thanh toán:</strong> 
                        <?= ($order['payment_method'] ?? '') === 'bank_transfer' ? 'Chuyển khoản ngân hàng (Đã xác nhận)' : 'Thanh toán khi nhận hàng (COD)' ?>
                    </p>
                    <p style="margin-bottom: 0;"><strong>Tổng tiền thanh toán:</strong> <span style="color: #d70018; font-size: 18px; font-weight: 700;"><?= formatCurrency($order['total_amount'] ?? 0) ?></span></p>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <a href="<?= APP_URL ?>" style="display: inline-block; background: #0071e3; color: white; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        <i class="fa-solid fa-house"></i> Về Trang Chủ Tiếp Tục Mua Sắm
                    </a>
                </div>
            </div>
        </div>
        <?php
        require_once VIEW_PATH . '/client/layouts/footer.php';
    }
}
