<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Vacation
{
    public static function findByUserId(int $userId): ?array
    {
        $stmt = Database::connect()->prepare("
            SELECT vacations.*, statuses.name AS status_name
            FROM vacations
            INNER JOIN statuses ON vacations.status_id = statuses.id
            WHERE vacations.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(array $data): void
    {
        $stmt = Database::connect()->prepare("INSERT INTO vacations (user_id, start_date, end_date, reason, submitted_at, status_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user'], $data['start_date'], $data['end_date'], $data['reason'], date('Y-m-d H:i:s'), 3]);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::connect()->prepare("DELETE FROM vacations WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function overlappingDates(int $userId, string $startDate, string $endDate): bool
    {
        $stmt = Database::connect()->prepare("
            SELECT COUNT(*) FROM vacations
            WHERE user_id = ?
            AND (
                start_date <= ? AND end_date >= ?
            )
        ");
        $stmt->execute([$userId, $endDate, $startDate]);
        return $stmt->fetchColumn() > 0;
    }
}