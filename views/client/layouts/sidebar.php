<?php
/**
 * =======================================================================
 * LAYOUT SIDEBAR PHÍA KHÁCH HÀNG (SIDEBAR.PHP)
 * Bộ lọc danh mục, mức giá và thương hiệu cho trang sản phẩm
 * =======================================================================
 */
?>
<aside class="product-sidebar">
    <!-- 1. Danh mục sản phẩm -->
    <div class="sidebar-block">
        <h3 class="sidebar-title"><i class="fa-solid fa-list"></i> Danh Mục Hàng</h3>
        <ul class="sidebar-category-list">
            <li class="<?= empty($filters['category_id']) ? 'active' : '' ?>">
                <a href="<?= APP_URL ?>/index.php?act=products">Tất cả sản phẩm</a>
            </li>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <li class="<?= (isset($filters['category_id']) && $filters['category_id'] == $cat['id']) ? 'active' : '' ?>">
                        <a href="<?= APP_URL ?>/index.php?act=products&category=<?= $cat['id'] ?>">
                            <i class="<?= $cat['icon'] ?: 'fa-solid fa-angle-right' ?>"></i>
                            <?= htmlspecialchars($cat['name']) ?>
                            <?php if ($cat['badge']): ?>
                                <span class="cat-pill"><?= htmlspecialchars($cat['badge']) ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <!-- 2. Lọc theo khoảng giá -->
    <div class="sidebar-block">
        <h3 class="sidebar-title"><i class="fa-solid fa-filter-circle-dollar"></i> Chọn Mức Giá</h3>
        <ul class="price-filter-list">
            <li><a href="<?= APP_URL ?>/index.php?act=products&max_price=1000000">Dưới 1 triệu</a></li>
            <li><a href="<?= APP_URL ?>/index.php?act=products&min_price=1000000&max_price=3000000">Từ 1 - 3 triệu</a></li>
            <li><a href="<?= APP_URL ?>/index.php?act=products&min_price=3000000&max_price=7000000">Từ 3 - 7 triệu</a></li>
            <li><a href="<?= APP_URL ?>/index.php?act=products&min_price=7000000&max_price=15000000">Từ 7 - 15 triệu</a></li>
            <li><a href="<?= APP_URL ?>/index.php?act=products&min_price=15000000">Trên 15 triệu</a></li>
        </ul>
    </div>

    <!-- 3. Cam kết dịch vụ -->
    <div class="sidebar-block commitment-box">
        <h4><i class="fa-solid fa-shield-halved"></i> Cam kết tại Điện máy XANH</h4>
        <ul>
            <li><i class="fa-solid fa-truck-fast"></i> Giao hàng tận nơi miễn phí</li>
            <li><i class="fa-solid fa-arrow-rotate-left"></i> Hư gì đổi nấy 12 tháng tận nhà</li>
            <li><i class="fa-solid fa-screwdriver-wrench"></i> Lắp đặt chuyên nghiệp, tận tâm</li>
        </ul>
    </div>
</aside>
