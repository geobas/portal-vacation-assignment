<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\VacationRepositoryInterface;
use App\Models\Vacation;

class VacationRepository implements VacationRepositoryInterface
{
    public function __construct(
        protected Vacation $vacation = new Vacation(),
    ) {
    }

    public function all(): array
    {
        return $this->vacation->all();
    }

    public function findByUserId(int $userId): array
    {
        return $this->vacation->findByUserId($userId) ?? [];
    }

    public function create(array $data): void
    {
        $this->vacation->create($data);
    }

    public function delete(string $id): void
    {
        $this->vacation->delete($id);
    }

    public function overlappingDates(int $userId, string $startDate, string $endDate): bool
    {
        return $this->vacation->overlappingDates($userId, $startDate, $endDate);
    }

    public function exceedingDays(int $userId, string $startDate, string $endDate): array
    {
        return $this->vacation->exceedingDays($userId, $startDate, $endDate);
    }

    public function approve(string $id): void
    {
        $this->vacation->approve($id);
    }

    public function reject(string $id): void
    {
        $this->vacation->reject($id);
    }
}
