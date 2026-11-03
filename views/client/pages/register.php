<?php
/**
 * =======================================================================
 * TRANG ĐĂNG KÝ (REGISTER.PHP)
 * =======================================================================
 */
?>
<div class="auth-page-container">
    <div class="auth-card">
        <div class="auth-card-head">
            <div class="brand-pill">
                <i class="fa-solid fa-bolt"></i> Điện máy XANH
            </div>
            <h2>Đăng Ký Thành Viên</h2>
            <p>Tạo tài khoản để nhận voucher giảm đến 1.200.000₫</p>
        </div>

        <form action="<?= APP_URL ?>/index.php?act=register" method="POST" id="registerForm" class="auth-form">
            <div class="form-group">
                <label for="fullname">Họ và tên <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                    <i class="fa-regular fa-user"></i>
                    <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Ví dụ: Nguyễn Văn An" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Địa chỉ Email <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                    <i class="fa-regular fa-envelope"></i>
                    <input type="email" id="email" name="email" class="form-control" placeholder="nguyenvanan@gmail.com" required>
                </div>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-phone"></i>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="0912345678">
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ giao hàng</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-location-dot"></i>
                    <input type="text" id="address" name="address" class="form-control" placeholder="Ví dụ: 123 Đường Cầu Giấy, Hà Nội">
                </div>
            </div>

            <button type="submit" class="btn-auth-submit">
                <i class="fa-solid fa-user-plus"></i> Đăng Ký Tài Khoản
            </button>
        </form>

        <div class="auth-card-footer">
            <p>Đã có tài khoản? <a href="<?= APP_URL ?>/index.php?act=login">Đăng nhập ngay</a></p>
        </div>
    </div>
</div>
