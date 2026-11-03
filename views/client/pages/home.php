<?php
/**
 * =======================================================================
 * TRANG CHỦ WEBSITE (HOME.PHP)
 * Tái hiện 100% phong cách thiết kế Điện Máy XANH từ Ảnh 1 và Ảnh 2
 * Bao gồm: Banner chuyển động tự động, Khuyến mãi Flash Sale có countdown,
 * Lưới 16 icon danh mục có gắn nhãn giá sốc
 * =======================================================================
 */
?>

<!-- 1. KHU VỰC HERO BANNER VÀ MASCOT 2 BÊN (Theo ảnh 1) -->
<section class="hero-section">
    <!-- Mascot bên trái -->
    <div class="mascot-left">
        <div class="mascot-badge">
            <i class="fa-solid fa-umbrella"></i>
            <span>GIAO, LẮP, BẢO HÀNH TẬN TÌNH</span>
        </div>
    </div>

    <!-- Banner Slider Trung tâm -->
    <div class="hero-slider-container">
        <div class="slider-wrapper" id="heroSliderWrapper">
            <?php foreach ($banners as $index => $banner): ?>
                <div class="slide-item <?= $index === 0 ? 'active' : '' ?>" style="background: <?= $banner['bg_gradient'] ?>;">
                    <div class="slide-content">
                        <span class="slide-badge"><?= htmlspecialchars($banner['badge']) ?></span>
                        <h2 class="slide-title"><?= htmlspecialchars($banner['title']) ?></h2>
                        <h3 class="slide-subtitle"><?= htmlspecialchars($banner['subtitle']) ?></h3>
                        <div class="slide-price"><?= htmlspecialchars($banner['price_highlight']) ?></div>
                        <p class="slide-promo"><?= htmlspecialchars($banner['promo']) ?></p>
                        <a href="<?= $banner['link'] ?>" class="btn-slide-buy"><?= htmlspecialchars($banner['btn_text']) ?></a>
                    </div>
                    <div class="slide-graphic">
                        <i class="fa-solid fa-kitchen-set slide-icon-promo"></i>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Nút điều hướng Slider -->
        <button class="slider-arrow prev" id="sliderPrev"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="slider-arrow next" id="sliderNext"><i class="fa-solid fa-chevron-right"></i></button>

        <!-- Dấu chấm chỉ số slide -->
        <div class="slider-dots" id="sliderDots">
            <?php foreach ($banners as $index => $banner): ?>
                <span class="dot <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>"></span>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Mascot bên phải -->
    <div class="mascot-right">
        <div class="mascot-badge right-badge">
            <i class="fa-solid fa-award"></i>
            <span>VÀO LÀ VIỆC</span>
        </div>
    </div>
</section>

<div class="container">
    <!-- 2. THANH TICKER TÀI CHÍNH / MWG PAYLATER (Theo ảnh 1) -->
    <div class="financial-ticker-bar">
        <div class="ticker-item">
            <span class="ticker-badge"><i class="fa-solid fa-bolt"></i></span>
            <strong>MWG Paylater</strong> hạn mức đến <span class="highlight-blue">60 triệu</span>
        </div>
        <div class="ticker-bullet">•</div>
        <div class="ticker-item">
            <span class="ticker-badge black"><i class="fa-solid fa-credit-card"></i></span>
            <strong>Thẻ tín dụng VPBank MWG</strong> hạn mức đến <span class="highlight-blue">100 triệu</span>
        </div>
    </div>

    <!-- 3. LƯỚI ICON DANH MỤC 2 HÀNG CÓ GẮN NHÃN GIÁ (Theo ảnh 1) -->
    <section class="category-grid-section">
        <div class="category-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= APP_URL ?>/index.php?act=products&category=<?= $cat['id'] ?>" class="cat-card">
                    <?php if (!empty($cat['badge'])): ?>
                        <span class="cat-card-badge <?= $cat['badge'] === 'HOT' ? 'hot-badge' : '' ?>"><?= htmlspecialchars($cat['badge']) ?></span>
                    <?php endif; ?>
                    <div class="cat-icon-box">
                        <i class="<?= $cat['icon'] ?: 'fa-solid fa-boxes-stacked' ?>"></i>
                    </div>
                    <span class="cat-card-name"><?= htmlspecialchars($cat['name']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- 4. KHỐI KHUYẾN MÃI ONLINE & FLASH SALE (Theo ảnh 2) -->
    <section class="flash-sale-section">
        <div class="flash-sale-header">
            <div class="flash-title-wrapper">
                <i class="fa-solid fa-umbrella umbrella-icon"></i>
                <h2 class="flash-section-title">Khuyến mãi online</h2>
            </div>
            
            <!-- Tabs khuyến mãi -->
            <div class="flash-tabs-scroll">
                <button class="flash-tab-btn active"><i class="fa-solid fa-fire text-danger"></i> FLASH SALE GIÁ SỐC</button>
                <button class="flash-tab-btn">CHỈ CÓ DMX GIẢM ĐẾN 50%</button>
                <button class="flash-tab-btn">MÁY NƯỚC NÓNG GIẢM SỐC</button>
                <button class="flash-tab-btn">MÁY GIẶT GIẢM SÂU</button>
                <button class="flash-tab-btn">Nồi cơm</button>
                <button class="flash-tab-btn">Tivi</button>
                <button class="flash-tab-btn">Tủ lạnh</button>
                <button class="flash-tab-btn">Máy lạnh</button>
            </div>
        </div>

        <!-- Thanh đếm ngược thời gian Flash Sale (Ảnh 2) -->
        <div class="countdown-timeline-bar">
            <div class="active-countdown-box">
                <span class="countdown-label">Chỉ còn</span>
                <div class="timer-display" id="flashSaleTimer">
                    <span class="time-block hours">06</span> : 
                    <span class="time-block minutes">46</span> : 
                    <span class="time-block seconds">15</span>
                </div>
            </div>
            <div class="timeline-slot">Sắp diễn ra 09:00</div>
            <div class="timeline-slot">Sắp diễn ra 12:00</div>
            <div class="timeline-slot">Sắp diễn ra 15:00</div>
            <div class="timeline-slot">Sắp diễn ra 18:00</div>
        </div>

        <!-- Danh sách sản phẩm Flash Sale (Ảnh 2) -->
        <div class="products-grid flash-products-grid">
            <?php foreach ($flashSaleProducts as $prod): ?>
                <?php 
                    $specs = !empty($prod['specs']) ? json_decode($prod['specs'], true) : [];
                    $soldRate = min(100, round(($prod['flash_sale_sold'] / max(1, $prod['flash_sale_quota'])) * 100));
                ?>
                <div class="product-card">
                    <!-- Ảnh sản phẩm -->
                    <div class="prod-img-box">
                        <a href="<?= APP_URL ?>/index.php?act=product-detail&id=<?= $prod['id'] ?>">
                            <!-- SVG/Ảnh tượng trưng cho từng loại sản phẩm Điện Máy Xanh -->
                            <div class="prod-placeholder-img">
                                <?php if ($prod['category_id'] == 12): ?>
                                    <i class="fa-solid fa-bowl-rice"></i>
                                <?php elseif ($prod['category_id'] == 4): ?>
                                    <i class="fa-solid fa-tv"></i>
                                <?php elseif ($prod['category_id'] == 2): ?>
                                    <i class="fa-solid fa-temperature-arrow-down"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-blender"></i>
                                <?php endif; ?>
                            </div>
                        </a>
                        <!-- Icon thông số nhanh góc phải -->
                        <?php if (!empty($specs)): ?>
                            <div class="quick-specs-tag">
                                <?php $firstSpec = reset($specs); ?>
                                <span><?= htmlspecialchars($firstSpec) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Tên sản phẩm -->
                    <h3 class="prod-name">
                        <a href="<?= APP_URL ?>/index.php?act=product-detail&id=<?= $prod['id'] ?>">
                            <?= htmlspecialchars($prod['name']) ?>
                        </a>
                    </h3>

                    <!-- Giá bán & Giá gốc -->
                    <div class="prod-price-box">
                        <span class="sale-price"><?= formatCurrency($prod['sale_price'] ?? $prod['price']) ?></span>
                        <div class="old-price-row">
                            <span class="old-price"><?= formatCurrency($prod['price']) ?></span>
                            <span class="discount-percent">-<?= $prod['discount_percent'] ?>%</span>
                        </div>
                    </div>

                    <!-- Đánh giá sao & lượt bán -->
                    <div class="prod-rating-row">
                        <span class="star-rating"><i class="fa-solid fa-star"></i> <?= $prod['rating'] ?></span>
                        <span class="sold-count">• Đã bán <?= $prod['sold_count'] > 1000 ? round($prod['sold_count']/1000, 1) . 'k' : $prod['sold_count'] ?></span>
                    </div>

                    <!-- Thanh tiến độ suất Flash sale (Ảnh 2) -->
                    <div class="flash-progress-bar">
                        <div class="progress-fill" style="width: 100%;"></div>
                        <div class="progress-text">
                            <i class="fa-solid fa-fire-flame-curved"></i>
                            <?= $prod['flash_sale_sold'] ?>/<?= $prod['flash_sale_quota'] ?> Suất
                        </div>
                    </div>

                    <!-- Nút mua ngay -->
                    <div class="prod-action-box">
                        <a href="<?= APP_URL ?>/index.php?act=cart_add&id=<?= $prod['id'] ?>&quantity=1" class="btn-buy-flash">
                            Mua ngay
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- 5. DANH SÁCH SẢN PHẨM NỔI BẬT KHÁC -->
    <section class="featured-section my-5">
        <div class="section-title-bar">
            <h2 class="section-heading"><i class="fa-solid fa-award"></i> Gợi Ý Hôm Nay Cho Bạn</h2>
            <a href="<?= APP_URL ?>/index.php?act=products" class="view-all-link">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="products-grid">
            <?php foreach ($featuredProducts as $prod): ?>
                <div class="product-card">
                    <div class="prod-img-box">
                        <a href="<?= APP_URL ?>/index.php?act=product-detail&id=<?= $prod['id'] ?>">
                            <div class="prod-placeholder-img">
                                <i class="fa-solid fa-tv"></i>
                            </div>
                        </a>
                    </div>
                    <h3 class="prod-name">
                        <a href="<?= APP_URL ?>/index.php?act=product-detail&id=<?= $prod['id'] ?>">
                            <?= htmlspecialchars($prod['name']) ?>
                        </a>
                    </h3>
                    <div class="prod-price-box">
                        <span class="sale-price"><?= formatCurrency($prod['sale_price'] ?? $prod['price']) ?></span>
                        <?php if ($prod['sale_price'] && $prod['sale_price'] < $prod['price']): ?>
                            <div class="old-price-row">
                                <span class="old-price"><?= formatCurrency($prod['price']) ?></span>
                                <span class="discount-percent">-<?= $prod['discount_percent'] ?>%</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="prod-rating-row">
                        <span class="star-rating"><i class="fa-solid fa-star"></i> <?= $prod['rating'] ?></span>
                        <span class="sold-count">• Đã bán <?= $prod['sold_count'] ?></span>
                    </div>
                    <div class="prod-action-box">
                        <a href="<?= APP_URL ?>/index.php?act=cart_add&id=<?= $prod['id'] ?>" class="btn-buy-flash">
                            <i class="fa-solid fa-cart-plus"></i> Chọn mua
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
