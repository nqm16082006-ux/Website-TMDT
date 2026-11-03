<?php
/**
 * =======================================================================
 * MIDDLEWARE XÁC THỰC QUYỀN TRUY CẬP (AUTH_MIDDLEWARE.PHP)
 * Chặn truy cập trái phép vào trang Quản trị (Admin) hoặc trang yêu cầu đăng nhập
 * =======================================================================
 */

/**
 * Yêu cầu người dùng phải đăng nhập mới được tiếp tục
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setFlashMessage('error', 'Vui lòng đăng nhập tài khoản để tiếp tục!');
        redirect(APP_URL . '/index.php?act=login');
    }
}

/**
 * Yêu cầu quyền Quản trị viên (Admin)
 * Nếu chưa đăng nhập hoặc không phải admin -> chuyển hướng về trang đăng nhập hoặc báo lỗi
 */
function requireAdmin() {
    if (!isLoggedIn()) {
        setFlashMessage('error', 'Bạn cần đăng nhập tài khoản Quản trị viên!');
        redirect(APP_URL . '/index.php?act=login&redirect=admin');
    }

    if (!isAdmin()) {
        // Người dùng thường cố ý truy cập URL admin
        http_response_code(403);
        echo '<!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <title>403 - Quyền truy cập bị từ chối</title>
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; background-color: #f4f6f8; }
                .box { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); text-align: center; max-width: 480px; }
                h1 { color: #dc3545; font-size: 48px; margin: 0 0 10px; }
                p { color: #555; line-height: 1.6; margin-bottom: 25px; }
                a { display: inline-block; background: #0071e3; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; }
            </style>
        </head>
        <body>
            <div class="box">
                <h1>403</h1>
                <h2>Từ Chối Truy Cập</h2>
                <p>Tài khoản của bạn không có đặc quyền Quản trị viên để truy cập trang này.</p>
                <a href="' . APP_URL . '">Quay về Trang chủ</a>
            </div>
        </body>
        </html>';
        exit();
    }
}

/**
 * Yêu cầu là khách (chưa đăng nhập) - dùng cho trang Login/Register
 */
function requireGuest() {
    if (isLoggedIn()) {
        redirect(APP_URL . '/index.php');
    }
}
