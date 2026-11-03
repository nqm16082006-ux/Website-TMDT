<?php
/**
 * =======================================================================
 * CHI TIẾT & DUYỆT ĐƠN HÀNG (VIEWS/ADMIN/PAGES/ORDERS/DETAIL.PHP)
 * =======================================================================
 */
?>
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-file-invoice"></i> Chi Tiết Đơn Hàng #<?= htmlspecialchars($order['order_code']) ?></h3>
        <a href="<?= APP_URL ?>/admin.php?act=orders" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="order-detail-admin-grid">
        <!-- Thông tin khách hàng & Giao nhận -->
        <div class="order-info-box">
            <h4><i class="fa-solid fa-user"></i> Người nhận hàng:</h4>
            <p><strong>Họ tên:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email'] ?: 'Chưa cập nhật') ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['shipping_address']) ?></p>
            <p><strong>Hình thức giao:</strong> <?= $order['shipping_method'] === 'express' ? 'Giao siêu nhanh (10.000₫)' : 'Giao tiêu chuẩn (Miễn phí)' ?></p>
            <?php if (!empty($order['note'])): ?>
                <p><strong>Ghi chú:</strong> <em><?= htmlspecialchars($order['note']) ?></em></p>
            <?php endif; ?>
        </div>

        <!-- Cập nhật trạng thái duyệt đơn -->
        <div class="order-status-box">
            <h4><i class="fa-solid fa-pen-nib"></i> Cập nhật trạng thái</h4>
            <form action="<?= APP_URL ?>/admin.php?act=order_detail&id=<?= $order['id'] ?>" method="POST">
                <div class="form-group mb-3">
                    <label>Trạng thái đơn hàng</label>
                    <select name="status" class="form-control">
                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Chờ xác nhận (Pending)</option>
                        <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Đang xử lý (Processing)</option>
                        <option value="shipping" <?= $order['status'] === 'shipping' ? 'selected' : '' ?>>Đang giao hàng (Shipping)</option>
                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Đã hoàn thành (Completed)</option>
                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy (Cancelled)</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Trạng thái thanh toán</label>
                    <select name="payment_status" class="form-control">
                        <option value="pending" <?= $order['payment_status'] === 'pending' ? 'selected' : '' ?>>Chưa thanh toán (Pending)</option>
                        <option value="paid" <?= $order['payment_status'] === 'paid' ? 'selected' : '' ?>>Đã thanh toán (Paid)</option>
                        <option value="failed" <?= $order['payment_status'] === 'failed' ? 'selected' : '' ?>>Thất bại (Failed)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Cập nhật đơn hàng</button>
            </form>
        </div>
    </div>

    <h4 class="mt-4 mb-3"><i class="fa-solid fa-list-ol"></i> Các sản phẩm trong đơn hàng:</h4>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Quà tặng kèm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($it['product_name']) ?></strong></td>
                        <td><small class="text-success"><i class="fa-solid fa-gift"></i> <?= htmlspecialchars($it['gift_info']) ?></small></td>
                        <td><?= formatCurrency($it['price']) ?></td>
                        <td><?= $it['quantity'] ?></td>
                        <td class="text-danger font-bold"><?= formatCurrency($it['total']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Phí vận chuyển:</strong></td>
                    <td><strong><?= formatCurrency($order['shipping_fee']) ?></strong></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>TỔNG CỘNG:</strong></td>
                    <td class="text-danger" style="font-size: 18px; font-weight: 800;"><?= formatCurrency($order['total_amount']) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
