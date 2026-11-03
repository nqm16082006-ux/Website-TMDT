<?php
/**
 * =======================================================================
 * LỚP MODEL QUẢN LÝ SẢN PHẨM (PRODUCTMODEL.PHP)
 * =======================================================================
 */

require_once ROOT_PATH . '/models/Database.php';

class ProductModel {
    /**
     * Lấy danh sách sản phẩm Flash Sale (theo giao diện ảnh 2)
     */
    public static function getFlashSaleProducts(int $limit = 6): array {
        $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 1 AND p.flash_sale = 1 
                ORDER BY p.id ASC 
                LIMIT :limit";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy danh sách sản phẩm nổi bật
     */
    public static function getFeaturedProducts(int $limit = 8): array {
        $sql = "SELECT p.*, c.name AS category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 1 AND p.is_featured = 1 
                ORDER BY p.id DESC 
                LIMIT :limit";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy sản phẩm theo ID
     */
    public static function findById(int $id): ?array {
        $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.id = :id LIMIT 1";
        return Database::fetchOne($sql, ['id' => $id]);
    }

    /**
     * Lấy sản phẩm theo Slug
     */
    public static function findBySlug(string $slug): ?array {
        $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.slug = :slug AND p.status = 1 LIMIT 1";
        return Database::fetchOne($sql, ['slug' => $slug]);
    }

    /**
     * Lấy sản phẩm liên quan
     */
    public static function getRelated(int $categoryId, int $excludeId, int $limit = 4): array {
        $sql = "SELECT * FROM products 
                WHERE category_id = :cat_id AND id != :exc_id AND status = 1 
                ORDER BY RAND() LIMIT :limit";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':exc_id', $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Tìm kiếm và lọc sản phẩm có phân trang
     */
    public static function filterProducts(array $filters = [], int $limit = 12, int $offset = 0): array {
        $sql = "SELECT p.*, c.name AS category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 1";
        $params = [];

        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = :cat_id";
            $params['cat_id'] = $filters['category_id'];
        }

        if (!empty($filters['keyword'])) {
            $sql .= " AND (p.name LIKE :kw OR p.short_description LIKE :kw2)";
            $params['kw'] = '%' . $filters['keyword'] . '%';
            $params['kw2'] = '%' . $filters['keyword'] . '%';
        }

        if (!empty($filters['min_price'])) {
            $sql .= " AND COALESCE(p.sale_price, p.price) >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND COALESCE(p.sale_price, p.price) <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }

        // Sắp xếp
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY COALESCE(p.sale_price, p.price) ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY COALESCE(p.sale_price, p.price) DESC";
                break;
            case 'best_seller':
                $sql .= " ORDER BY p.sold_count DESC";
                break;
            default:
                $sql .= " ORDER BY p.id DESC";
                break;
        }

        $sql .= " LIMIT :offset, :limit";

        $stmt = Database::getConnection()->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue(':' . $key, $val);
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Đếm tổng số lượng sản phẩm phù hợp bộ lọc
     */
    public static function countFiltered(array $filters = []): int {
        $sql = "SELECT COUNT(*) as total FROM products p WHERE p.status = 1";
        $params = [];

        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = :cat_id";
            $params['cat_id'] = $filters['category_id'];
        }

        if (!empty($filters['keyword'])) {
            $sql .= " AND (p.name LIKE :kw OR p.short_description LIKE :kw2)";
            $params['kw'] = '%' . $filters['keyword'] . '%';
            $params['kw2'] = '%' . $filters['keyword'] . '%';
        }

        if (!empty($filters['min_price'])) {
            $sql .= " AND COALESCE(p.sale_price, p.price) >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND COALESCE(p.sale_price, p.price) <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }

        $result = Database::fetchOne($sql, $params);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Lấy toàn bộ sản phẩm cho Admin quản trị
     */
    public static function getAllForAdmin(): array {
        $sql = "SELECT p.*, c.name AS category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                ORDER BY p.id DESC";
        return Database::fetchAll($sql);
    }

    /**
     * Thêm mới sản phẩm
     */
    public static function create(array $data): int {
        $sql = "INSERT INTO products (
                    category_id, name, slug, sku, image, price, sale_price, 
                    discount_percent, quantity, flash_sale, flash_sale_quota, 
                    flash_sale_sold, specs, gift_info, short_description, 
                    description, is_featured, status
                ) VALUES (
                    :category_id, :name, :slug, :sku, :image, :price, :sale_price, 
                    :discount_percent, :quantity, :flash_sale, :flash_sale_quota, 
                    :flash_sale_sold, :specs, :gift_info, :short_description, 
                    :description, :is_featured, :status
                )";
        Database::execute($sql, [
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'sku' => $data['sku'] ?? null,
            'image' => $data['image'],
            'price' => $data['price'],
            'sale_price' => $data['sale_price'] ?? null,
            'discount_percent' => $data['discount_percent'] ?? 0,
            'quantity' => $data['quantity'] ?? 100,
            'flash_sale' => $data['flash_sale'] ?? 0,
            'flash_sale_quota' => $data['flash_sale_quota'] ?? 10,
            'flash_sale_sold' => $data['flash_sale_sold'] ?? 0,
            'specs' => $data['specs'] ?? null,
            'gift_info' => $data['gift_info'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'is_featured' => $data['is_featured'] ?? 0,
            'status' => $data['status'] ?? 1
        ]);
        return (int)Database::lastInsertId();
    }

    /**
     * Cập nhật sản phẩm
     */
    public static function update(int $id, array $data): bool {
        $sql = "UPDATE products SET 
                    category_id = :category_id, name = :name, slug = :slug, sku = :sku,
                    image = :image, price = :price, sale_price = :sale_price,
                    discount_percent = :discount_percent, quantity = :quantity,
                    flash_sale = :flash_sale, flash_sale_quota = :flash_sale_quota,
                    flash_sale_sold = :flash_sale_sold, specs = :specs, gift_info = :gift_info,
                    short_description = :short_description, description = :description,
                    is_featured = :is_featured, status = :status
                WHERE id = :id";
        $data['id'] = $id;
        return Database::execute($sql, $data) >= 0;
    }

    /**
     * Xóa sản phẩm
     */
    public static function delete(int $id): bool {
        $sql = "DELETE FROM products WHERE id = :id";
        return Database::execute($sql, ['id' => $id]) > 0;
    }
}
