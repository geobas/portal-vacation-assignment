<?php

declare(strict_types=1);

namespace App\Contracts;

interface VacationRepositoryInterface
{
    public function all(): array;
    public function findByUserId(int $userId): array;
    public function create(array $data): void;
    public function delete(string $id): void;
    public function overlappingDates(int $userId, string $startDate, string $endDate): bool;
    public function exceedingDays(int $userId, string $startDate, string $endDate): array;
    public function approve(string $id): void;
    public function reject(string $id): void;
}
