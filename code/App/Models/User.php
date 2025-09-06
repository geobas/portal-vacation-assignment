<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use RuntimeException;

class User
{
    public int $id;
    public string $username;
    public string $email;
    public ?string $employee_code;
    public string $password;
    public string $role;
    public ?string $created_at;
    public ?string $updated_at;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return array<int, self>
     */
    public function all(): array
    {
        $stmt = Database::connect()->query("SELECT id, username, email, employee_code, role, created_at, updated_at FROM users WHERE role = 'user'");

        if ($stmt === false) {
            throw new RuntimeException('Failed to execute query in User::all()');
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn ($row) => new self($row), $rows);
    }

    public function find(string $id): ?self
    {
        $stmt = Database::connect()->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new self($row) : null;
    }

    public function findByUsername(string $username): ?self
    {
        $stmt = Database::connect()->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new self($row) : null;
    }

    public function findByEmail(string $email): ?self
    {
        $stmt = Database::connect()->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new self($row) : null;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): void
    {
        $stmt = Database::connect()->prepare('INSERT INTO users (username, email, employee_code, password, created_at) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$data['username'], $data['email'], $data['employee_code'], password_hash($data['password'], PASSWORD_DEFAULT), date('Y-m-d H:i:s')]);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(string $id, array $data): void
    {
        $db = Database::connect();

        if (!empty($data['password'])) {
            $stmt = $db->prepare('UPDATE users SET username = ?, email = ?, employee_code = ?, password = ?, updated_at = ? WHERE id = ?');
            $stmt->execute([$data['username'], $data['email'], $data['employee_code'], password_hash($data['password'], PASSWORD_DEFAULT), date('Y-m-d H:i:s'), $id]);
        } else {
            $stmt = $db->prepare('UPDATE users SET username = ?, email = ?, employee_code = ?, updated_at = ? WHERE id = ?');
            $stmt->execute([$data['username'], $data['email'], $data['employee_code'], date('Y-m-d H:i:s'), $id]);
        }
    }

    public function delete(string $id): void
    {
        $stmt = Database::connect()->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
    }
}
