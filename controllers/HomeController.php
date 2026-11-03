<?php
/**
 * =======================================================================
 * CONTROLLER TRANG CHỦ (HOMECONTROLLER.PHP)
 * Phụ trách lấy danh mục, banner, sản phẩm flash sale & sản phẩm nổi bật
 * =======================================================================
 */

require_once ROOT_PATH . '/models/CategoryModel.php';
require_once ROOT_PATH . '/models/ProductModel.php';

class HomeController {
    /**
     * Hiển thị trang chủ chính của website
     */
    public function index() {
        // 1. Lấy danh sách tất cả danh mục đang hoạt động (Hiển thị lưới icon danh mục ở ảnh 1)
        $categories = CategoryModel::getAllActive();

        // 2. Lấy danh sách sản phẩm Flash Sale đặc biệt (Hiển thị ở ảnh 2)
        $flashSaleProducts = ProductModel::getFlashSaleProducts(6);

        // 3. Lấy danh sách sản phẩm nổi bật
        $featuredProducts = ProductModel::getFeaturedProducts(8);

        // 4. Dữ liệu các banner quảng cáo tự động chuyển động
        $banners = [
            [
                'title' => 'TUẦN LỄ RA MẮT DÒNG PRO MỚI',
                'subtitle' => 'MÁY RỬA CHÉN JUNGER CHỈ CÓ TẠI ĐIỆN MÁY XANH',
                'price_highlight' => 'GIÁ CHỈ TỪ 14.490.000Đ',
                'promo' => 'GIẢM THÊM 500.000Đ (*) + TẶNG BẾP ĐƠN HỒNG NGOẠI TRỊ GIÁ 3.490.000Đ',
                'bg_gradient' => 'linear-gradient(90deg, #0b1325 0%, #1a2a4b 50%, #0c1b33 100%)',
                'badge' => 'MỚI RA MẮT',
                'btn_text' => 'MUA NGAY',
                'link' => APP_URL . '/index.php?act=products&category=5'
            ],
            [
                'title' => 'LỄ HỘI ĐIỆN MÁY - ĐẠI TIỆC NỒI CƠM',
                'subtitle' => 'NỒI CƠM ĐIỆN TỬ GIẢM SỐC ĐẾN 70%',
                'price_highlight' => 'CHỈ TỪ 790.000Đ',
                'promo' => 'TẶNG PHIẾU MUA HÀNG PIN TIỂU 20.000Đ + TRẢ GÓP 0% LÃI SUẤT',
                'bg_gradient' => 'linear-gradient(90deg, #004d9c 0%, #0071e3 50%, #002e60 100%)',
                'badge' => 'FLASH SALE',
                'btn_text' => 'SẮM NGAY',
                'link' => APP_URL . '/index.php?act=products&category=12'
            ],
            [
                'title' => 'TIVI SAMSUNG 4K - RỰC RỠ SẮC MÀU',
                'subtitle' => 'ÂM THANH ĐỈNH CAO Q-SYMPHONY',
                'price_highlight' => 'GIÁ SỐC CHỈ TỪ 8.990.000Đ',
                'promo' => 'MIỄN PHÍ CÔNG LẮP ĐẶT TẬN NHÀ + BẢO HÀNH CHÍNH HÃNG 2 NĂM',
                'bg_gradient' => 'linear-gradient(90deg, #1f1c2c 0%, #928DAB 100%)',
                'badge' => 'BÁN CHẠY NHẤT',
                'btn_text' => 'XEM NGAY',
                'link' => APP_URL . '/index.php?act=products&category=4'
            ]
        ];

        // 5. Nạp view trang chủ
        $pageTitle = "Điện máy XANH - Mua bán điện tử, điện lạnh, gia dụng giá rẻ nhất";
        require_once VIEW_PATH . '/client/layouts/header.php';
        require_once VIEW_PATH . '/client/pages/home.php';
        require_once VIEW_PATH . '/client/layouts/footer.php';
    }
}
