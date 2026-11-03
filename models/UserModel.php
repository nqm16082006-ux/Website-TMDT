<?php
/**
 * =======================================================================
 * LỚP MODEL QUẢN LÝ NGƯỜI DÙNG & TÀI KHOẢN (USERMODEL.PHP)
 * =======================================================================
 */

require_once ROOT_PATH . '/models/Database.php';

class UserModel {
    /**
     * Tìm người dùng theo Email
     */
    public static function findByEmail(string $email): ?array {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        return Database::fetchOne($sql, ['email' => $email]);
    }

    /**
     * Tìm người dùng theo ID
     */
    public static function findById(int $id): ?array {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        return Database::fetchOne($sql, ['id' => $id]);
    }

    /**
     * Tạo tài khoản mới
     */
    public static function create(array $data): int {
        $sql = "INSERT INTO users (fullname, email, phone, password, address, role, status) 
                VALUES (:fullname, :email, :phone, :password, :address, :role, :status)";
        Database::execute($sql, [
            'fullname' => $data['fullname'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'address'  => $data['address'] ?? null,
            'role'     => $data['role'] ?? 'user',
            'status'   => $data['status'] ?? 1
        ]);
        return (int)Database::lastInsertId();
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public static function updateProfile(int $id, array $data): bool {
        $sql = "UPDATE users SET fullname = :fullname, phone = :phone, address = :address WHERE id = :id";
        return Database::execute($sql, [
            'id'       => $id,
            'fullname' => $data['fullname'],
            'phone'    => $data['phone'] ?? null,
            'address'  => $data['address'] ?? null
        ]) >= 0;
    }

    /**
     * Đổi mật khẩu
     */
    public static function updatePassword(int $id, string $newPassword): bool {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        return Database::execute($sql, [
            'id'       => $id,
            'password' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]) > 0;
    }

    /**
     * Lấy danh sách thành viên cho trang Admin
     */
    public static function getAll(): array {
        $sql = "SELECT id, fullname, email, phone, address, role, status, created_at FROM users ORDER BY id DESC";
        return Database::fetchAll($sql);
    }

    /**
     * Khóa hoặc mở khóa tài khoản
     */
    public static function toggleStatus(int $id, int $status): bool {
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        return Database::execute($sql, ['id' => $id, 'status' => $status]) > 0;
    }
}
