<?php
/**
 * =======================================================================
 * DANH SÁCH SẢN PHẨM ADMIN (VIEWS/ADMIN/PAGES/PRODUCTS/LIST.PHP)
 * =======================================================================
 */
?>
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-boxes-stacked"></i> Danh Sách Sản Phẩm</h3>
        <a href="<?= APP_URL ?>/admin.php?act=product_add" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Thêm Sản Phẩm Mới
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá gốc</th>
                    <th>Giá KM</th>
                    <th>Flash Sale</th>
                    <th>Tồn kho</th>
                    <th>Đã bán</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>#<?= $p['id'] ?></td>
                            <td>
                                <div class="admin-prod-thumb">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($p['name']) ?></strong>
                                <small style="display:block; color:#777;">SKU: <?= htmlspecialchars($p['sku'] ?? 'N/A') ?></small>
                            </td>
                            <td><?= htmlspecialchars($p['category_name']) ?></td>
                            <td><?= formatCurrency($p['price']) ?></td>
                            <td class="text-danger font-bold">
                                <?= $p['sale_price'] ? formatCurrency($p['sale_price']) : '-' ?>
                            </td>
                            <td>
                                <span class="badge <?= $p['flash_sale'] ? 'badge-danger' : 'badge-secondary' ?>">
                                    <?= $p['flash_sale'] ? 'Có' : 'Không' ?>
                                </span>
                            </td>
                            <td><?= $p['quantity'] ?></td>
                            <td><?= $p['sold_count'] ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= APP_URL ?>/admin.php?act=product_edit&id=<?= $p['id'] ?>" class="btn-action edit" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/admin.php?act=product_delete&id=<?= $p['id'] ?>" class="btn-action delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center py-4">Chưa có sản phẩm nào. Hãy bấm thêm sản phẩm!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
