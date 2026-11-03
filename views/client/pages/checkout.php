<?php
/**
 * =======================================================================
 * TRANG THANH TOÁN ĐƠN HÀNG (CHECKOUT.PHP)
 * Tái hiện 100% giao diện Thanh toán Điện Máy XANH từ Ảnh 4 và Ảnh 5
 * =======================================================================
 */
?>

<div class="checkout-page-bg">
    <div class="container py-4">
        <!-- Nút quay lại giỏ hàng -->
        <div class="checkout-top-nav">
            <a href="<?= APP_URL ?>/index.php?act=cart" class="btn-back-cart">
                <i class="fa-solid fa-chevron-left"></i> Thanh toán
            </a>
        </div>

        <form action="<?= APP_URL ?>/index.php?act=process_order" method="POST" id="checkoutForm">
            <div class="checkout-grid">
                <!-- CỘT BÊN TRÁI: CHI TIẾT GIAO HÀNG & SẢN PHẨM (Khớp ảnh 4) -->
                <div class="checkout-left-col">
                    <div class="checkout-card">
                        <h2 class="card-section-title">Chi tiết giao hàng</h2>

                        <!-- Lựa chọn hình thức giao hàng (Ảnh 4) -->
                        <div class="shipping-options-group">
                            <!-- Giao tiêu chuẩn -->
                            <label class="shipping-option-item active">
                                <div class="option-radio-col">
                                    <input type="radio" name="shipping_method" value="standard" checked onchange="updateShippingFee(0)">
                                    <span class="custom-radio"></span>
                                </div>
                                <div class="option-info-col">
                                    <div class="option-name-row">
                                        <strong>Giao tiêu chuẩn</strong>
                                        <span class="fee-free">Miễn phí</span>
                                    </div>
                                    <div class="option-time-desc">
                                        Nhận <strong>09h00 - 13h00, Thứ Bảy (17/10)</strong> <span class="change-link">Đổi</span>
                                    </div>
                                </div>
                            </label>

                            <!-- Giao siêu nhanh -->
                            <label class="shipping-option-item">
                                <div class="option-radio-col">
                                    <input type="radio" name="shipping_method" value="express" onchange="updateShippingFee(10000)">
                                    <span class="custom-radio"></span>
                                </div>
                                <div class="option-info-col">
                                    <div class="option-name-row">
                                        <strong>Giao siêu nhanh</strong>
                                        <span class="fee-amount">10.000₫</span>
                                    </div>
                                    <div class="option-time-desc">
                                        Nhận <strong>Trước 9h, Hôm nay (15/10)</strong>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Danh sách sản phẩm mua kèm trong đơn (Ảnh 4) -->
                        <div class="checkout-product-preview-list">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="checkout-prod-item">
                                    <div class="prod-thumbnail">
                                        <i class="fa-solid fa-bowl-rice"></i>
                                    </div>
                                    <div class="prod-details">
                                        <h4 class="prod-title"><?= htmlspecialchars($item['name']) ?></h4>
                                        <div class="prod-meta-price">
                                            <span class="price"><?= formatCurrency($item['sale_price'] ?? $item['price']) ?></span>
                                            <span class="qty">SL: <?= $item['quantity'] ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quà tặng kèm sản phẩm (Ảnh 4) -->
                                <div class="checkout-gift-box">
                                    <div class="gift-head">
                                        <i class="fa-solid fa-gift"></i> Quà tặng cùng sản phẩm
                                    </div>
                                    <div class="gift-body">
                                        <i class="fa-solid fa-gift gift-ico"></i>
                                        <span class="gift-txt"><?= htmlspecialchars($item['gift_info'] ?: 'Phiếu mua hàng mua Pin tiểu (đơn hàng từ 80.000đ trở lên) trị giá 20.000đ') ?></span>
                                        <span class="gift-sl">SL: 1</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Form nhập thông tin nhận hàng -->
                        <div class="customer-info-inputs mt-4">
                            <h3 class="input-section-title"><i class="fa-solid fa-user-pen"></i> Thông tin người nhận hàng</h3>
                            <div class="form-row-2">
                                <div class="form-group">
                                    <label>Họ và tên người nhận <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_name" class="form-control" required 
                                           placeholder="Ví dụ: Anh Ngô Quang Minh" 
                                           value="<?= htmlspecialchars($defaultCustomer['name']) ?>">
                                </div>
                                <div class="form-group">
                                    <label>Số điện thoại liên hệ <span class="text-danger">*</span></label>
                                    <input type="tel" name="customer_phone" class="form-control" required 
                                           placeholder="Ví dụ: 0342586038" 
                                           value="<?= htmlspecialchars($defaultCustomer['phone']) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Địa chỉ nhận hàng chi tiết <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_address" class="form-control" required 
                                       placeholder="Số nhà, ngõ ngách, tên đường, Phường/Xã, Tỉnh/TP" 
                                       value="<?= htmlspecialchars($defaultCustomer['address']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Ghi chú đơn hàng (nếu có)</label>
                                <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CỘT BÊN PHẢI: PHƯƠNG THỨC THANH TOÁN & CHI PHÍ (Khớp ảnh 5) -->
                <div class="checkout-right-col">
                    <!-- Khối thông tin địa chỉ rút gọn (Ảnh 4 & 5) -->
                    <div class="checkout-card address-summary-card">
                        <div class="card-subtitle">Giao tận nơi</div>
                        <div class="address-preview-row">
                            <i class="fa-solid fa-location-dot loc-ico"></i>
                            <div class="address-preview-text">
                                <strong><?= htmlspecialchars($defaultCustomer['name']) ?> - <?= htmlspecialchars($defaultCustomer['phone']) ?></strong>
                                <p><?= htmlspecialchars($defaultCustomer['address']) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Khối Mã giảm -->
                    <div class="checkout-card hover-action">
                        <div class="card-row-between">
                            <div class="left-icon-text">
                                <i class="fa-solid fa-ticket-simple text-danger"></i>
                                <span>Mã giảm</span>
                            </div>
                            <i class="fa-solid fa-chevron-right arrow-ico"></i>
                        </div>
                    </div>

                    <!-- Khối Dùng điểm Quà Tặng VIP (Ảnh 5) -->
                    <div class="checkout-card">
                        <div class="card-row-between">
                            <div class="left-icon-text">
                                <span class="vip-icon"><i class="fa-solid fa-star"></i></span>
                                <span>Dùng điểm Quà Tặng VIP</span>
                            </div>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Khối Yêu cầu khác (Ảnh 5) -->
                    <div class="checkout-card hover-action">
                        <div class="card-row-between">
                            <span>Yêu cầu khác</span>
                            <i class="fa-solid fa-chevron-down arrow-ico"></i>
                        </div>
                    </div>

                    <!-- Khối Chọn số tiền thanh toán (Ảnh 5) -->
                    <div class="checkout-card">
                        <h3 class="card-small-title">Chọn số tiền thanh toán</h3>
                        <div class="payment-amount-options">
                            <label class="amount-radio active">
                                <input type="radio" name="payment_type" value="full" checked onchange="updatePayType('full')">
                                <span class="radio-mark"></span>
                                <div class="amount-desc">
                                    <strong>Thanh toán toàn bộ đơn</strong>
                                    <span class="amount-val" id="fullPayVal"><?= formatCurrency($cartTotal) ?></span>
                                </div>
                            </label>

                            <label class="amount-radio">
                                <input type="radio" name="payment_type" value="deposit" onchange="updatePayType('deposit')">
                                <span class="radio-mark"></span>
                                <div class="amount-desc">
                                    <strong>Thanh toán tối thiểu để giữ hàng - <span class="text-blue">Chi tiết</span></strong>
                                    <span class="note-warn">(Lưu ý: Không hoàn tiền nếu không nhận hàng)</span>
                                    <span class="amount-val" id="depositPayVal"><?= formatCurrency($cartTotal * 0.1) ?></span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Khối Chọn phương thức thanh toán (Ảnh 5) -->
                    <div class="checkout-card">
                        <h3 class="card-small-title">Chọn phương thức thanh toán</h3>
                        <div class="payment-methods-list">
                            <!-- MWG Paylater -->
                            <label class="method-option-item">
                                <input type="radio" name="payment_method" value="paylater">
                                <span class="radio-mark"></span>
                                <div class="method-body">
                                    <div class="method-title">
                                        <i class="fa-solid fa-bolt text-warning"></i>
                                        <strong>MWG Paylater</strong>
                                        <span class="text-blue font-bold">Đăng ký ngay</span>
                                    </div>
                                    <p class="method-sub">(Hạn mức đến 60 triệu, lãi suất 0% đến 45 ngày)</p>
                                </div>
                            </label>

                            <!-- Chuyển khoản ngân hàng (QR Code) (Mặc định chọn ở ảnh 5) -->
                            <label class="method-option-item active" id="bankTransferOption">
                                <input type="radio" name="payment_method" value="bank_transfer" checked>
                                <span class="radio-mark"></span>
                                <div class="method-body">
                                    <div class="method-title">
                                        <i class="fa-solid fa-building-columns text-primary"></i>
                                        <strong>Chuyển khoản ngân hàng</strong>
                                    </div>
                                </div>
                            </label>

                            <!-- Hộp thông tin chuyển khoản & VietQR -->
                            <div class="bank-qr-box" id="bankQrBox">
                                <div class="qr-notice">
                                    <i class="fa-solid fa-qrcode"></i> Quét mã VietQR hoặc chuyển khoản trực tiếp:
                                </div>
                                <div class="qr-details">
                                    <p><strong>Ngân hàng:</strong> MBBank (Ngân hàng Quân Đội)</p>
                                    <p><strong>Số tài khoản:</strong> <span class="highlight-code">0342586038</span></p>
                                    <p><strong>Chủ tài khoản:</strong> CONG TY DIEN MAY XANH</p>
                                    <p><strong>Số tiền:</strong> <span class="text-danger font-bold" id="qrAmountDisplay"><?= formatCurrency($cartTotal) ?></span></p>
                                    <p><strong>Nội dung CK:</strong> <span class="highlight-code">DMX <?= date('Ymd') ?></span></p>
                                </div>
                            </div>

                            <!-- Qua thẻ tín dụng / ghi nợ -->
                            <label class="method-option-item">
                                <input type="radio" name="payment_method" value="credit_card">
                                <span class="radio-mark"></span>
                                <div class="method-body">
                                    <div class="method-title">
                                        <i class="fa-solid fa-credit-card text-info"></i>
                                        <strong>Qua thẻ tín dụng / ghi nợ</strong>
                                    </div>
                                    <div class="card-logos-row">
                                        <i class="fa-brands fa-cc-visa text-primary"></i>
                                        <i class="fa-brands fa-cc-mastercard text-danger"></i>
                                        <i class="fa-brands fa-cc-jcb text-success"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- COD -->
                            <label class="method-option-item">
                                <input type="radio" name="payment_method" value="cod">
                                <span class="radio-mark"></span>
                                <div class="method-body">
                                    <div class="method-title">
                                        <i class="fa-solid fa-money-bill-wave text-success"></i>
                                        <strong>Thanh toán tiền mặt khi nhận hàng (COD)</strong>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Khối Chi tiết thanh toán cuối cùng (Ảnh 5) -->
                    <div class="checkout-card summary-final-card">
                        <h3 class="card-small-title">Chi tiết thanh toán</h3>
                        <div class="summary-line">
                            <span>Tổng tiền hàng</span>
                            <span class="price-val"><?= formatCurrency($cartTotal) ?></span>
                        </div>
                        <div class="summary-line">
                            <span>Phí giao hàng</span>
                            <span class="price-val" id="shippingFeeDisplay">Miễn phí</span>
                        </div>
                        <div class="summary-line total-line">
                            <strong>Tổng thanh toán</strong>
                            <strong class="total-price-val" id="finalTotalDisplay"><?= formatCurrency($cartTotal) ?></strong>
                        </div>
                        <div class="summary-vip-reward">
                            <span>Tổng điểm tích luỹ Quà Tặng VIP (dự kiến)</span>
                            <span class="vip-points"><strong>3.950 điểm</strong></span>
                        </div>

                        <!-- Checkbox chính sách dữ liệu (Ảnh 5) -->
                        <div class="terms-agreement-row">
                            <label class="custom-checkbox-label">
                                <input type="checkbox" id="termsCheck" required checked>
                                <span class="checkmark"></span>
                                <span class="terms-text">
                                    Tôi đồng ý với <a href="#" class="text-blue">Chính sách xử lý dữ liệu cá nhân</a> cho Công Ty Cổ Phần Đầu Tư Điện Máy Xanh sử dụng tên, số điện thoại, địa chỉ để xử lý đơn hàng (đặt hàng, thanh toán, giao hàng, bảo hành, xử lý khiếu nại) tạo tài khoản và đăng nhập vào hệ thống để quản lý đơn hàng. (bắt buộc) <a href="#" class="text-blue">Xem chi tiết</a>
                                </span>
                            </label>
                        </div>

                        <!-- Nút Thanh toán xanh dương Điện Máy Xanh (Ảnh 5) -->
                        <button type="submit" class="btn-checkout-submit" id="btnSubmitOrder">
                            Thanh toán
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let subtotal = <?= (float)$cartTotal ?>;
    let currentShippingFee = 0;
    let payType = 'full';

    function updateShippingFee(fee) {
        currentShippingFee = fee;
        document.getElementById('shippingFeeDisplay').innerText = fee > 0 ? (fee.toLocaleString('vi-VN') + '₫') : 'Miễn phí';
        calculateTotals();
    }

    function updatePayType(type) {
        payType = type;
        calculateTotals();
    }

    function calculateTotals() {
        let grandTotal = subtotal + currentShippingFee;
        let payableTotal = (payType === 'deposit') ? (grandTotal * 0.1) : grandTotal;

        document.getElementById('fullPayVal').innerText = grandTotal.toLocaleString('vi-VN') + '₫';
        document.getElementById('depositPayVal').innerText = (grandTotal * 0.1).toLocaleString('vi-VN') + '₫';
        document.getElementById('finalTotalDisplay').innerText = payableTotal.toLocaleString('vi-VN') + '₫';
        if (document.getElementById('qrAmountDisplay')) {
            document.getElementById('qrAmountDisplay').innerText = payableTotal.toLocaleString('vi-VN') + '₫';
        }
    }
</script>
