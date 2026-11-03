<?php
/**
 * =======================================================================
 * CONTROLLER QUẢN LÝ ĐƠN HÀNG ADMIN (ORDERMANAGECONTROLLER.PHP)
 * Xem danh sách đơn, chi tiết đơn, cập nhật trạng thái đơn & thanh toán
 * =======================================================================
 */

require_once ROOT_PATH . '/models/OrderModel.php';

class OrderManageController {
    public function __construct() {
        requireAdmin();
    }

    /**
     * Danh sách tất cả đơn hàng
     */
    public function list() {
        $orders = OrderModel::getAll();
        $pageTitle = "Quản lý đơn hàng - Admin";

        require_once VIEW_PATH . '/admin/layouts/header.php';
        require_once VIEW_PATH . '/admin/pages/orders/list.php';
        require_once VIEW_PATH . '/admin/layouts/footer.php';
    }

    /**
     * Chi tiết đơn hàng và duyệt đơn
     */
    public function detail() {
        $id = (int)($_GET['id'] ?? 0);
        $order = OrderModel::findById($id);

        if (!$order) {
            setFlashMessage('error', 'Không tìm thấy đơn hàng!');
            redirect(APP_URL . '/admin.php?act=orders');
        }

        $items = OrderModel::getOrderItems($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = sanitizeInput($_POST['status'] ?? 'pending');
            $paymentStatus = sanitizeInput($_POST['payment_status'] ?? 'pending');

            OrderModel::updateStatus($id, $status);
            OrderModel::updatePaymentStatus($id, $paymentStatus);

            setFlashMessage('success', "Đã cập nhật trạng thái đơn hàng #{$order['order_code']}!");
            redirect(APP_URL . '/admin.php?act=order_detail&id=' . $id);
        }

        $pageTitle = "Chi tiết đơn hàng #" . $order['order_code'] . " - Admin";
        require_once VIEW_PATH . '/admin/layouts/header.php';
        require_once VIEW_PATH . '/admin/pages/orders/detail.php';
        require_once VIEW_PATH . '/admin/layouts/footer.php';
    }
}
