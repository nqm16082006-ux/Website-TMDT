<?php
/**
 * =======================================================================
 * TRANG TỔNG QUAN TÀI KHOẢN (VIEWS/CLIENT/PROFILE/DASHBOARD.PHP)
 * =======================================================================
 */
?>
<div class="container my-5">
    <div class="profile-layout">
        <!-- Sidebar tài khoản -->
        <div class="profile-sidebar">
            <div class="user-brief">
                <div class="user-avatar-circle">
                    <i class="fa-solid fa-user"></i>
                </div>
                <h4><?= htmlspecialchars($user['fullname']) ?></h4>
                <p><?= htmlspecialchars($user['email']) ?></p>
            </div>
            <ul class="profile-nav-menu">
                <li class="active"><a href="<?= APP_URL ?>/index.php?act=profile"><i class="fa-solid fa-gauge"></i> Tổng quan</a></li>
                <li><a href="<?= APP_URL ?>/index.php?act=profile_orders"><i class="fa-solid fa-box-archive"></i> Đơn hàng đã mua</a></li>
                <li><a href="<?= APP_URL ?>/index.php?act=profile_settings"><i class="fa-solid fa-gear"></i> Cài đặt tài khoản</a></li>
                <li><a href="<?= APP_URL ?>/index.php?act=logout" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
            </ul>
        </div>

        <!-- Nội dung tổng quan -->
        <div class="profile-main">
            <div class="profile-card">
                <h2>Xin chào, <?= htmlspecialchars($user['fullname']) ?>!</h2>
                <p>Từ trang tổng quan, bạn có thể dễ dàng quản lý thông tin cá nhân và kiểm tra trạng thái các đơn hàng gần đây.</p>

                <div class="profile-stats-grid">
                    <div class="p-stat-box">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <div class="stat-number"><?= count($orders) ?></div>
                        <div class="stat-title">Đơn hàng đã đặt</div>
                    </div>
                    <div class="p-stat-box gold">
                        <i class="fa-solid fa-crown"></i>
                        <div class="stat-number">3.950</div>
                        <div class="stat-title">Điểm Quà Tặng VIP</div>
                    </div>
                </div>

                <h3 class="mt-4 mb-3">Đơn hàng gần đây nhất:</h3>
                <?php if (!empty($orders)): ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Thanh toán</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($orders, 0, 3) as $ord): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($ord['order_code']) ?></strong></td>
                                        <td><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></td>
                                        <td class="text-danger font-bold"><?= formatCurrency($ord['total_amount']) ?></td>
                                        <td>
                                            <span class="badge <?= $ord['payment_status'] === 'paid' ? 'badge-success' : 'badge-warning' ?>">
                                                <?= $ord['payment_status'] === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?= htmlspecialchars($ord['status']) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p style="color: #666;">Bạn chưa có đơn hàng nào tại Điện máy XANH.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
