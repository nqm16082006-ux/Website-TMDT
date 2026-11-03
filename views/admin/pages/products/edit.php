<?php
/**
 * =======================================================================
 * FORM CHỈNH SỬA SẢN PHẨM (VIEWS/ADMIN/PAGES/PRODUCTS/EDIT.PHP)
 * =======================================================================
 */
?>
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-pen-to-square"></i> Chỉnh Sửa Sản Phẩm: <?= htmlspecialchars($product['name']) ?></h3>
        <a href="<?= APP_URL ?>/admin.php?act=products" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <form action="<?= APP_URL ?>/admin.php?act=product_edit&id=<?= $product['id'] ?>" method="POST" class="admin-form">
        <div class="form-row-2">
            <div class="form-group">
                <label>Tên sản phẩm <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="form-group">
                <label>Danh mục sản phẩm <span class="text-danger">*</span></label>
                <select name="category_id" class="form-control" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row-3">
            <div class="form-group">
                <label>Giá gốc (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control" required value="<?= (int)$product['price'] ?>">
            </div>
            <div class="form-group">
                <label>Giá khuyến mãi / Flash Sale (VNĐ)</label>
                <input type="number" name="sale_price" class="form-control" value="<?= (int)$product['sale_price'] ?>">
            </div>
            <div class="form-group">
                <label>Số lượng tồn kho</label>
                <input type="number" name="quantity" class="form-control" value="<?= $product['quantity'] ?>">
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>Quà tặng kèm theo</label>
                <input type="text" name="gift_info" class="form-control" value="<?= htmlspecialchars($product['gift_info'] ?? '') ?>">
            </div>
            <div class="form-group" style="display: flex; gap: 20px; align-items: center; margin-top: 25px;">
                <label style="cursor: pointer;">
                    <input type="checkbox" name="flash_sale" value="1" <?= $product['flash_sale'] ? 'checked' : '' ?>> <strong>Kích hoạt Flash Sale</strong>
                </label>
                <label style="cursor: pointer;">
                    <input type="checkbox" name="is_featured" value="1" <?= $product['is_featured'] ? 'checked' : '' ?>> <strong>Sản phẩm nổi bật</strong>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label>Mô tả ngắn</label>
            <textarea name="short_description" class="form-control" rows="2"><?= htmlspecialchars($product['short_description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Mô tả chi tiết</label>
            <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
        </div>

        <div class="form-actions mt-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu Thay Đổi</button>
            <a href="<?= APP_URL ?>/admin.php?act=products" class="btn btn-secondary">Hủy bỏ</a>
        </div>
    </form>
</div>
