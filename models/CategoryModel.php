<?php
/**
 * =======================================================================
 * LỚP MODEL QUẢN LÝ DANH MỤC (CATEGORYMODEL.PHP)
 * =======================================================================
 */

require_once ROOT_PATH . '/models/Database.php';

class CategoryModel {
    /**
     * Lấy danh sách tất cả danh mục đang hoạt động
     */
    public static function getAllActive(): array {
        $sql = "SELECT * FROM categories WHERE status = 1 ORDER BY sort_order ASC, id ASC";
        return Database::fetchAll($sql);
    }

    /**
     * Lấy toàn bộ danh mục cho trang Admin
     */
    public static function getAll(): array {
        $sql = "SELECT c.*, COUNT(p.id) AS product_count 
                FROM categories c 
                LEFT JOIN products p ON c.id = p.category_id 
                GROUP BY c.id 
                ORDER BY c.sort_order ASC, c.id DESC";
        return Database::fetchAll($sql);
    }

    /**
     * Tìm danh mục theo ID
     */
    public static function findById(int $id): ?array {
        $sql = "SELECT * FROM categories WHERE id = :id LIMIT 1";
        return Database::fetchOne($sql, ['id' => $id]);
    }

    /**
     * Tìm danh mục theo Slug
     */
    public static function findBySlug(string $slug): ?array {
        $sql = "SELECT * FROM categories WHERE slug = :slug AND status = 1 LIMIT 1";
        return Database::fetchOne($sql, ['slug' => $slug]);
    }

    /**
     * Thêm mới danh mục
     */
    public static function create(array $data): int {
        $sql = "INSERT INTO categories (name, slug, icon, badge, description, sort_order, status) 
                VALUES (:name, :slug, :icon, :badge, :description, :sort_order, :status)";
        Database::execute($sql, [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'icon' => $data['icon'] ?? null,
            'badge' => $data['badge'] ?? null,
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'status' => $data['status'] ?? 1
        ]);
        return (int)Database::lastInsertId();
    }

    /**
     * Cập nhật danh mục
     */
    public static function update(int $id, array $data): bool {
        $sql = "UPDATE categories 
                SET name = :name, slug = :slug, icon = :icon, badge = :badge, 
                    description = :description, sort_order = :sort_order, status = :status 
                WHERE id = :id";
        $params = [
            'id' => $id,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'icon' => $data['icon'] ?? null,
            'badge' => $data['badge'] ?? null,
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'status' => $data['status'] ?? 1
        ];
        return Database::execute($sql, $params) >= 0;
    }

    /**
     * Xóa danh mục
     */
    public static function delete(int $id): bool {
        $sql = "DELETE FROM categories WHERE id = :id";
        return Database::execute($sql, ['id' => $id]) > 0;
    }
}
