<?php
/**
 * =======================================================================
 * CONTROLLER TỔNG QUAN ADMIN (ADMINCONTROLLER.PHP)
 * Phụ trách thống kê doanh thu, số lượng đơn hàng, biểu đồ tổng quan
 * =======================================================================
 */

require_once ROOT_PATH . '/models/OrderModel.php';
require_once ROOT_PATH . '/models/ProductModel.php';
require_once ROOT_PATH . '/models/UserModel.php';

class AdminController {
    public function __construct() {
        requireAdmin();
    }

    /**
     * Trang Dashboard quản trị tổng quan
     */
    public function dashboard() {
        $stats = OrderModel::getDashboardStats();
        $pageTitle = "Bảng điều khiển quản trị - Điện máy XANH";

        require_once VIEW_PATH . '/admin/layouts/header.php';
        require_once VIEW_PATH . '/admin/pages/dashboard.php';
        require_once VIEW_PATH . '/admin/layouts/footer.php';
    }
}
