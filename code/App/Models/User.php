<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    public static function all(): array
    {
        $stmt = Database::connect()->query("SELECT * FROM users WHERE role = 'user'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(string $id): ?array
    {
        $stmt = Database::connect()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function findByUsername(string $username): ?array
    {
        $stmt = Database::connect()->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connect()->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(array $data): void
    {
        $stmt = Database::connect()->prepare("INSERT INTO users (username, email, employee_code, password, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['username'], $data['email'], $data['employee_code'], password_hash($data['password'], PASSWORD_DEFAULT), date('Y-m-d H:i:s')]);
    }

    public static function update(string $id, array $data): void
    {
        $db = Database::connect();

        if (!empty($data['password'])) {
            $stmt = $db->prepare("UPDATE users SET username = ?, email = ?, employee_code = ?, password = ?, updated_at = ? WHERE id = ?");
            $stmt->execute([$data['username'], $data['email'], $data['employee_code'], password_hash($data['password'], PASSWORD_DEFAULT), date('Y-m-d H:i:s'), $id]);
        } else {
            $stmt = $db->prepare("UPDATE users SET username = ?, email = ?, employee_code = ?, updated_at = ? WHERE id = ?");
            $stmt->execute([$data['username'], $data['email'], $data['employee_code'], date('Y-m-d H:i:s'), $id]);
        }
    }

    public static function delete(string $id): void
    {
        $stmt = Database::connect()->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
}
