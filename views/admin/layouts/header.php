<?php
/**
 * =======================================================================
 * LAYOUT HEADER ADMIN (VIEWS/ADMIN/LAYOUTS/HEADER.PHP)
 * =======================================================================
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Quản Trị Hệ Thống - Điện máy XANH') ?></title>
    
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar Admin dọc -->
        <?php require_once VIEW_PATH . '/admin/layouts/sidebar.php'; ?>

        <!-- Khu vực nội dung chính bên phải -->
        <div class="admin-main-area">
            <!-- Top Header Admin -->
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button class="btn-toggle-sidebar" id="adminSidebarToggle"><i class="fa-solid fa-bars"></i></button>
                    <span class="admin-page-heading"><?= htmlspecialchars($pageTitle ?? 'Quản Trị') ?></span>
                </div>
                <div class="topbar-right">
                    <a href="<?= APP_URL ?>" target="_blank" class="btn-view-site" title="Xem trang chủ bán hàng">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem Website
                    </a>
                    <div class="admin-profile-pill">
                        <i class="fa-solid fa-circle-user"></i>
                        <span><?= htmlspecialchars($_SESSION['user']['fullname']) ?></span>
                        <a href="<?= APP_URL ?>/index.php?act=logout" class="logout-link" title="Đăng xuất"><i class="fa-solid fa-power-off"></i></a>
                    </div>
                </div>
            </header>

            <!-- Flash message admin -->
            <?php 
            $fSuccess = getFlashMessage('success');
            $fError = getFlashMessage('error');
            ?>
            <?php if ($fSuccess): ?>
                <div class="admin-alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($fSuccess) ?>
                </div>
            <?php endif; ?>
            <?php if ($fError): ?>
                <div class="admin-alert alert-danger">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($fError) ?>
                </div>
            <?php endif; ?>

            <div class="admin-page-content">
