<?php
/**
 * =======================================================================
 * QUẢN LÝ THÀNH VIÊN (VIEWS/ADMIN/PAGES/USERS/LIST.PHP)
 * =======================================================================
 */
?>
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-users"></i> Quản Lý Danh Sách Thành Viên</h3>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Ngày đăng ký</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>#<?= $u['id'] ?></td>
                            <td><strong><?= htmlspecialchars($u['fullname']) ?></strong></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
                            <td><small><?= htmlspecialchars($u['address'] ?? '-') ?></small></td>
                            <td>
                                <span class="badge <?= $u['role'] === 'admin' ? 'badge-primary' : 'badge-secondary' ?>">
                                    <?= strtoupper($u['role']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?= $u['status'] == 1 ? 'success' : 'danger' ?>">
                                    <?= $u['status'] == 1 ? 'Hoạt động' : 'Bị khóa' ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                            <td>
                                <?php if ($u['id'] !== (int)$_SESSION['user']['id']): ?>
                                    <?php if ($u['status'] == 1): ?>
                                        <a href="<?= APP_URL ?>/admin.php?act=user_toggle&id=<?= $u['id'] ?>&status=0" class="btn-action delete" title="Khóa tài khoản" onclick="return confirm('Bạn có chắc muốn khóa tài khoản này?');">
                                            <i class="fa-solid fa-lock"></i> Khóa
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= APP_URL ?>/admin.php?act=user_toggle&id=<?= $u['id'] ?>&status=1" class="btn-action view" title="Mở khóa tài khoản">
                                            <i class="fa-solid fa-lock-open"></i> Mở
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted"><i class="fa-solid fa-user-check"></i> Hiện tại</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
