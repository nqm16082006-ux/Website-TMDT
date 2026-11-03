<?php
/**
 * =======================================================================
 * LAYOUT FOOTER PHÍA KHÁCH HÀNG (FOOTER.PHP)
 * Chuẩn thông tin chân trang Điện Máy XANH theo đúng ảnh 5
 * =======================================================================
 */
?>
    </main>

    <!-- FOOTER CHÍNH THỐNG ĐIỆN MÁY XANH -->
    <footer class="main-footer">
        <div class="container footer-container">
            <!-- Cột 1: Tổng đài hỗ trợ -->
            <div class="footer-col">
                <h4 class="footer-title">Tổng đài hỗ trợ</h4>
                <ul class="footer-list">
                    <li>Gọi mua: <a href="tel:1900232461" class="hotline">1900 232 461</a> (8:00 - 21:30)</li>
                    <li>Khiếu nại: <a href="tel:18001063" class="hotline">1800.1063</a> (8:00 - 21:30)</li>
                    <li>Bảo hành: <a href="tel:1900232464" class="hotline">1900 232 464</a> (8:00 - 21:00)</li>
                </ul>
            </div>

            <!-- Cột 2: Về công ty -->
            <div class="footer-col">
                <h4 class="footer-title">Về công ty</h4>
                <ul class="footer-list">
                    <li><a href="<?= APP_URL ?>/index.php?act=contact">Giới thiệu công ty (DMX.vn)</a></li>
                    <li><a href="#">Tuyển dụng mới nhất</a></li>
                    <li><a href="#">Gửi góp ý, khiếu nại</a></li>
                    <li><a href="#">Tìm siêu thị (3.150 shop)</a></li>
                </ul>
            </div>

            <!-- Cột 3: Thông tin khác -->
            <div class="footer-col">
                <h4 class="footer-title">Thông tin khác</h4>
                <ul class="footer-list">
                    <li><a href="#">Tích điểm Quà Tặng VIP</a></li>
                    <li><a href="#">Chính sách bảo hành & lắp đặt</a></li>
                    <li><a href="#">Chính sách đổi trả 1 đổi 1</a></li>
                    <li><a href="#">Giao hàng & thanh toán tận nơi</a></li>
                </ul>
            </div>

            <!-- Cột 4: Website cùng tập đoàn & Thanh toán -->
            <div class="footer-col">
                <h4 class="footer-title">Website cùng tập đoàn</h4>
                <div class="group-brands">
                    <span class="group-pill tgdd">thegioididong</span>
                    <span class="group-pill topzone">TopZone</span>
                    <span class="group-pill ankhang">An Khang</span>
                    <span class="group-pill avakids">AVAKids</span>
                </div>
                <h4 class="footer-title" style="margin-top: 15px;">Hình thức thanh toán</h4>
                <div class="payment-partners">
                    <i class="fa-brands fa-cc-visa" title="Visa"></i>
                    <i class="fa-brands fa-cc-mastercard" title="Mastercard"></i>
                    <i class="fa-solid fa-qrcode" title="VietQR"></i>
                    <i class="fa-solid fa-money-bill-wave" title="COD"></i>
                </div>
            </div>
        </div>

        <!-- Chân trang bản quyền -->
        <div class="footer-bottom">
            <div class="container footer-bottom-content">
                <p>© 2026. Công Ty Cổ Phần Đầu Tư Điện Máy Xanh. Địa chỉ: Tòa nhà MWG, TP. Hồ Chí Minh.</p>
                <p>Website thương mại điện tử chuyên nghiệp được xây dựng chuẩn mô hình MVC PHP thuần.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript Files -->
    <script>
        const APP_URL = "<?= APP_URL ?>";
    </script>
    <script src="<?= ASSETS_URL ?>/js/validator.js"></script>
    <script src="<?= ASSETS_URL ?>/js/main.js"></script>
</body>
</html>
