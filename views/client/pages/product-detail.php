<?php
/**
 * =======================================================================
 * TRANG CHI TIẾT SẢN PHẨM (PRODUCT-DETAIL.PHP)
 * Bao gồm: Giá sốc, đếm ngược flash sale, quà tặng kèm, thông số kỹ thuật
 * =======================================================================
 */
?>

<div class="container my-4">
    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <a href="<?= APP_URL ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
        <i class="fa-solid fa-chevron-right separator"></i>
        <a href="<?= APP_URL ?>/index.php?act=products&category=<?= $product['category_id'] ?>"><?= htmlspecialchars($product['category_name']) ?></a>
        <i class="fa-solid fa-chevron-right separator"></i>
        <span><?= htmlspecialchars($product['name']) ?></span>
    </div>

    <div class="product-detail-layout">
        <!-- Cột 1: Ảnh sản phẩm lớn -->
        <div class="detail-gallery-col">
            <div class="main-image-preview">
                <div class="detail-icon-large">
                    <?php if ($product['category_id'] == 12): ?>
                        <i class="fa-solid fa-bowl-rice"></i>
                    <?php elseif ($product['category_id'] == 4): ?>
                        <i class="fa-solid fa-tv"></i>
                    <?php elseif ($product['category_id'] == 2): ?>
                        <i class="fa-solid fa-temperature-arrow-down"></i>
                    <?php else: ?>
                        <i class="fa-solid fa-kitchen-set"></i>
                    <?php endif; ?>
                </div>
                <?php if ($product['flash_sale']): ?>
                    <span class="detail-flash-badge"><i class="fa-solid fa-bolt"></i> FLASH SALE GIÁ SỐC</span>
                <?php endif; ?>
            </div>
            
            <div class="product-commitments-card">
                <div class="commit-item"><i class="fa-solid fa-truck-fast"></i> Giao siêu nhanh trong 2 giờ</div>
                <div class="commit-item"><i class="fa-solid fa-rotate-left"></i> 1 đổi 1 trong 30 ngày nếu lỗi kỹ thuật</div>
                <div class="commit-item"><i class="fa-solid fa-shield-check"></i> Bảo hành chính hãng 12 - 24 tháng</div>
            </div>
        </div>

        <!-- Cột 2: Thông tin mua hàng & Giá sốc -->
        <div class="detail-info-col">
            <h1 class="detail-title"><?= htmlspecialchars($product['name']) ?></h1>
            <div class="detail-rating-meta">
                <span class="stars"><i class="fa-solid fa-star"></i> <?= $product['rating'] ?></span>
                <span class="reviews">(<?= $product['review_count'] ?> đánh giá)</span>
                <span class="sold">Đã bán: <?= $product['sold_count'] ?></span>
            </div>

            <!-- Khối giá bán -->
            <div class="detail-price-box">
                <div class="current-price-row">
                    <span class="current-price"><?= formatCurrency($product['sale_price'] ?? $product['price']) ?></span>
                    <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                        <span class="original-price"><?= formatCurrency($product['price']) ?></span>
                        <span class="discount-pill">-<?= $product['discount_percent'] ?>%</span>
                    <?php endif; ?>
                </div>
                <?php if ($product['flash_sale']): ?>
                    <div class="flash-alert-strip">
                        <i class="fa-solid fa-clock"></i> Khuyến mãi Flash Sale giá sốc kết thúc trong hôm nay!
                    </div>
                <?php endif; ?>
            </div>

            <!-- Khối quà tặng đính kèm (Khớp ảnh 3 & 4) -->
            <?php if (!empty($product['gift_info'])): ?>
                <div class="gift-box-wrapper">
                    <div class="gift-header">
                        <i class="fa-solid fa-gift"></i> Quà tặng kèm sản phẩm:
                    </div>
                    <div class="gift-content">
                        <i class="fa-solid fa-circle-check text-success"></i>
                        <span><?= htmlspecialchars($product['gift_info']) ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form Đặt mua & Số lượng -->
            <form action="<?= APP_URL ?>/index.php" method="GET" class="detail-buy-form">
                <input type="hidden" name="act" value="cart_add">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">

                <div class="quantity-control-row">
                    <label>Số lượng:</label>
                    <div class="quantity-stepper">
                        <button type="button" class="btn-step" onclick="var q = document.getElementById('qtyInput'); if(q.value > 1) q.value--;">-</button>
                        <input type="number" id="qtyInput" name="quantity" value="1" min="1" max="99">
                        <button type="button" class="btn-step" onclick="var q = document.getElementById('qtyInput'); q.value++;">+</button>
                    </div>
                </div>

                <div class="detail-action-buttons">
                    <button type="submit" class="btn-buy-now">
                        <strong>MUA NGAY</strong>
                        <span>Giao tận nơi hoặc nhận tại siêu thị</span>
                    </button>
                    <a href="<?= APP_URL ?>/index.php?act=cart_add&id=<?= $product['id'] ?>" class="btn-add-to-cart">
                        <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
                    </a>
                </div>
            </form>

            <!-- Mô tả ngắn -->
            <?php if (!empty($product['short_description'])): ?>
                <div class="short-desc-box">
                    <h4>Đặc điểm nổi bật:</h4>
                    <p><?= nl2br(htmlspecialchars($product['short_description'])) ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Cột 3: Thông số kỹ thuật -->
        <div class="detail-specs-col">
            <div class="specs-card">
                <h3 class="specs-heading"><i class="fa-solid fa-sliders"></i> Thông số kỹ thuật</h3>
                <?php if (!empty($specs)): ?>
                    <table class="specs-table">
                        <tbody>
                            <?php foreach ($specs as $key => $val): ?>
                                <tr>
                                    <td class="spec-label"><?= ucfirst(str_replace('_', ' ', $key)) ?></td>
                                    <td class="spec-value"><strong><?= htmlspecialchars($val) ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="font-size: 13px; color: #666; padding: 10px;">Đang cập nhật thông số chi tiết của nhà sản xuất.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- SẢN PHẨM TƯƠNG TỰ LIÊN QUAN -->
    <?php if (!empty($relatedProducts)): ?>
        <section class="related-section my-5">
            <h2 class="section-heading"><i class="fa-solid fa-layer-group"></i> Sản Phẩm Tương Tự</h2>
            <div class="products-grid">
                <?php foreach ($relatedProducts as $rel): ?>
                    <div class="product-card">
                        <div class="prod-img-box">
                            <a href="<?= APP_URL ?>/index.php?act=product-detail&id=<?= $rel['id'] ?>">
                                <div class="prod-placeholder-img">
                                    <i class="fa-solid fa-bowl-rice"></i>
                                </div>
                            </a>
                        </div>
                        <h3 class="prod-name">
                            <a href="<?= APP_URL ?>/index.php?act=product-detail&id=<?= $rel['id'] ?>">
                                <?= htmlspecialchars($rel['name']) ?>
                            </a>
                        </h3>
                        <div class="prod-price-box">
                            <span class="sale-price"><?= formatCurrency($rel['sale_price'] ?? $rel['price']) ?></span>
                        </div>
                        <div class="prod-action-box">
                            <a href="<?= APP_URL ?>/index.php?act=cart_add&id=<?= $rel['id'] ?>" class="btn-buy-flash">
                                Chọn mua
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>
