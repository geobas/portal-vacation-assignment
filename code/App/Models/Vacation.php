<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Enums\StatusEnum;
use DateTime;
use PDO;
use RuntimeException;

class Vacation
{
    public const TOTAL_VACATION_DAYS = 25;

    public int $id;
    public int $user_id;
    public string $start_date;
    public string $end_date;
    public string $reason;
    public ?string $submitted_at;
    public int $status_id;

    public ?User $user;
    public ?StatusEnum $status;

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

        if (isset($data['user_name'])) {
            $this->user = new User([
                'id' => $this->user_id,
                'username' => $data['user_name']
            ]);
        }

        if (isset($data['status_name'])) {
            $this->status = StatusEnum::fromName($data['status_name']);
        }
    }

    /**
     * @return array<int, self>
     */
    public function all(): array
    {
        $stmt = Database::connect()->query('
            SELECT vacations.*, statuses.name AS status_name, users.username AS user_name
            FROM vacations 
            INNER JOIN statuses ON vacations.status_id = statuses.id
            INNER JOIN users ON vacations.user_id = users.id
            ORDER BY vacations.submitted_at ASC
        ');

        if ($stmt === false) {
            throw new RuntimeException('Failed to execute query in Vacation::all()');
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn ($row) => new self($row), $rows);
    }

    /**
     * @param int $userId
     * @return array<int, self>
     */
    public function findByUserId(int $userId): ?array
    {
        $stmt = Database::connect()->prepare('
            SELECT vacations.*, statuses.name AS status_name
            FROM vacations
            INNER JOIN statuses ON vacations.status_id = statuses.id
            WHERE vacations.user_id = ?
            ORDER BY vacations.submitted_at ASC
        ');
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn ($row) => new self($row), $rows);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): void
    {
        $stmt = Database::connect()->prepare('INSERT INTO vacations (user_id, start_date, end_date, reason, submitted_at, status_id) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$_SESSION['user'], $data['start_date'], $data['end_date'], $data['reason'], date('Y-m-d H:i:s'), StatusEnum::PENDING->value]);
    }

    public function delete(string $id): void
    {
        $stmt = Database::connect()->prepare('DELETE FROM vacations WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function overlappingDates(int $userId, string $startDate, string $endDate): bool
    {
        $stmt = Database::connect()->prepare('
            SELECT COUNT(*) FROM vacations
            WHERE user_id = ?
            AND (
                start_date <= ? AND end_date >= ?
            )
        ');
        $stmt->execute([$userId, $endDate, $startDate]);

        return $stmt->fetchColumn() > 0;
    }


    /**
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @return array{exceeding: bool, usedDays: int}
     */
    public function exceedingDays(int $userId, string $startDate, string $endDate): array
    {
        $totalDays = (new DateTime($startDate))->diff(new DateTime($endDate))->days + 1;
        $vacations = self::findByUserId($userId);
        $usedDays = array_reduce($vacations, function ($temp, $v) {
            return $temp + ((new DateTime($v->start_date))->diff(new DateTime($v->end_date))->days + 1);
        }, 0);

        return [
            'exceeding' => ($usedDays + $totalDays) > self::TOTAL_VACATION_DAYS,
            'usedDays' => $usedDays,
        ];
    }

    public function approve(string $id): void
    {
        $stmt = Database::connect()->prepare('UPDATE vacations SET status_id = ' . StatusEnum::APPROVED->value . ' WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function reject(string $id): void
    {
        $stmt = Database::connect()->prepare('UPDATE vacations SET status_id = ' . StatusEnum::REJECTED->value . ' WHERE id = ?');
        $stmt->execute([$id]);
    }
}
