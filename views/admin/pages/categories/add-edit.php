<?php
/**
 * =======================================================================
 * THÊM / SỬA DANH MỤC (VIEWS/ADMIN/PAGES/CATEGORIES/ADD-EDIT.PHP)
 * =======================================================================
 */
?>
<div class="admin-card">
    <div class="admin-card-header">
        <h3><?= $id > 0 ? 'Chỉnh Sửa Danh Mục: ' . htmlspecialchars($category['name']) : 'Thêm Danh Mục Mới' ?></h3>
        <a href="<?= APP_URL ?>/admin.php?act=categories" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <form action="<?= APP_URL ?>/admin.php?act=<?= $id > 0 ? 'category_edit&id='.$id : 'category_add' ?>" method="POST" class="admin-form">
        <div class="form-row-2">
            <div class="form-group">
                <label>Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($category['name'] ?? '') ?>" placeholder="Ví dụ: Máy giặt">
            </div>
            <div class="form-group">
                <label>Slug URL (Để trống để tự tạo)</label>
                <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($category['slug'] ?? '') ?>" placeholder="may-giat">
            </div>
        </div>

        <div class="form-row-3">
            <div class="form-group">
                <label>Icon Class FontAwesome</label>
                <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($category['icon'] ?? '') ?>" placeholder="fa-solid fa-soap">
            </div>
            <div class="form-group">
                <label>Nhãn nổi bật (Badge)</label>
                <input type="text" name="badge" class="form-control" value="<?= htmlspecialchars($category['badge'] ?? '') ?>" placeholder="Ví dụ: HOT, 3.790k, -50%">
            </div>
            <div class="form-group">
                <label>Thứ tự hiển thị</label>
                <input type="number" name="sort_order" class="form-control" value="<?= $category['sort_order'] ?? 0 ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Mô tả danh mục</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
        </div>

        <div class="form-actions mt-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu Danh Mục</button>
            <a href="<?= APP_URL ?>/admin.php?act=categories" class="btn btn-secondary">Hủy bỏ</a>
        </div>
    </form>
</div>
