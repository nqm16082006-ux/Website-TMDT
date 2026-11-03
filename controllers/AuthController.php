<?php
/**
 * =======================================================================
 * CONTROLLER XÁC THỰC (AUTHCONTROLLER.PHP)
 * Phụ trách Đăng nhập, Đăng ký và Đăng xuất
 * =======================================================================
 */

require_once ROOT_PATH . '/models/UserModel.php';

class AuthController {
    /**
     * Đăng nhập tài khoản
     */
    public function login() {
        if (isLoggedIn()) {
            redirect(APP_URL . '/index.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $redirect = sanitizeInput($_POST['redirect'] ?? '');

            if (empty($email) || empty($password)) {
                setFlashMessage('error', 'Vui lòng nhập đầy đủ Email và Mật khẩu!');
            } else {
                $user = UserModel::findByEmail($email);
                if ($user && password_verify($password, $user['password'])) {
                    if ((int)$user['status'] !== 1) {
                        setFlashMessage('error', 'Tài khoản của bạn đã bị khóa! Vui lòng liên hệ ban quản trị.');
                    } else {
                        // Lưu thông tin vào session
                        $_SESSION['user'] = [
                            'id'       => $user['id'],
                            'fullname' => $user['fullname'],
                            'email'    => $user['email'],
                            'phone'    => $user['phone'],
                            'address'  => $user['address'],
                            'avatar'   => $user['avatar'],
                            'role'     => $user['role']
                        ];

                        setFlashMessage('success', "Chào mừng " . htmlspecialchars($user['fullname']) . " đã đăng nhập thành công!");

                        if ($user['role'] === 'admin' && ($redirect === 'admin' || empty($redirect))) {
                            redirect(APP_URL . '/admin.php');
                        }
                        redirect(APP_URL . '/index.php');
                    }
                } else {
                    setFlashMessage('error', 'Email hoặc mật khẩu không chính xác!');
                }
            }
        }

        $pageTitle = "Đăng nhập tài khoản - Điện máy XANH";
        require_once VIEW_PATH . '/client/layouts/header.php';
        require_once VIEW_PATH . '/client/pages/login.php';
        require_once VIEW_PATH . '/client/layouts/footer.php';
    }

    /**
     * Đăng ký tài khoản mới
     */
    public function register() {
        if (isLoggedIn()) {
            redirect(APP_URL . '/index.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = sanitizeInput($_POST['fullname'] ?? '');
            $email = sanitizeInput($_POST['email'] ?? '');
            $phone = sanitizeInput($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $address = sanitizeInput($_POST['address'] ?? '');

            if (empty($fullname) || empty($email) || empty($password)) {
                setFlashMessage('error', 'Vui lòng điền đầy đủ thông tin bắt buộc (*)!');
            } elseif ($password !== $confirmPassword) {
                setFlashMessage('error', 'Mật khẩu xác nhận không khớp nhau!');
            } elseif (strlen($password) < 6) {
                setFlashMessage('error', 'Mật khẩu phải có ít nhất 6 ký tự!');
            } elseif (UserModel::findByEmail($email)) {
                setFlashMessage('error', 'Email này đã được sử dụng. Vui lòng chọn email khác!');
            } else {
                $userId = UserModel::create([
                    'fullname' => $fullname,
                    'email'    => $email,
                    'phone'    => $phone,
                    'password' => $password,
                    'address'  => $address,
                    'role'     => 'user',
                    'status'   => 1
                ]);

                if ($userId) {
                    setFlashMessage('success', 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.');
                    redirect(APP_URL . '/index.php?act=login');
                } else {
                    setFlashMessage('error', 'Có lỗi xảy ra trong quá trình đăng ký!');
                }
            }
        }

        $pageTitle = "Đăng ký tài khoản mới - Điện máy XANH";
        require_once VIEW_PATH . '/client/layouts/header.php';
        require_once VIEW_PATH . '/client/pages/register.php';
        require_once VIEW_PATH . '/client/layouts/footer.php';
    }

    /**
     * Đăng xuất
     */
    public function logout() {
        unset($_SESSION['user']);
        setFlashMessage('success', 'Đã đăng xuất tài khoản thành công.');
        redirect(APP_URL . '/index.php');
    }
}
