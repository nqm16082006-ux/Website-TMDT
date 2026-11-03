<?php
/**
 * =======================================================================
 * LAYOUT HEADER PHÍA KHÁCH HÀNG (HEADER.PHP)
 * Chuẩn phong cách thiết kế Điện Máy XANH theo đúng ảnh 1, 2, 3, 4
 * =======================================================================
 */
$cartCount = getCartCount();
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Điện máy XANH - Siêu thị điện máy hàng đầu') ?></title>
    
    <!-- Google Font Inter / Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/main.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/responsive.css">
</head>
<body>

    <!-- 1. TOP BANNER KHUYẾN MÃI (Theo ảnh 1 & 3) -->
    <div class="top-promo-banner">
        <div class="container top-promo-content">
            <span class="badge-online">MUA ONLINE</span>
            <span class="promo-text-white">GIÁ RẺ HƠN</span>
            <span class="promo-text-yellow">CHẠM ĐỂ LẤY MÃ GIẢM ĐẾN 1.2 TRIỆU</span>
            <a href="<?= APP_URL ?>/index.php?act=products" class="btn-get-code">NHẬN NGAY</a>
        </div>
    </div>

    <!-- 2. HEADER CHÍNH BRAND BLUE ĐIỆN MÁY XANH -->
    <header class="main-header">
        <div class="container header-container">
            <!-- Logo Điện Máy XANH -->
            <a href="<?= APP_URL ?>" class="brand-logo">
                <div class="logo-icon">
                    <i class="fa-solid fa-person-walking-luggage"></i>
                </div>
                <div class="logo-text">
                    <span class="txt-dienmay">Điện máy</span>
                    <span class="txt-xanh">XANH</span>
                </div>
            </a>

            <!-- Nút Danh mục -->
            <div class="category-menu-toggle" id="catMenuToggle">
                <i class="fa-solid fa-bars"></i>
                <span>Danh mục</span>
            </div>

            <!-- Ô tìm kiếm thông minh -->
            <form action="<?= APP_URL ?>/index.php" method="GET" class="search-bar">
                <input type="hidden" name="act" value="products">
                <div class="search-input-wrapper">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" name="search" class="search-input" 
                           placeholder="Mua sớm combo Toshiba, nồi cơm điện..." 
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" autocomplete="off">
                </div>
                <button type="submit" class="search-submit-btn" style="display:none;"></button>
            </form>

            <!-- Tài khoản / Đăng nhập -->
            <div class="header-action-item user-action">
                <?php if ($user): ?>
                    <div class="dropdown-trigger">
                        <i class="fa-regular fa-circle-user header-icon"></i>
                        <span class="action-label"><?= htmlspecialchars(mb_strimwidth($user['fullname'], 0, 12, '...')) ?></span>
                        <div class="user-dropdown-menu">
                            <a href="<?= APP_URL ?>/index.php?act=profile"><i class="fa-solid fa-user"></i> Tài khoản của tôi</a>
                            <a href="<?= APP_URL ?>/index.php?act=profile_orders"><i class="fa-solid fa-receipt"></i> Đơn hàng đã mua</a>
                            <?php if ($user['role'] === 'admin'): ?>
                                <a href="<?= APP_URL ?>/admin.php" style="color: #0071e3; font-weight: 700;"><i class="fa-solid fa-gauge-high"></i> Trang Quản Trị Admin</a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?= APP_URL ?>/index.php?act=logout" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/index.php?act=login" class="action-link">
                        <i class="fa-regular fa-circle-user header-icon"></i>
                        <span class="action-label">Đăng nhập</span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Voucher -->
            <a href="<?= APP_URL ?>/index.php?act=products" class="header-action-item action-link">
                <i class="fa-solid fa-ticket header-icon"></i>
                <span class="action-label">Voucher</span>
            </a>

            <!-- Giỏ hàng (Khớp ảnh 1 & 3) -->
            <a href="<?= APP_URL ?>/index.php?act=cart" class="header-action-item cart-btn action-link">
                <div class="cart-icon-wrapper">
                    <i class="fa-solid fa-cart-shopping header-icon"></i>
                    <span class="cart-badge" id="cartCountBadge"><?= $cartCount ?></span>
                </div>
                <span class="action-label">Giỏ hàng</span>
            </a>

            <!-- Địa điểm / Thành phố -->
            <div class="header-action-item location-selector">
                <i class="fa-solid fa-location-dot location-icon"></i>
                <span class="location-name">Thành phố Hà Nội</span>
                <i class="fa-solid fa-chevron-down" style="font-size: 10px; margin-left: 4px;"></i>
            </div>
        </div>

        <!-- 3. THANH DANH MỤC NHANH (SUB-NAV) (Theo ảnh 1) -->
        <div class="sub-nav">
            <div class="container sub-nav-container">
                <a href="<?= APP_URL ?>/index.php?act=products&category=6" class="sub-nav-item">điều hòa</a>
                <a href="<?= APP_URL ?>/index.php?act=products&category=2" class="sub-nav-item">tủ lạnh</a>
                <a href="<?= APP_URL ?>/index.php?act=products&category=7" class="sub-nav-item">máy lọc nước</a>
                <a href="<?= APP_URL ?>/index.php?act=products&category=11" class="sub-nav-item">quạt sưởi</a>
                <a href="<?= APP_URL ?>/index.php?act=products&category=5" class="sub-nav-item">nồi chiên</a>
                <a href="<?= APP_URL ?>/index.php?act=products&category=4" class="sub-nav-item">smart Tivi</a>
                <a href="<?= APP_URL ?>/index.php?act=products&category=5" class="sub-nav-item">máy rửa bát</a>
                <a href="<?= APP_URL ?>/index.php?act=products&category=13" class="sub-nav-item">loa</a>
                <a href="<?= APP_URL ?>/index.php?act=products&category=12" class="sub-nav-item">nồi cơm điện</a>
                <a href="<?= APP_URL ?>/index.php?act=products&category=15" class="sub-nav-item">máy lọc không khí</a>
            </div>
        </div>
    </header>

    <!-- THÔNG BÁO FLASH MESSAGE NẾU CÓ -->
    <?php 
    $flashSuccess = getFlashMessage('success');
    $flashError = getFlashMessage('error');
    ?>
    <?php if ($flashSuccess): ?>
        <div class="flash-alert flash-success">
            <div class="container flash-content">
                <i class="fa-solid fa-circle-check"></i>
                <span><?= htmlspecialchars($flashSuccess) ?></span>
                <button type="button" class="close-alert" onclick="this.parentElement.parentElement.remove();">&times;</button>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($flashError): ?>
        <div class="flash-alert flash-danger">
            <div class="container flash-content">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?= htmlspecialchars($flashError) ?></span>
                <button type="button" class="close-alert" onclick="this.parentElement.parentElement.remove();">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <!-- NỘI DUNG CHÍNH (MAIN WRAPPER) -->
    <main class="main-wrapper">
