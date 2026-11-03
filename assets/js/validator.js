/**
 * =======================================================================
 * VALIDATOR FORM ĐĂNG KÝ / ĐĂNG NHẬP / THANH TOÁN (VALIDATOR.JS)
 * =======================================================================
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Kiểm tra Form Đăng Ký
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const pwd = document.getElementById('password').value;
            const confirmPwd = document.getElementById('confirm_password').value;

            if (pwd.length < 6) {
                alert('Mật khẩu phải có độ dài tối thiểu từ 6 ký tự!');
                e.preventDefault();
                return false;
            }

            if (pwd !== confirmPwd) {
                alert('Mật khẩu xác nhận không khớp! Vui lòng nhập lại.');
                e.preventDefault();
                return false;
            }
        });
    }

    // 2. Kiểm tra Form Thanh Toán (Ảnh 5)
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            const terms = document.getElementById('termsCheck');
            if (terms && !terms.checked) {
                alert('Vui lòng tích đồng ý với Chính sách xử lý dữ liệu cá nhân của Điện Máy Xanh để tiếp tục đặt hàng!');
                e.preventDefault();
                return false;
            }
        });
    }
});
