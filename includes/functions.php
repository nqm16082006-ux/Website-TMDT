<?php
/**
 * =======================================================================
 * HÀM TIỆN ÍCH DÙNG CHUNG (FUNCTIONS.PHP)
 * Chứa các hàm xử lý tiền tệ, chuỗi, bảo mật, view và session
 * =======================================================================
 */

/**
 * Định dạng tiền tệ Việt Nam Đồng (VND)
 * Ví dụ: 790000 => "790.000₫"
 */
function formatCurrency($number) {
    if (!is_numeric($number)) {
        $number = 0;
    }
    return number_format($number, 0, ',', '.') . '₫';
}

/**
 * Tính phần trăm giảm giá giữa giá gốc và giá khuyến mãi
 * Ví dụ: 2690000, 790000 => -71%
 */
function calculateDiscountPercent($originalPrice, $salePrice) {
    if ($originalPrice <= 0 || $salePrice >= $originalPrice) {
        return 0;
    }
    return round((($originalPrice - $salePrice) / $originalPrice) * 100);
}

/**
 * Làm sạch dữ liệu đầu vào chống XSS
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Chuyển đổi tiêu đề tiếng Việt thành Slug thân thiện SEO
 */
function slugify($text) {
    // Thay thế ký tự có dấu
    $vietnamese = [
        'à'=>'a', 'á'=>'a', 'ả'=>'a', 'ã'=>'a', 'ạ'=>'a',
        'ă'=>'a', 'ằ'=>'a', 'ắ'=>'a', 'ẳ'=>'a', 'ẵ'=>'a', 'ặ'=>'a',
        'â'=>'a', 'ầ'=>'a', 'ấ'=>'a', 'ẩ'=>'a', 'ẫ'=>'a', 'ậ'=>'a',
        'đ'=>'d',
        'è'=>'e', 'é'=>'e', 'ẻ'=>'e', 'ẽ'=>'e', 'ẹ'=>'e',
        'ê'=>'e', 'ề'=>'e', 'ế'=>'e', 'ể'=>'e', 'ễ'=>'e', 'ệ'=>'e',
        'ì'=>'i', 'í'=>'i', 'ỉ'=>'i', 'ĩ'=>'i', 'ị'=>'i',
        'ò'=>'o', 'ó'=>'o', 'ỏ'=>'o', 'õ'=>'o', 'ọ'=>'o',
        'ô'=>'o', 'ồ'=>'o', 'ố'=>'o', 'ổ'=>'o', 'ỗ'=>'o', 'ộ'=>'o',
        'ơ'=>'o', 'ờ'=>'o', 'ớ'=>'o', 'ở'=>'o', 'ỡ'=>'o', 'ợ'=>'o',
        'ù'=>'u', 'ú'=>'u', 'ủ'=>'u', 'ũ'=>'u', 'ụ'=>'u',
        'ư'=>'u', 'ừ'=>'u', 'ứ'=>'u', 'ử'=>'u', 'ữ'=>'u', 'ự'=>'u',
        'ỳ'=>'y', 'ý'=>'y', 'ỷ'=>'y', 'ỹ'=>'y', 'ỵ'=>'y',
    ];
    $text = mb_strtolower($text, 'UTF-8');
    $text = strtr($text, $vietnamese);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Điều hướng trang URL
 */
function redirect($url) {
    if (!headers_sent()) {
        header("Location: " . $url);
        exit();
    }
    echo '<script>window.location.href = "' . htmlspecialchars($url, ENT_QUOTES) . '";</script>';
    exit();
}

/**
 * Đặt thông báo Flash (hiển thị 1 lần)
 */
function setFlashMessage($type, $message) {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][$type] = $message;
}

/**
 * Lấy và xóa thông báo Flash
 */
function getFlashMessage($type) {
    if (isset($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
    return null;
}

/**
 * Kiểm tra trạng thái đăng nhập
 */
function isLoggedIn() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

/**
 * Lấy thông tin user hiện tại
 */
function currentUser() {
    return $_SESSION['user'] ?? null;
}

/**
 * Kiểm tra quyền Quản trị viên (Admin)
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

/**
 * Lấy số lượng sản phẩm trong giỏ hàng
 */
function getCartCount() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0;
    }
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += (int)($item['quantity'] ?? 1);
    }
    return $total;
}

/**
 * Tính tổng tiền giỏ hàng
 */
function getCartTotal() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0;
    }
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $price = $item['sale_price'] ?? $item['price'] ?? 0;
        $qty = $item['quantity'] ?? 1;
        $total += ($price * $qty);
    }
    return $total;
}

/**
 * Trả về JSON cho API / AJAX request
 */
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}
