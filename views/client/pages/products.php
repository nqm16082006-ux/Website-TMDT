<?php
/**
 * =======================================================================
 * TRANG DANH SÁCH SẢN PHẨM (PRODUCTS.PHP)
 * Lọc theo danh mục, khoảng giá, sắp xếp giá/bán chạy và phân trang
 * =======================================================================
 */
?>

<div class="container my-4">
    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <a href="<?= APP_URL ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
        <i class="fa-solid fa-chevron-right separator"></i>
        <span><?= htmlspecialchars($currentCategory['name'] ?? 'Tất cả sản phẩm') ?></span>
    </div>

    <div class="product-page-layout">
        <!-- Cột phụ Sidebar lọc sản phẩm -->
        <?php require_once VIEW_PATH . '/client/layouts/sidebar.php'; ?>

        <!-- Khu vực sản phẩm chính -->
        <div class="product-main-content">
            <!-- Thanh tiêu đề & sắp xếp -->
            <div class="products-toolbar">
                <div class="toolbar-title">
                    <h2><?= htmlspecialchars($currentCategory['name'] ?? 'Tất cả sản phẩm') ?></h2>
                    <span class="product-total-badge">(<?= $totalProducts ?> sản phẩm)</span>
                </div>

                <div class="toolbar-sorting">
                    <span>Sắp xếp theo:</span>
                    <a href="<?= APP_URL ?>/index.php?act=products<?= !empty($filters['category_id']) ? '&category='.$filters['category_id'] : '' ?>&sort=newest" 
                       class="sort-btn <?= ($filters['sort'] === 'newest') ? 'active' : '' ?>">Mới nhất</a>
                    <a href="<?= APP_URL ?>/index.php?act=products<?= !empty($filters['category_id']) ? '&category='.$filters['category_id'] : '' ?>&sort=best_seller" 
                       class="sort-btn <?= ($filters['sort'] === 'best_seller') ? 'active' : '' ?>">Bán chạy</a>
                    <a href="<?= APP_URL ?>/index.php?act=products<?= !empty($filters['category_id']) ? '&category='.$filters['category_id'] : '' ?>&sort=price_asc" 
                       class="sort-btn <?= ($filters['sort'] === 'price_asc') ? 'active' : '' ?>">Giá tăng dần</a>
                    <a href="<?= APP_URL ?>/index.php?act=products<?= !empty($filters['category_id']) ? '&category='.$filters['category_id'] : '' ?>&sort=price_desc" 
                       class="sort-btn <?= ($filters['sort'] === 'price_desc') ? 'active' : '' ?>">Giá giảm dần</a>
                </div>
            </div>

            <!-- Lưới sản phẩm -->
            <?php if (!empty($products)): ?>
                <div class="products-grid">
                    <?php foreach ($products as $prod): ?>
                        <div class="product-card">
                            <div class="prod-img-box">
                                <a href="<?= APP_URL ?>/index.php?act=product-detail&id=<?= $prod['id'] ?>">
                                    <div class="prod-placeholder-img">
                                        <i class="fa-solid fa-microchip"></i>
                                    </div>
                                </a>
                            </div>
                            <h3 class="prod-name">
                                <a href="<?= APP_URL ?>/index.php?act=product-detail&id=<?= $prod['id'] ?>">
                                    <?= htmlspecialchars($prod['name']) ?>
                                </a>
                            </h3>
                            <div class="prod-price-box">
                                <span class="sale-price"><?= formatCurrency($prod['sale_price'] ?? $prod['price']) ?></span>
                                <?php if ($prod['sale_price'] && $prod['sale_price'] < $prod['price']): ?>
                                    <div class="old-price-row">
                                        <span class="old-price"><?= formatCurrency($prod['price']) ?></span>
                                        <span class="discount-percent">-<?= $prod['discount_percent'] ?>%</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="prod-rating-row">
                                <span class="star-rating"><i class="fa-solid fa-star"></i> <?= $prod['rating'] ?></span>
                                <span class="sold-count">• Đã bán <?= $prod['sold_count'] ?></span>
                            </div>
                            <div class="prod-action-box">
                                <a href="<?= APP_URL ?>/index.php?act=cart_add&id=<?= $prod['id'] ?>" class="btn-buy-flash">
                                    <i class="fa-solid fa-cart-plus"></i> Chọn mua
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Phân trang -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination-bar">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="<?= APP_URL ?>/index.php?act=products&page=<?= $i ?><?= !empty($filters['category_id']) ? '&category='.$filters['category_id'] : '' ?><?= !empty($filters['sort']) ? '&sort='.$filters['sort'] : '' ?>" 
                               class="page-link <?= $page == $i ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="empty-state-card">
                    <i class="fa-solid fa-box-open empty-icon"></i>
                    <h3>Không tìm thấy sản phẩm nào!</h3>
                    <p>Hãy thử tìm kiếm với từ khóa khác hoặc bỏ các bộ lọc đang chọn.</p>
                    <a href="<?= APP_URL ?>/index.php?act=products" class="btn-reset-filter">Xem tất cả sản phẩm</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
