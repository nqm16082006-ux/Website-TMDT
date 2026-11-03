<?php
/**
 * =======================================================================
 * FORM THÊM SẢN PHẨM MỚI (VIEWS/ADMIN/PAGES/PRODUCTS/ADD.PHP)
 * =======================================================================
 */
?>
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-plus-circle"></i> Thêm Sản Phẩm Mới</h3>
        <a href="<?= APP_URL ?>/admin.php?act=products" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <form action="<?= APP_URL ?>/admin.php?act=product_add" method="POST" class="admin-form">
        <div class="form-row-2">
            <div class="form-group">
                <label>Tên sản phẩm <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required placeholder="Ví dụ: Nồi cơm điện tử Kangaroo 1.8 lít">
            </div>
            <div class="form-group">
                <label>Danh mục sản phẩm <span class="text-danger">*</span></label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row-3">
            <div class="form-group">
                <label>Giá gốc (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control" required placeholder="2690000">
            </div>
            <div class="form-group">
                <label>Giá khuyến mãi / Flash Sale (VNĐ)</label>
                <input type="number" name="sale_price" class="form-control" placeholder="790000">
            </div>
            <div class="form-group">
                <label>Số lượng trong kho</label>
                <input type="number" name="quantity" class="form-control" value="50">
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>Quà tặng kèm theo</label>
                <input type="text" name="gift_info" class="form-control" placeholder="Phiếu mua hàng mua Pin tiểu trị giá 20.000đ">
            </div>
            <div class="form-group" style="display: flex; gap: 20px; align-items: center; margin-top: 25px;">
                <label style="cursor: pointer;">
                    <input type="checkbox" name="flash_sale" value="1"> <strong>Kích hoạt Flash Sale</strong>
                </label>
                <label style="cursor: pointer;">
                    <input type="checkbox" name="is_featured" value="1" checked> <strong>Sản phẩm nổi bật</strong>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label>Mô tả ngắn</label>
            <textarea name="short_description" class="form-control" rows="2" placeholder="Dung tích 1.8L phù hợp gia đình 4-6 người..."></textarea>
        </div>

        <div class="form-group">
            <label>Mô tả chi tiết</label>
            <textarea name="description" class="form-control" rows="5" placeholder="Thông số chi tiết sản phẩm..."></textarea>
        </div>

        <div class="form-actions mt-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu Sản Phẩm</button>
            <a href="<?= APP_URL ?>/admin.php?act=products" class="btn btn-secondary">Hủy bỏ</a>
        </div>
    </form>
</div>
