<?php
/**
 * =======================================================================
 * LAYOUT SIDEBAR ADMIN (VIEWS/ADMIN/LAYOUTS/SIDEBAR.PHP)
 * Menu dọc quản trị viên
 * =======================================================================
 */
$act = $_GET['act'] ?? 'dashboard';
?>
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-sidebar-brand">
        <a href="<?= APP_URL ?>/admin.php">
            <span class="brand-pill"><i class="fa-solid fa-bolt"></i> DMX ADMIN</span>
        </a>
    </div>

    <ul class="admin-nav-list">
        <li class="<?= $act === 'dashboard' ? 'active' : '' ?>">
            <a href="<?= APP_URL ?>/admin.php?act=dashboard">
                <i class="fa-solid fa-chart-line"></i>
                <span>Tổng quan</span>
            </a>
        </li>
        <li class="<?= strpos($act, 'product') === 0 ? 'active' : '' ?>">
            <a href="<?= APP_URL ?>/admin.php?act=products">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Quản lý sản phẩm</span>
            </a>
        </li>
        <li class="<?= strpos($act, 'categor') === 0 ? 'active' : '' ?>">
            <a href="<?= APP_URL ?>/admin.php?act=categories">
                <i class="fa-solid fa-list-check"></i>
                <span>Quản lý danh mục</span>
            </a>
        </li>
        <li class="<?= strpos($act, 'order') === 0 ? 'active' : '' ?>">
            <a href="<?= APP_URL ?>/admin.php?act=orders">
                <i class="fa-solid fa-cart-flatbed"></i>
                <span>Quản lý đơn hàng</span>
            </a>
        </li>
        <li class="<?= strpos($act, 'user') === 0 ? 'active' : '' ?>">
            <a href="<?= APP_URL ?>/admin.php?act=users">
                <i class="fa-solid fa-users"></i>
                <span>Quản lý thành viên</span>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="<?= APP_URL ?>" target="_blank">
                <i class="fa-solid fa-store"></i>
                <span>Xem Cửa Hàng</span>
            </a>
        </li>
        <li>
            <a href="<?= APP_URL ?>/index.php?act=logout" class="text-danger">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Đăng xuất</span>
            </a>
        </li>
    </ul>
</aside>
