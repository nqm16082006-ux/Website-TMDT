<?php
/**
 * =======================================================================
 * QUẢN LÝ DANH MỤC ADMIN (VIEWS/ADMIN/PAGES/CATEGORIES/LIST.PHP)
 * =======================================================================
 */
?>
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-list-check"></i> Quản Lý Danh Mục Sản Phẩm</h3>
        <a href="<?= APP_URL ?>/admin.php?act=category_add" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Thêm Danh Mục Mới
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Thứ tự</th>
                    <th>Icon</th>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Nhãn (Badge)</th>
                    <th>Số sản phẩm</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= $cat['sort_order'] ?></td>
                            <td><i class="<?= $cat['icon'] ?: 'fa-solid fa-folder' ?>" style="font-size: 18px; color: #0071e3;"></i></td>
                            <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                            <td><code><?= htmlspecialchars($cat['slug']) ?></code></td>
                            <td>
                                <?php if ($cat['badge']): ?>
                                    <span class="badge badge-warning"><?= htmlspecialchars($cat['badge']) ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><strong><?= $cat['product_count'] ?? 0 ?></strong></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= APP_URL ?>/admin.php?act=category_edit&id=<?= $cat['id'] ?>" class="btn-action edit" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/admin.php?act=category_delete&id=<?= $cat['id'] ?>" class="btn-action delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa danh mục này? Các sản phẩm thuộc danh mục sẽ bị ảnh hưởng!');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
