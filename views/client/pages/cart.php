<?php
/**
 * =======================================================================
 * TRANG GIỎ HÀNG (CART.PHP)
 * Tái hiện 100% giao diện Giỏ hàng Điện Máy XANH từ Ảnh 3
 * =======================================================================
 */
?>

<div class="cart-page-bg">
    <div class="container py-4">
        <!-- Nút quay lại trang chủ -->
        <div class="cart-top-nav">
            <a href="<?= APP_URL ?>" class="btn-back-home">
                <i class="fa-solid fa-chevron-left"></i> Giỏ hàng
            </a>
        </div>

        <?php if (!empty($cartItems)): ?>
            <div class="cart-content-grid">
                <!-- CỘT BÊN TRÁI: DANH SÁCH SẢN PHẨM (Khớp ảnh 3) -->
                <div class="cart-left-col">
                    <div class="cart-items-card">
                        <!-- Tiêu đề & Chọn tất cả -->
                        <div class="cart-card-header">
                            <h2 class="cart-card-title">Danh sách sản phẩm</h2>
                        </div>
                        <div class="cart-select-all-row">
                            <label class="custom-checkbox-label">
                                <input type="checkbox" id="checkAllItems" checked>
                                <span class="checkmark"></span>
                                <span class="check-text">Chọn tất cả</span>
                            </label>
                            <span class="selected-count-badge">Đã chọn: <strong><?= count($cartItems) ?> sản phẩm</strong></span>
                        </div>

                        <!-- Danh sách các món hàng trong giỏ -->
                        <div class="cart-items-list">
                            <?php foreach ($cartItems as $id => $item): ?>
                                <div class="cart-item-row" id="cart-item-<?= $id ?>">
                                    <div class="cart-item-main">
                                        <!-- Checkbox từng món -->
                                        <label class="custom-checkbox-label item-check">
                                            <input type="checkbox" class="item-checkbox" checked data-id="<?= $id ?>" data-price="<?= $item['sale_price'] ?? $item['price'] ?>">
                                            <span class="checkmark"></span>
                                        </label>

                                        <!-- Ảnh sản phẩm -->
                                        <div class="cart-item-img">
                                            <i class="fa-solid fa-bowl-rice"></i>
                                        </div>

                                        <!-- Thông tin & Giá -->
                                        <div class="cart-item-details">
                                            <h4 class="cart-item-title">
                                                <a href="<?= APP_URL ?>/index.php?act=product-detail&id=<?= $item['id'] ?>">
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </a>
                                            </h4>

                                            <!-- Nhãn Flashsale kết thúc (Ảnh 3) -->
                                            <div class="cart-flash-pill">
                                                <i class="fa-solid fa-bolt"></i> Flashsale kết thúc sau 21:46:41 <i class="fa-solid fa-chevron-down" style="font-size: 10px;"></i>
                                            </div>

                                            <div class="cart-item-price" id="item-price-<?= $id ?>">
                                                <?= formatCurrency($item['sale_price'] ?? $item['price']) ?>
                                            </div>

                                            <!-- Bộ đếm số lượng & Xóa (Ảnh 3) -->
                                            <div class="cart-quantity-row">
                                                <a href="<?= APP_URL ?>/index.php?act=cart_remove&id=<?= $id ?>" class="btn-cart-delete" title="Xóa món này">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </a>
                                                <div class="cart-stepper">
                                                    <button type="button" class="btn-step-cart" onclick="updateCartQty(<?= $id ?>, <?= $item['quantity'] - 1 ?>)">-</button>
                                                    <span class="qty-number" id="qty-val-<?= $id ?>"><?= $item['quantity'] ?></span>
                                                    <button type="button" class="btn-step-cart" onclick="updateCartQty(<?= $id ?>, <?= $item['quantity'] + 1 ?>)">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quà tặng cùng sản phẩm (Khớp ảnh 3) -->
                                    <div class="cart-gift-container">
                                        <div class="gift-badge-header">
                                            <i class="fa-solid fa-gift"></i> Quà tặng cùng sản phẩm
                                        </div>
                                        <div class="gift-item-row">
                                            <div class="gift-icon-box">
                                                <i class="fa-solid fa-gift"></i>
                                            </div>
                                            <div class="gift-title-text">
                                                <?= htmlspecialchars($item['gift_info'] ?: 'Phiếu mua hàng mua Pin tiểu (đơn hàng từ 80.000đ trở lên) trị giá 20.000đ') ?>
                                            </div>
                                            <div class="gift-qty">SL: 1</div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Nút Xóa tất cả sản phẩm (Ảnh 3) -->
                        <div class="cart-bottom-actions">
                            <a href="<?= APP_URL ?>/index.php?act=cart_clear" class="btn-clear-all" onclick="return confirm('Bạn có chắc muốn xóa tất cả sản phẩm khỏi giỏ hàng?');">
                                Xóa tất cả sản phẩm
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CỘT BÊN PHẢI: TÍNH TIỀN & ĐẶT MUA (Khớp ảnh 3) -->
                <div class="cart-right-col">
                    <!-- Khối chọn hình thức giao hàng -->
                    <div class="cart-summary-card">
                        <div class="delivery-toggle-group">
                            <label class="delivery-radio active">
                                <input type="radio" name="delivery_type" value="home" checked>
                                <span>Giao tận nơi</span>
                            </label>
                            <label class="delivery-radio">
                                <input type="radio" name="delivery_type" value="store">
                                <span>Nhận tại siêu thị</span>
                            </label>
                        </div>

                        <!-- Địa chỉ nhận hàng -->
                        <a href="<?= APP_URL ?>/index.php?act=checkout" class="cart-address-box">
                            <div class="address-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <div class="address-text">
                                <strong>Thành phố Hà Nội</strong>
                                <span>Vui lòng cung cấp thông tin nhận hàng</span>
                            </div>
                            <i class="fa-solid fa-chevron-right arrow-icon"></i>
                        </a>
                    </div>

                    <!-- Khối Mã giảm giá (Ảnh 3) -->
                    <div class="cart-summary-card">
                        <div class="voucher-accordion-trigger">
                            <div class="voucher-icon-title">
                                <i class="fa-solid fa-ticket-simple text-danger"></i>
                                <span>Mã giảm</span>
                            </div>
                            <i class="fa-solid fa-chevron-right arrow-icon"></i>
                        </div>
                    </div>

                    <!-- Khối Quà tặng VIP (Ảnh 3) -->
                    <div class="cart-summary-card">
                        <div class="vip-toggle-row">
                            <div class="vip-label">
                                <span class="vip-badge-icon"><i class="fa-solid fa-star"></i></span>
                                <span>Dùng điểm Quà Tặng VIP</span>
                            </div>
                            <label class="switch">
                                <input type="checkbox" id="vipSwitch">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Khối Chi tiết thanh toán & Nút Đặt mua (Ảnh 3) -->
                    <div class="cart-summary-card checkout-box">
                        <h3 class="summary-heading">Chi tiết thanh toán</h3>
                        <div class="summary-line">
                            <span>Tổng tiền hàng</span>
                            <span class="price-val" id="cartSummarySubtotal"><?= formatCurrency($cartTotal) ?></span>
                        </div>
                        <div class="summary-line total-line">
                            <strong>Tổng thanh toán</strong>
                            <strong class="total-price-val" id="cartSummaryTotal"><?= formatCurrency($cartTotal) ?></strong>
                        </div>

                        <a href="<?= APP_URL ?>/index.php?act=checkout" class="btn-checkout-primary">
                            Đặt mua
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="cart-empty-state">
                <i class="fa-solid fa-cart-shopping empty-cart-icon"></i>
                <h2>Giỏ hàng của bạn đang trống</h2>
                <p>Không có sản phẩm nào trong giỏ hàng. Hãy lướt Điện máy XANH để chọn món đồ yêu thích nhé!</p>
                <a href="<?= APP_URL ?>" class="btn-checkout-primary" style="display:inline-block; max-width: 250px;">
                    Mua sắm ngay
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
