<?php
/**
 * =======================================================================
 * TRANG ĐĂNG NHẬP (LOGIN.PHP)
 * =======================================================================
 */
?>
<div class="auth-page-container">
    <div class="auth-card">
        <div class="auth-card-head">
            <div class="brand-pill">
                <i class="fa-solid fa-bolt"></i> Điện máy XANH
            </div>
            <h2>Đăng Nhập Tài Khoản</h2>
            <p>Đăng nhập để theo dõi đơn hàng và nhận ưu đãi độc quyền</p>
        </div>

        <form action="<?= APP_URL ?>/index.php?act=login" method="POST" id="loginForm" class="auth-form">
            <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET['redirect'] ?? '') ?>">

            <div class="form-group">
                <label for="email">Địa chỉ Email <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                    <i class="fa-regular fa-envelope"></i>
                    <input type="email" id="email" name="email" class="form-control" placeholder="admin@dienmayxanh.com" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu (123456)" required>
                </div>
            </div>

            <div class="auth-helpers">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
                </label>
                <a href="#" class="forgot-pass">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn-auth-submit">
                <i class="fa-solid fa-right-to-bracket"></i> Đăng Nhập
            </button>
        </form>

        <div class="auth-card-footer">
            <p>Chưa có tài khoản? <a href="<?= APP_URL ?>/index.php?act=register">Đăng ký ngay tại đây</a></p>
            <div class="demo-account-hint" style="margin-top: 15px; padding: 12px; background: #f0f7ff; border-radius: 8px; font-size: 13px; border: 1px dashed #0071e3; text-align: left;">
                <div style="font-weight: 700; color: #004882; margin-bottom: 8px;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Bấm để điền nhanh tài khoản mẫu:
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="button" onclick="fillAccount('admin@dienmayxanh.com', '123456')" style="flex: 1; padding: 8px; background: #0071e3; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; transition: opacity 0.2s;">
                        <i class="fa-solid fa-user-shield"></i> Admin (123456)
                    </button>
                    <button type="button" onclick="fillAccount('minh.ngo@example.com', '123456')" style="flex: 1; padding: 8px; background: #ffd400; color: #004882; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; transition: opacity 0.2s;">
                        <i class="fa-solid fa-user"></i> User (123456)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillAccount(email, password) {
    var emailInput = document.getElementById('email');
    var passInput = document.getElementById('password');
    if (emailInput && passInput) {
        emailInput.value = email;
        passInput.value = password;
        emailInput.focus();
    }
}
</script>
