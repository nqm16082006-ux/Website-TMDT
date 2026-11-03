<?php
/**
 * =======================================================================
 * CONTROLLER TRANG CÁ NHÂN NGƯỜI DÙNG (USERCONTROLLER.PHP)
 * Phụ trách xem thông tin cá nhân, cập nhật mật khẩu, lịch sử đơn hàng
 * =======================================================================
 */

require_once ROOT_PATH . '/models/UserModel.php';
require_once ROOT_PATH . '/models/OrderModel.php';

class UserController {
    public function __construct() {
        requireLogin();
    }

    /**
     * Tổng quan trang cá nhân
     */
    public function dashboard() {
        $user = UserModel::findById($_SESSION['user']['id']);
        $orders = OrderModel::getOrdersByUser($user['id']);

        $pageTitle = "Tài khoản của tôi - Điện máy XANH";
        require_once VIEW_PATH . '/client/layouts/header.php';
        require_once VIEW_PATH . '/client/profile/dashboard.php';
        require_once VIEW_PATH . '/client/layouts/footer.php';
    }

    /**
     * Lịch sử đơn hàng
     */
    public function orders() {
        $orders = OrderModel::getOrdersByUser($_SESSION['user']['id']);

        $pageTitle = "Đơn hàng đã mua - Điện máy XANH";
        require_once VIEW_PATH . '/client/layouts/header.php';
        require_once VIEW_PATH . '/client/profile/orders.php';
        require_once VIEW_PATH . '/client/layouts/footer.php';
    }

    /**
     * Đổi mật khẩu & cài đặt thông tin
     */
    public function settings() {
        $user = UserModel::findById($_SESSION['user']['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = sanitizeInput($_POST['sub_act'] ?? '');

            if ($action === 'update_profile') {
                $fullname = sanitizeInput($_POST['fullname'] ?? '');
                $phone = sanitizeInput($_POST['phone'] ?? '');
                $address = sanitizeInput($_POST['address'] ?? '');

                UserModel::updateProfile($user['id'], [
                    'fullname' => $fullname,
                    'phone'    => $phone,
                    'address'  => $address
                ]);

                $_SESSION['user']['fullname'] = $fullname;
                $_SESSION['user']['phone'] = $phone;
                $_SESSION['user']['address'] = $address;

                setFlashMessage('success', 'Cập nhật thông tin thành công!');
                redirect(APP_URL . '/index.php?act=profile_settings');

            } elseif ($action === 'change_password') {
                $oldPassword = $_POST['old_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';

                if (!password_verify($oldPassword, $user['password'])) {
                    setFlashMessage('error', 'Mật khẩu hiện tại không chính xác!');
                } elseif ($newPassword !== $confirmPassword) {
                    setFlashMessage('error', 'Mật khẩu mới không trùng khớp!');
                } elseif (strlen($newPassword) < 6) {
                    setFlashMessage('error', 'Mật khẩu mới phải từ 6 ký tự trở lên!');
                } else {
                    UserModel::updatePassword($user['id'], $newPassword);
                    setFlashMessage('success', 'Đổi mật khẩu thành công!');
                    redirect(APP_URL . '/index.php?act=profile_settings');
                }
            }
        }

        $pageTitle = "Cài đặt tài khoản - Điện máy XANH";
        require_once VIEW_PATH . '/client/layouts/header.php';
        require_once VIEW_PATH . '/client/profile/settings.php';
        require_once VIEW_PATH . '/client/layouts/footer.php';
    }
}
