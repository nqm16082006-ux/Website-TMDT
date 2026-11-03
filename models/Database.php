<?php
/**
 * =======================================================================
 * LỚP KẾT NỐI CƠ SỞ DỮ LIỆU PDO (DATABASE.PHP)
 * Áp dụng mẫu thiết kế Singleton (Singleton Pattern)
 * Hỗ trợ kiểm tra kết nối SQL và giao diện thông báo yêu cầu kết nối SQL
 * =======================================================================
 */

class Database {
    private static ?PDO $instance = null;
    private static bool $connectionChecked = false;
    private static ?string $lastError = null;

    /**
     * Hàm khởi tạo private để ngăn việc tạo đối tượng trực tiếp bên ngoài
     */
    private function __construct() {}

    /**
     * Lấy kết nối PDO Singleton
     * Nếu không kết nối được SQL -> dừng thực thi và hiển thị màn hình yêu cầu kết nối SQL
     * @return PDO
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $host = defined('DB_HOST') ? DB_HOST : '127.0.0.1';
            $port = defined('DB_PORT') ? DB_PORT : '3306';
            $dbname = defined('DB_NAME') ? DB_NAME : 'techstore_db';
            $user = defined('DB_USER') ? DB_USER : 'root';
            $pass = defined('DB_PASS') ? DB_PASS : '';
            $charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';

            // Xử lý yêu cầu tự động khởi tạo database nếu có query act=setup_db
            if (isset($_GET['act']) && $_GET['act'] === 'setup_db') {
                self::handleAutoSetup($host, $port, $dbname, $user, $pass);
            }

            try {
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset}"
                ];

                self::$instance = new PDO($dsn, $user, $pass, $options);
                self::$connectionChecked = true;

                // Kiểm tra xem bảng products có tồn tại không
                $checkTable = self::$instance->query("SHOW TABLES LIKE 'products'")->fetch();
                if (!$checkTable) {
                    self::renderConnectionRequiredScreen(
                        "Đã kết nối thành công tới MySQL, nhưng Cơ sở dữ liệu '{$dbname}' chưa có bảng dữ liệu nào. Vui lòng bấm 'Khởi tạo Cơ sở dữ liệu' để nạp bảng và dữ liệu mẫu.",
                        $host, $port, $dbname, $user, true
                    );
                }

            } catch (PDOException $e) {
                self::$lastError = $e->getMessage();
                self::renderConnectionRequiredScreen($e->getMessage(), $host, $port, $dbname, $user, false);
            }
        }

        return self::$instance;
    }

    /**
     * Kiểm tra nhanh trạng thái kết nối SQL (trả về true/false)
     */
    public static function isConnected(): bool {
        if (self::$instance !== null) {
            return true;
        }
        try {
            $host = defined('DB_HOST') ? DB_HOST : '127.0.0.1';
            $port = defined('DB_PORT') ? DB_PORT : '3306';
            $dbname = defined('DB_NAME') ? DB_NAME : 'techstore_db';
            $user = defined('DB_USER') ? DB_USER : 'root';
            $pass = defined('DB_PASS') ? DB_PASS : '';
            $charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
            $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Tự động khởi tạo Database và nạp database.sql
     */
    private static function handleAutoSetup($host, $port, $dbname, $user, $pass) {
        try {
            // Kết nối MySQL server không chỉ định dbname
            $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            // Tạo database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $pdo->exec("USE `{$dbname}`;");

            // Nạp file database.sql
            $sqlFile = ROOT_PATH . '/database.sql';
            if (file_exists($sqlFile)) {
                $sqlContent = file_get_contents($sqlFile);
                $pdo->exec($sqlContent);
            }

            // Chuyển hướng về trang chủ
            header("Location: " . APP_URL . "/index.php");
            exit();
        } catch (PDOException $e) {
            self::renderConnectionRequiredScreen("Lỗi khi tự động cài đặt CSDL: " . $e->getMessage(), $host, $port, $dbname, $user, false);
        }
    }

    /**
     * Hiển thị giao diện thông báo YÊU CẦU KẾT NỐI CƠ SỞ DỮ LIỆU SQL
     */
    public static function renderConnectionRequiredScreen($errorMessage, $host, $port, $dbname, $user, $hasDatabaseOnly = false) {
        $setupUrl = APP_URL . "/index.php?act=setup_db";
        $retryUrl = APP_URL . "/index.php";

        echo '<!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Yêu Cầu Kết Nối SQL - Điện Máy XANH</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                    background: linear-gradient(135deg, #004882 0%, #0071e3 50%, #00a4ff 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                    color: #333;
                }
                .sql-card {
                    background: #ffffff;
                    border-radius: 16px;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
                    max-width: 650px;
                    width: 100%;
                    overflow: hidden;
                    animation: fadeIn 0.4s ease-out;
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .sql-header {
                    background: #0071e3;
                    color: white;
                    padding: 24px;
                    text-align: center;
                    position: relative;
                }
                .sql-header .brand-pill {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    background: #ffd400;
                    color: #004882;
                    font-weight: 800;
                    font-size: 14px;
                    padding: 6px 16px;
                    border-radius: 30px;
                    margin-bottom: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                .sql-header h1 {
                    font-size: 22px;
                    font-weight: 700;
                    margin-bottom: 6px;
                }
                .sql-body {
                    padding: 28px;
                }
                .alert-box {
                    background: #fff3cd;
                    border-left: 5px solid #ffc107;
                    padding: 14px 16px;
                    border-radius: 6px;
                    margin-bottom: 20px;
                    font-size: 14px;
                    color: #856404;
                    line-height: 1.5;
                }
                .alert-box.danger {
                    background: #f8d7da;
                    border-left-color: #dc3545;
                    color: #721c24;
                }
                .config-grid {
                    background: #f8f9fa;
                    border: 1px solid #e9ecef;
                    border-radius: 10px;
                    padding: 16px;
                    margin-bottom: 20px;
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 12px;
                    font-size: 13px;
                }
                .config-item strong {
                    color: #495057;
                    display: block;
                    font-size: 12px;
                    text-transform: uppercase;
                    margin-bottom: 2px;
                }
                .config-item span {
                    font-family: monospace;
                    color: #0071e3;
                    font-size: 14px;
                    font-weight: 600;
                }
                .steps-guide {
                    background: #f0f7ff;
                    border: 1px dashed #b8daff;
                    border-radius: 10px;
                    padding: 16px;
                    margin-bottom: 25px;
                }
                .steps-guide h3 {
                    font-size: 14px;
                    color: #0056b3;
                    margin-bottom: 10px;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                }
                .steps-guide ol {
                    margin-left: 20px;
                    font-size: 13px;
                    line-height: 1.6;
                    color: #444;
                }
                .btn-group {
                    display: flex;
                    gap: 12px;
                }
                .btn {
                    flex: 1;
                    padding: 14px;
                    border-radius: 8px;
                    font-size: 15px;
                    font-weight: 600;
                    text-align: center;
                    text-decoration: none;
                    cursor: pointer;
                    border: none;
                    transition: all 0.2s;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                }
                .btn-primary {
                    background: #0071e3;
                    color: #ffffff;
                }
                .btn-primary:hover {
                    background: #005bb5;
                    box-shadow: 0 4px 12px rgba(0,113,227,0.35);
                }
                .btn-success {
                    background: #ffd400;
                    color: #004882;
                }
                .btn-success:hover {
                    background: #e6be00;
                    box-shadow: 0 4px 12px rgba(255,212,0,0.4);
                }
            </style>
        </head>
        <body>
            <div class="sql-card">
                <div class="sql-header">
                    <div class="brand-pill">
                        <i class="fa-solid fa-bolt"></i> Điện máy XANH
                    </div>
                    <h1><i class="fa-solid fa-database"></i> YÊU CẦU KẾT NỐI CƠ SỞ DỮ LIỆU SQL</h1>
                    <p style="opacity: 0.9; font-size: 14px;">Hệ thống cần kết nối MySQL để hiển thị toàn bộ website.</p>
                </div>
                <div class="sql-body">
                    <div class="alert-box ' . ($hasDatabaseOnly ? '' : 'danger') . '">
                        <strong><i class="fa-solid fa-triangle-exclamation"></i> Trạng thái:</strong> ' . htmlspecialchars($errorMessage, ENT_QUOTES) . '
                    </div>

                    <div class="config-grid">
                        <div class="config-item">
                            <strong>Máy chủ (DB_HOST):</strong>
                            <span>' . htmlspecialchars($host . ':' . $port, ENT_QUOTES) . '</span>
                        </div>
                        <div class="config-item">
                            <strong>Tên Database (DB_NAME):</strong>
                            <span>' . htmlspecialchars($dbname, ENT_QUOTES) . '</span>
                        </div>
                        <div class="config-item">
                            <strong>Người dùng (DB_USER):</strong>
                            <span>' . htmlspecialchars($user, ENT_QUOTES) . '</span>
                        </div>
                        <div class="config-item">
                            <strong>File Dữ Liệu:</strong>
                            <span>database.sql (Có sẵn)</span>
                        </div>
                    </div>

                    <div class="steps-guide">
                        <h3><i class="fa-solid fa-list-check"></i> Các bước kết nối nhanh:</h3>
                        <ol>
                            <li>Đảm bảo dịch vụ <strong>MySQL</strong> đang chạy (ví dụ trong <em>XAMPP</em>, <em>Laragon</em> hoặc <em>Docker</em>).</li>
                            <li>Nếu chưa tạo CSDL, bạn có thể click nút <strong>"Tự động tạo CSDL & Dữ liệu mẫu"</strong> bên dưới.</li>
                            <li>Hoặc mở <code>phpMyAdmin</code>, tạo database <code>' . htmlspecialchars($dbname, ENT_QUOTES) . '</code> và import file <code>database.sql</code>.</li>
                            <li>Kiểm tra cấu hình trong file <code>.env</code> nếu thông tin kết nối khác mặc định.</li>
                        </ol>
                    </div>

                    <div class="btn-group">
                        <a href="' . $setupUrl . '" class="btn btn-success">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> Tự Động Khởi Tạo CSDL
                        </a>
                        <a href="' . $retryUrl . '" class="btn btn-primary">
                            <i class="fa-solid fa-arrows-rotate"></i> Thử Lại Kết Nối
                        </a>
                    </div>
                </div>
            </div>
        </body>
        </html>';
        exit();
    }

    /**
     * Thực thi câu lệnh SQL và trả về số dòng bị ảnh hưởng
     */
    public static function execute(string $sql, array $params = []): int {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Lấy 1 bản ghi duy nhất
     */
    public static function fetchOne(string $sql, array $params = []): ?array {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Lấy tất cả bản ghi
     */
    public static function fetchAll(string $sql, array $params = []): array {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Lấy ID vừa chèn vào bảng
     */
    public static function lastInsertId(): string {
        return self::getConnection()->lastInsertId();
    }
}
