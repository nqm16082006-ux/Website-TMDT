<?php
/**
 * =======================================================================
 * TRANG TỔNG QUAN ADMIN (VIEWS/ADMIN/PAGES/DASHBOARD.PHP)
 * Thống kê doanh thu, số lượng đơn hàng, sản phẩm và khách hàng
 * =======================================================================
 */
?>
<div class="dashboard-stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fa-solid fa-coins"></i></div>
        <div class="stat-info">
            <span class="stat-label">Tổng doanh thu</span>
            <h3 class="stat-value"><?= formatCurrency($stats['total_revenue']) ?></h3>
        </div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon"><i class="fa-solid fa-clipboard-check"></i></div>
        <div class="stat-info">
            <span class="stat-label">Tổng đơn hàng</span>
            <h3 class="stat-value"><?= $stats['total_orders'] ?></h3>
        </div>
    </div>

    <div class="stat-card yellow">
        <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info">
            <span class="stat-label">Đơn chờ xử lý</span>
            <h3 class="stat-value"><?= $stats['pending_orders'] ?></h3>
        </div>
    </div>

    <div class="stat-card purple">
        <div class="stat-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
        <div class="stat-info">
            <span class="stat-label">Tổng sản phẩm</span>
            <h3 class="stat-value"><?= $stats['total_products'] ?></h3>
        </div>
    </div>
</div>

<div class="admin-card mt-4">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-receipt"></i> Đơn Hàng Mới Nhất</h3>
        <a href="<?= APP_URL ?>/admin.php?act=orders" class="btn btn-sm btn-outline">Xem tất cả đơn</a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Tổng tiền</th>
                    <th>Hình thức</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($stats['recent_orders'])): ?>
                    <?php foreach ($stats['recent_orders'] as $ord): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($ord['order_code']) ?></strong></td>
                            <td><?= htmlspecialchars($ord['customer_name']) ?></td>
                            <td><?= htmlspecialchars($ord['customer_phone']) ?></td>
                            <td class="text-danger font-bold"><?= formatCurrency($ord['total_amount']) ?></td>
                            <td><?= $ord['payment_method'] === 'bank_transfer' ? 'Chuyển khoản' : 'COD' ?></td>
                            <td>
                                <span class="badge badge-<?= $ord['status'] === 'completed' ? 'success' : 'warning' ?>">
                                    <?= htmlspecialchars($ord['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= APP_URL ?>/admin.php?act=order_detail&id=<?= $ord['id'] ?>" class="btn-action view">
                                    <i class="fa-solid fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">Chưa có đơn hàng nào trong hệ thống.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
