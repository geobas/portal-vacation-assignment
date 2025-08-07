<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Vacation;
use App\Contracts\VacationRepositoryInterface;

class VacationRepository implements VacationRepositoryInterface
{
    public function all(): array
    {
        return Vacation::all();
    }

    public function findByUserId(int $userId): array
    {
        return Vacation::findByUserId($userId) ?? [];
    }

    public function create(array $data): void
    {
        Vacation::create($data);
    }

    public function delete(string $id): void
    {
        Vacation::delete($id);
    }

    public function overlappingDates(int $userId, string $startDate, string $endDate): bool
    {
        return Vacation::overlappingDates($userId, $startDate, $endDate);
    }

    public function exceedingDays(int $userId, string $startDate, string $endDate): array
    {
        return Vacation::exceedingDays($userId, $startDate, $endDate);
    }

    public function approve(string $id): void
    {
        Vacation::approve($id);
    }

    public function reject(string $id): void
    {
        Vacation::reject($id);
    }
}
