<?php
/**
 * =======================================================================
 * CONTROLLER SẢN PHẨM (PRODUCTCONTROLLER.PHP)
 * Phụ trách danh sách sản phẩm, bộ lọc, tìm kiếm và chi tiết sản phẩm
 * =======================================================================
 */

require_once ROOT_PATH . '/models/ProductModel.php';
require_once ROOT_PATH . '/models/CategoryModel.php';

class ProductController {
    /**
     * Danh sách sản phẩm kèm bộ lọc, tìm kiếm và phân trang
     */
    public function index() {
        $categories = CategoryModel::getAllActive();

        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $filters = [
            'keyword'     => sanitizeInput($_GET['search'] ?? ''),
            'category_id' => isset($_GET['category']) ? (int)$_GET['category'] : null,
            'min_price'   => isset($_GET['min_price']) ? (float)$_GET['min_price'] : null,
            'max_price'   => isset($_GET['max_price']) ? (float)$_GET['max_price'] : null,
            'sort'        => sanitizeInput($_GET['sort'] ?? 'newest')
        ];

        $currentCategory = null;
        if (!empty($filters['category_id'])) {
            $currentCategory = CategoryModel::findById($filters['category_id']);
        }

        $totalProducts = ProductModel::countFiltered($filters);
        $totalPages = ceil($totalProducts / $limit);
        $products = ProductModel::filterProducts($filters, $limit, $offset);

        $pageTitle = $currentCategory ? $currentCategory['name'] . " - Điện máy XANH" : "Tất cả sản phẩm - Điện máy XANH";

        require_once VIEW_PATH . '/client/layouts/header.php';
        require_once VIEW_PATH . '/client/pages/products.php';
        require_once VIEW_PATH . '/client/layouts/footer.php';
    }

    /**
     * Chi tiết sản phẩm
     */
    public function detail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $product = ProductModel::findById($id);

        if (!$product) {
            setFlashMessage('error', 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh!');
            redirect(APP_URL . '/index.php?act=products');
        }

        $specs = !empty($product['specs']) ? json_decode($product['specs'], true) : [];
        $relatedProducts = ProductModel::getRelated($product['category_id'], $product['id'], 4);

        $pageTitle = $product['name'] . " - Điện máy XANH";

        require_once VIEW_PATH . '/client/layouts/header.php';
        require_once VIEW_PATH . '/client/pages/product-detail.php';
        require_once VIEW_PATH . '/client/layouts/footer.php';
    }
}
