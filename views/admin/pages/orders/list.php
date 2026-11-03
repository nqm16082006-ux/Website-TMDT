<?php
/**
 * =======================================================================
 * DANH SÁCH ĐƠN HÀNG ADMIN (VIEWS/ADMIN/PAGES/ORDERS/LIST.PHP)
 * =======================================================================
 */
?>
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-cart-flatbed"></i> Quản Lý Danh Sách Đơn Hàng</h3>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày tạo</th>
                    <th>Tên khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Tổng tiền</th>
                    <th>Hình thức TT</th>
                    <th>Trạng thái TT</th>
                    <th>Trạng thái đơn</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $ord): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($ord['order_code']) ?></strong></td>
                            <td><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></td>
                            <td><?= htmlspecialchars($ord['customer_name']) ?></td>
                            <td><?= htmlspecialchars($ord['customer_phone']) ?></td>
                            <td class="text-danger font-bold"><?= formatCurrency($ord['total_amount']) ?></td>
                            <td><?= $ord['payment_method'] === 'bank_transfer' ? 'Chuyển khoản' : 'COD' ?></td>
                            <td>
                                <span class="badge badge-<?= $ord['payment_status'] === 'paid' ? 'success' : 'warning' ?>">
                                    <?= $ord['payment_status'] === 'paid' ? 'Đã TT' : 'Chưa TT' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?= $ord['status'] === 'completed' ? 'success' : ($ord['status'] === 'pending' ? 'warning' : 'info') ?>">
                                    <?= htmlspecialchars($ord['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= APP_URL ?>/admin.php?act=order_detail&id=<?= $ord['id'] ?>" class="btn-action view" title="Xem chi tiết & duyệt đơn">
                                    <i class="fa-solid fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-4">Chưa có đơn hàng nào trong hệ thống.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
