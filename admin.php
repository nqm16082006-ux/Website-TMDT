<?php
/**
 * =======================================================================
 * CỔNG VÀO TRANG QUẢN TRỊ ADMIN (ADMIN.PHP)
 * Routing trung tâm dành riêng cho Quản trị viên
 * =======================================================================
 */

// 1. Nạp file cấu hình hệ thống
require_once __DIR__ . '/config.php';

// 2. Yêu cầu kết nối CSDL SQL
require_once ROOT_PATH . '/models/Database.php';
$pdo = Database::getConnection();

// 3. Kiểm tra quyền Admin (Chặn ngay nếu không phải Admin)
requireAdmin();

// 4. Nạp các Controller Admin
require_once ROOT_PATH . '/controllers/admin/AdminController.php';
require_once ROOT_PATH . '/controllers/admin/ProductManageController.php';
require_once ROOT_PATH . '/controllers/admin/OrderManageController.php';
require_once ROOT_PATH . '/controllers/admin/UserManageController.php';
require_once ROOT_PATH . '/models/CategoryModel.php';

// 5. Lấy hành động (action) từ Query string
$action = isset($_GET['act']) ? trim($_GET['act']) : 'dashboard';

switch ($action) {
    // --- DASHBOARD TỔNG QUAN ---
    case 'dashboard':
        $ctrl = new AdminController();
        $ctrl->dashboard();
        break;

    // --- QUẢN LÝ SẢN PHẨM ---
    case 'products':
        $ctrl = new ProductManageController();
        $ctrl->list();
        break;

    case 'product_add':
        $ctrl = new ProductManageController();
        $ctrl->add();
        break;

    case 'product_edit':
        $ctrl = new ProductManageController();
        $ctrl->edit();
        break;

    case 'product_delete':
        $ctrl = new ProductManageController();
        $ctrl->delete();
        break;

    // --- QUẢN LÝ DANH MỤC ---
    case 'categories':
        $categories = CategoryModel::getAll();
        $pageTitle = "Quản lý danh mục - Admin";
        require_once VIEW_PATH . '/admin/layouts/header.php';
        require_once VIEW_PATH . '/admin/pages/categories/list.php';
        require_once VIEW_PATH . '/admin/layouts/footer.php';
        break;

    case 'category_add':
    case 'category_edit':
        $id = (int)($_GET['id'] ?? 0);
        $category = $id > 0 ? CategoryModel::findById($id) : null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitizeInput($_POST['name'] ?? '');
            $slug = sanitizeInput($_POST['slug'] ?? '') ?: slugify($name);
            $icon = sanitizeInput($_POST['icon'] ?? '');
            $badge = sanitizeInput($_POST['badge'] ?? '');
            $desc = sanitizeInput($_POST['description'] ?? '');
            $sort = (int)($_POST['sort_order'] ?? 0);

            if ($id > 0) {
                CategoryModel::update($id, [
                    'name' => $name, 'slug' => $slug, 'icon' => $icon,
                    'badge' => $badge, 'description' => $desc, 'sort_order' => $sort, 'status' => 1
                ]);
                setFlashMessage('success', 'Cập nhật danh mục thành công!');
            } else {
                CategoryModel::create([
                    'name' => $name, 'slug' => $slug, 'icon' => $icon,
                    'badge' => $badge, 'description' => $desc, 'sort_order' => $sort, 'status' => 1
                ]);
                setFlashMessage('success', 'Thêm danh mục mới thành công!');
            }
            redirect(APP_URL . '/admin.php?act=categories');
        }

        $pageTitle = ($id > 0 ? "Sửa" : "Thêm") . " danh mục - Admin";
        require_once VIEW_PATH . '/admin/layouts/header.php';
        require_once VIEW_PATH . '/admin/pages/categories/add-edit.php';
        require_once VIEW_PATH . '/admin/layouts/footer.php';
        break;

    case 'category_delete':
        $id = (int)($_GET['id'] ?? 0);
        CategoryModel::delete($id);
        setFlashMessage('success', 'Đã xóa danh mục thành công!');
        redirect(APP_URL . '/admin.php?act=categories');
        break;

    // --- QUẢN LÝ ĐƠN HÀNG ---
    case 'orders':
        $ctrl = new OrderManageController();
        $ctrl->list();
        break;

    case 'order_detail':
        $ctrl = new OrderManageController();
        $ctrl->detail();
        break;

    // --- QUẢN LÝ THÀNH VIÊN ---
    case 'users':
        $ctrl = new UserManageController();
        $ctrl->list();
        break;

    case 'user_toggle':
        $ctrl = new UserManageController();
        $ctrl->toggleStatus();
        break;

    default:
        redirect(APP_URL . '/admin.php?act=dashboard');
        break;
}
