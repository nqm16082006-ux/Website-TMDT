<?php
/**
 * =======================================================================
 * CONTROLLER QUẢN LÝ THÀNH VIÊN ADMIN (USERMANAGECONTROLLER.PHP)
 * Xem danh sách thành viên, khóa / mở khóa tài khoản
 * =======================================================================
 */

require_once ROOT_PATH . '/models/UserModel.php';

class UserManageController {
    public function __construct() {
        requireAdmin();
    }

    /**
     * Danh sách người dùng
     */
    public function list() {
        $users = UserModel::getAll();
        $pageTitle = "Quản lý thành viên - Admin";

        require_once VIEW_PATH . '/admin/layouts/header.php';
        require_once VIEW_PATH . '/admin/pages/users/list.php';
        require_once VIEW_PATH . '/admin/layouts/footer.php';
    }

    /**
     * Khóa hoặc mở khóa tài khoản
     */
    public function toggleStatus() {
        $id = (int)($_GET['id'] ?? 0);
        $status = (int)($_GET['status'] ?? 1);

        // Không cho phép khóa tài khoản chính mình
        if ($id === (int)$_SESSION['user']['id']) {
            setFlashMessage('error', 'Bạn không thể tự khóa tài khoản của chính mình!');
        } else {
            UserModel::toggleStatus($id, $status);
            $msg = ($status === 1) ? 'Đã mở khóa tài khoản thành công!' : 'Đã khóa tài khoản thành công!';
            setFlashMessage('success', $msg);
        }

        redirect(APP_URL . '/admin.php?act=users');
    }
}
