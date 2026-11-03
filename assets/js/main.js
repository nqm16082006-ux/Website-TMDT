/**
 * =======================================================================
 * JAVASCRIPT CHÍNH PHÍA KHÁCH HÀNG (MAIN.JS)
 * Xử lý: Slider banner tự động chuyển động, đếm ngược Flash Sale,
 * tabs khuyến mãi, cập nhật giỏ hàng AJAX, checkout tương tác
 * =======================================================================
 */

document.addEventListener('DOMContentLoaded', function () {
    // -------------------------------------------------------------
    // 1. BANNER SLIDER TỰ ĐỘNG CHUYỂN ĐỘNG (Theo Yêu Cầu 6)
    // -------------------------------------------------------------
    const sliderWrapper = document.getElementById('heroSliderWrapper');
    if (sliderWrapper) {
        const slides = sliderWrapper.querySelectorAll('.slide-item');
        const dots = document.querySelectorAll('#sliderDots .dot');
        const prevBtn = document.getElementById('sliderPrev');
        const nextBtn = document.getElementById('sliderNext');
        let currentSlide = 0;
        let slideInterval;

        function showSlide(index) {
            slides.forEach((s) => s.classList.remove('active'));
            dots.forEach((d) => d.classList.remove('active'));

            currentSlide = (index + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
            if (dots[currentSlide]) {
                dots[currentSlide].classList.add('active');
            }
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function prevSlide() {
            showSlide(currentSlide - 1);
        }

        function startAutoSlide() {
            stopAutoSlide();
            slideInterval = setInterval(nextSlide, 4000); // 4 giây đổi banner 1 lần
        }

        function stopAutoSlide() {
            if (slideInterval) clearInterval(slideInterval);
        }

        if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); startAutoSlide(); });
        if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); startAutoSlide(); });

        dots.forEach((dot, idx) => {
            dot.addEventListener('click', () => {
                showSlide(idx);
                startAutoSlide();
            });
        });

        sliderWrapper.addEventListener('mouseenter', stopAutoSlide);
        sliderWrapper.addEventListener('mouseleave', startAutoSlide);

        startAutoSlide();
    }

    // -------------------------------------------------------------
    // 2. ĐẾM NGƯỢC FLASH SALE THỰC TẾ (Ảnh 2)
    // -------------------------------------------------------------
    const timerDisplay = document.getElementById('flashSaleTimer');
    if (timerDisplay) {
        // Thiết lập thời gian đếm ngược: 6 giờ 46 phút 15 giây
        let remainingSeconds = 6 * 3600 + 46 * 60 + 15;

        const countdownInterval = setInterval(() => {
            if (remainingSeconds <= 0) {
                remainingSeconds = 8 * 3600; // Reset lại slot mới
            } else {
                remainingSeconds--;
            }

            const h = Math.floor(remainingSeconds / 3600);
            const m = Math.floor((remainingSeconds % 3600) / 60);
            const s = remainingSeconds % 60;

            const hBox = timerDisplay.querySelector('.hours');
            const mBox = timerDisplay.querySelector('.minutes');
            const sBox = timerDisplay.querySelector('.seconds');

            if (hBox) hBox.textContent = String(h).padStart(2, '0');
            if (mBox) mBox.textContent = String(m).padStart(2, '0');
            if (sBox) sBox.textContent = String(s).padStart(2, '0');
        }, 1000);
    }

    // -------------------------------------------------------------
    // 3. TABS KHUYẾN MÃI ONLINE (Ảnh 2)
    // -------------------------------------------------------------
    const flashTabs = document.querySelectorAll('.flash-tab-btn');
    flashTabs.forEach((tab) => {
        tab.addEventListener('click', function () {
            flashTabs.forEach((t) => t.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // -------------------------------------------------------------
    // 4. CHỌN TẤT CẢ TRONG GIỎ HÀNG (Ảnh 3)
    // -------------------------------------------------------------
    const checkAll = document.getElementById('checkAllItems');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');

    if (checkAll && itemCheckboxes.length > 0) {
        checkAll.addEventListener('change', function () {
            itemCheckboxes.forEach((cb) => (cb.checked = checkAll.checked));
            recalculateCart();
        });

        itemCheckboxes.forEach((cb) => {
            cb.addEventListener('change', function () {
                const allChecked = Array.from(itemCheckboxes).every((c) => c.checked);
                checkAll.checked = allChecked;
                recalculateCart();
            });
        });
    }

    function recalculateCart() {
        let total = 0;
        let count = 0;
        itemCheckboxes.forEach((cb) => {
            if (cb.checked) {
                const id = cb.dataset.id;
                const price = parseFloat(cb.dataset.price) || 0;
                const qtyElem = document.getElementById(`qty-val-${id}`);
                const qty = qtyElem ? parseInt(qtyElem.textContent) : 1;
                total += price * qty;
                count++;
            }
        });

        const subtotalElem = document.getElementById('cartSummarySubtotal');
        const totalElem = document.getElementById('cartSummaryTotal');
        if (subtotalElem) subtotalElem.textContent = total.toLocaleString('vi-VN') + '₫';
        if (totalElem) totalElem.textContent = total.toLocaleString('vi-VN') + '₫';
    }
});

/**
 * Hàm cập nhật số lượng sản phẩm giỏ hàng bằng AJAX
 */
function updateCartQty(productId, newQty) {
    if (newQty < 1) {
        if (!confirm('Bạn có muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            return;
        }
    }

    const formData = new FormData();
    formData.append('id', productId);
    formData.append('quantity', newQty);

    fetch(`${APP_URL}/index.php?act=cart_update`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then((res) => res.json())
    .then((data) => {
        if (data.success) {
            if (newQty <= 0) {
                const itemRow = document.getElementById(`cart-item-${productId}`);
                if (itemRow) itemRow.remove();
            } else {
                const qtyElem = document.getElementById(`qty-val-${productId}`);
                if (qtyElem) qtyElem.textContent = newQty;
            }

            // Cập nhật số lượng giỏ hàng trên Header badge
            const badge = document.getElementById('cartCountBadge');
            if (badge) badge.textContent = data.cartCount;

            // Cập nhật tổng tiền giỏ hàng
            const subtotalElem = document.getElementById('cartSummarySubtotal');
            const totalElem = document.getElementById('cartSummaryTotal');
            if (subtotalElem) subtotalElem.textContent = data.cartTotal;
            if (totalElem) totalElem.textContent = data.cartTotal;
        }
    })
    .catch((err) => {
        // Fallback reload bình thường nếu gặp lỗi
        window.location.href = `${APP_URL}/index.php?act=cart_update&id=${productId}&quantity=${newQty}`;
    });
}
