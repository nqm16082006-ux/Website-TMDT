<?php
/**
 * =======================================================================
 * TRANG CÀI ĐẶT TÀI KHOẢN (VIEWS/CLIENT/PROFILE/SETTINGS.PHP)
 * =======================================================================
 */
?>
<div class="container my-5">
    <div class="profile-layout">
        <!-- Sidebar tài khoản -->
        <div class="profile-sidebar">
            <div class="user-brief">
                <div class="user-avatar-circle"><i class="fa-solid fa-user"></i></div>
                <h4><?= htmlspecialchars($_SESSION['user']['fullname']) ?></h4>
                <p><?= htmlspecialchars($_SESSION['user']['email']) ?></p>
            </div>
            <ul class="profile-nav-menu">
                <li><a href="<?= APP_URL ?>/index.php?act=profile"><i class="fa-solid fa-gauge"></i> Tổng quan</a></li>
                <li><a href="<?= APP_URL ?>/index.php?act=profile_orders"><i class="fa-solid fa-box-archive"></i> Đơn hàng đã mua</a></li>
                <li class="active"><a href="<?= APP_URL ?>/index.php?act=profile_settings"><i class="fa-solid fa-gear"></i> Cài đặt tài khoản</a></li>
                <li><a href="<?= APP_URL ?>/index.php?act=logout" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
            </ul>
        </div>

        <div class="profile-main">
            <!-- Cập nhật thông tin cá nhân -->
            <div class="profile-card mb-4">
                <h3>Cập Nhật Thông Tin Cá Nhân</h3>
                <form action="<?= APP_URL ?>/index.php?act=profile_settings" method="POST">
                    <input type="hidden" name="sub_act" value="update_profile">
                    
                    <div class="form-group">
                        <label>Họ và tên</label>
                        <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($user['fullname']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email (Không thể thay đổi)</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly style="background: #f1f3f5;">
                    </div>

                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>Địa chỉ mặc định</label>
                        <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user['address'] ?? '') ?>">
                    </div>

                    <button type="submit" class="btn-checkout-primary" style="max-width: 200px;">Lưu Thay Đổi</button>
                </form>
            </div>

            <!-- Đổi mật khẩu -->
            <div class="profile-card">
                <h3>Đổi Mật Khẩu Đăng Nhập</h3>
                <form action="<?= APP_URL ?>/index.php?act=profile_settings" method="POST">
                    <input type="hidden" name="sub_act" value="change_password">

                    <div class="form-group">
                        <label>Mật khẩu hiện tại</label>
                        <input type="password" name="old_password" class="form-control" required placeholder="Nhập mật khẩu hiện tại">
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label>Mật khẩu mới</label>
                            <input type="password" name="new_password" class="form-control" required placeholder="Tối thiểu 6 ký tự">
                        </div>
                        <div class="form-group">
                            <label>Xác nhận mật khẩu mới</label>
                            <input type="password" name="confirm_password" class="form-control" required placeholder="Nhập lại mật khẩu mới">
                        </div>
                    </div>

                    <button type="submit" class="btn-checkout-primary" style="max-width: 200px; background: #333;">Đổi Mật Khẩu</button>
                </form>
            </div>
        </div>
    </div>
</div>
