<?php
/**
 * =======================================================================
 * TRANG LỊCH SỬ ĐƠN HÀNG (VIEWS/CLIENT/PROFILE/ORDERS.PHP)
 * =======================================================================
 */
?>
<div class="container my-5">
    <div class="profile-layout">
        <!-- Sidebar tài khoản -->
        <div class="profile-sidebar">
            <div class="user-brief">
                <div class="user-avatar-circle"><i class="fa-solid fa-user"></i></div>
                <h4><?= htmlspecialchars($_SESSION['user']['fullname']) ?></h4>
                <p><?= htmlspecialchars($_SESSION['user']['email']) ?></p>
            </div>
            <ul class="profile-nav-menu">
                <li><a href="<?= APP_URL ?>/index.php?act=profile"><i class="fa-solid fa-gauge"></i> Tổng quan</a></li>
                <li class="active"><a href="<?= APP_URL ?>/index.php?act=profile_orders"><i class="fa-solid fa-box-archive"></i> Đơn hàng đã mua</a></li>
                <li><a href="<?= APP_URL ?>/index.php?act=profile_settings"><i class="fa-solid fa-gear"></i> Cài đặt tài khoản</a></li>
                <li><a href="<?= APP_URL ?>/index.php?act=logout" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
            </ul>
        </div>

        <div class="profile-main">
            <div class="profile-card">
                <h2>Lịch Sử Đơn Hàng Của Bạn</h2>
                <?php if (!empty($orders)): ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Người nhận</th>
                                    <th>Địa chỉ</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $ord): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($ord['order_code']) ?></strong></td>
                                        <td><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></td>
                                        <td><?= htmlspecialchars($ord['customer_name']) ?></td>
                                        <td><small><?= htmlspecialchars($ord['shipping_address']) ?></small></td>
                                        <td class="text-danger font-bold"><?= formatCurrency($ord['total_amount']) ?></td>
                                        <td>
                                            <span class="badge badge-info">
                                                <?= ($ord['status'] === 'pending') ? 'Chờ xác nhận' : (($ord['status'] === 'completed') ? 'Hoàn thành' : 'Đang xử lý') ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-receipt" style="font-size: 50px; color: #ccc;"></i>
                        <h4 class="mt-3">Bạn chưa có đơn hàng nào</h4>
                        <a href="<?= APP_URL ?>" class="btn-checkout-primary mt-2" style="display:inline-block; max-width:200px;">Mua sắm ngay</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
