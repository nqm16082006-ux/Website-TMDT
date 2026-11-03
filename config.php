<?php
/**
 * =======================================================================
 * FILE CẤU HÌNH TRUNG TÂM (CONFIG.PHP)
 * Đọc file .env, khởi tạo session, múi giờ và các hằng số hệ thống
 * =======================================================================
 */

// Bắt đầu session nếu chưa được khởi chạy
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Thiết lập múi giờ chuẩn Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Đường dẫn thư mục gốc hệ thống
define('ROOT_PATH', realpath(__DIR__));
define('VIEW_PATH', ROOT_PATH . '/views');
define('CONTROLLER_PATH', ROOT_PATH . '/controllers');
define('MODEL_PATH', ROOT_PATH . '/models');
define('INCLUDES_PATH', ROOT_PATH . '/includes');

/**
 * Hàm đọc và nạp các biến môi trường từ file .env
 */
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Bỏ qua chú thích (#) và dòng trống
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }

        // Tách KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Bỏ dấu nháy kép hoặc đơn nếu có
            $value = trim($value, '"\'');

            // Lưu vào biến môi trường và siêu toàn cục $_ENV
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
    return true;
}

// Nạp file .env
$envLoaded = loadEnv(ROOT_PATH . '/.env');

// Thiết lập các hằng số với giá trị mặc định an toàn nếu chưa có trong .env
define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('APP_NAME', getenv('APP_NAME') ?: 'Điện máy XANH');
define('APP_KEY', getenv('APP_KEY') ?: 'secret_key_dmx_2026');

// Tự động xác định BASE_URL linh hoạt theo cách chạy (Laragon, XAMPP, php -S hoặc CLI)
if (php_sapi_name() === 'cli' || empty($_SERVER['HTTP_HOST'])) {
    define('APP_URL', rtrim(getenv('APP_URL') ?: 'http://localhost/Website%20TMDT', '/'));
} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $scriptDir = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])) : '';
    $scriptDir = ($scriptDir === '/' || $scriptDir === '\\' || $scriptDir === '.') ? '' : $scriptDir;
    $autoAppUrl = rtrim($protocol . $host . $scriptDir, '/');

    $envAppUrl = getenv('APP_URL');
    if (!empty($envAppUrl) && strpos($envAppUrl, $host) !== false) {
        define('APP_URL', rtrim($envAppUrl, '/'));
    } else {
        define('APP_URL', !empty($autoAppUrl) ? $autoAppUrl : 'http://localhost');
    }
}
define('ASSETS_URL', APP_URL . '/assets');
define('UPLOADS_URL', APP_URL . '/assets/uploads');

// Cấu hình Database
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'techstore_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Báo lỗi theo môi trường
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Nạp các hàm tiện ích chung
require_once INCLUDES_PATH . '/functions.php';
require_once INCLUDES_PATH . '/auth_middleware.php';
