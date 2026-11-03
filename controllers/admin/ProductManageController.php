<?php
/**
 * =======================================================================
 * CONTROLLER QUẢN LÝ SẢN PHẨM ADMIN (PRODUCTMANAGECONTROLLER.PHP)
 * CRUD: Thêm, sửa, xóa, liệt kê sản phẩm
 * =======================================================================
 */

require_once ROOT_PATH . '/models/ProductModel.php';
require_once ROOT_PATH . '/models/CategoryModel.php';

class ProductManageController {
    public function __construct() {
        requireAdmin();
    }

    /**
     * Danh sách sản phẩm
     */
    public function list() {
        $products = ProductModel::getAllForAdmin();
        $pageTitle = "Quản lý sản phẩm - Admin";

        require_once VIEW_PATH . '/admin/layouts/header.php';
        require_once VIEW_PATH . '/admin/pages/products/list.php';
        require_once VIEW_PATH . '/admin/layouts/footer.php';
    }

    /**
     * Thêm sản phẩm mới
     */
    public function add() {
        $categories = CategoryModel::getAllActive();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitizeInput($_POST['name'] ?? '');
            $categoryId = (int)($_POST['category_id'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            $salePrice = !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null;
            $quantity = (int)($_POST['quantity'] ?? 10);
            $shortDesc = sanitizeInput($_POST['short_description'] ?? '');
            $desc = $_POST['description'] ?? '';
            $giftInfo = sanitizeInput($_POST['gift_info'] ?? '');
            $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
            $flashSale = isset($_POST['flash_sale']) ? 1 : 0;

            if (empty($name) || $categoryId <= 0 || $price <= 0) {
                setFlashMessage('error', 'Vui lòng nhập đầy đủ tên, danh mục và giá sản phẩm!');
            } else {
                $slug = slugify($name) . '-' . rand(100, 999);
                $discount = ($salePrice && $salePrice < $price) ? calculateDiscountPercent($price, $salePrice) : 0;

                $data = [
                    'category_id'      => $categoryId,
                    'name'             => $name,
                    'slug'             => $slug,
                    'sku'              => 'SP' . rand(1000, 9999),
                    'image'            => 'product-default.png',
                    'price'            => $price,
                    'sale_price'       => $salePrice,
                    'discount_percent' => $discount,
                    'quantity'         => $quantity,
                    'flash_sale'       => $flashSale,
                    'flash_sale_quota' => 10,
                    'flash_sale_sold'  => 0,
                    'specs'            => json_encode(['thong_tin' => 'Chính hãng 100%']),
                    'gift_info'        => $giftInfo,
                    'short_description'=> $shortDesc,
                    'description'      => $desc,
                    'is_featured'      => $isFeatured,
                    'status'           => 1
                ];

                ProductModel::create($data);
                setFlashMessage('success', 'Thêm mới sản phẩm thành công!');
                redirect(APP_URL . '/admin.php?act=products');
            }
        }

        $pageTitle = "Thêm sản phẩm mới - Admin";
        require_once VIEW_PATH . '/admin/layouts/header.php';
        require_once VIEW_PATH . '/admin/pages/products/add.php';
        require_once VIEW_PATH . '/admin/layouts/footer.php';
    }

    /**
     * Sửa sản phẩm
     */
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        $product = ProductModel::findById($id);
        if (!$product) {
            setFlashMessage('error', 'Không tìm thấy sản phẩm!');
            redirect(APP_URL . '/admin.php?act=products');
        }

        $categories = CategoryModel::getAllActive();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitizeInput($_POST['name'] ?? '');
            $categoryId = (int)($_POST['category_id'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            $salePrice = !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null;
            $quantity = (int)($_POST['quantity'] ?? 10);
            $shortDesc = sanitizeInput($_POST['short_description'] ?? '');
            $desc = $_POST['description'] ?? '';
            $giftInfo = sanitizeInput($_POST['gift_info'] ?? '');
            $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
            $flashSale = isset($_POST['flash_sale']) ? 1 : 0;

            $discount = ($salePrice && $salePrice < $price) ? calculateDiscountPercent($price, $salePrice) : 0;

            $updateData = [
                'category_id'      => $categoryId,
                'name'             => $name,
                'slug'             => $product['slug'],
                'sku'              => $product['sku'],
                'image'            => $product['image'],
                'price'            => $price,
                'sale_price'       => $salePrice,
                'discount_percent' => $discount,
                'quantity'         => $quantity,
                'flash_sale'       => $flashSale,
                'flash_sale_quota' => $product['flash_sale_quota'] ?? 10,
                'flash_sale_sold'  => $product['flash_sale_sold'] ?? 0,
                'specs'            => $product['specs'],
                'gift_info'        => $giftInfo,
                'short_description'=> $shortDesc,
                'description'      => $desc,
                'is_featured'      => $isFeatured,
                'status'           => 1
            ];

            ProductModel::update($id, $updateData);
            setFlashMessage('success', 'Cập nhật sản phẩm thành công!');
            redirect(APP_URL . '/admin.php?act=products');
        }

        $pageTitle = "Chỉnh sửa sản phẩm - Admin";
        require_once VIEW_PATH . '/admin/layouts/header.php';
        require_once VIEW_PATH . '/admin/pages/products/edit.php';
        require_once VIEW_PATH . '/admin/layouts/footer.php';
    }

    /**
     * Xóa sản phẩm
     */
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        ProductModel::delete($id);
        setFlashMessage('success', 'Đã xóa sản phẩm thành công!');
        redirect(APP_URL . '/admin.php?act=products');
    }
}
