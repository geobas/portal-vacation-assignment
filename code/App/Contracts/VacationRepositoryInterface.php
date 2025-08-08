<?php

declare(strict_types=1);

namespace App\Contracts;

interface VacationRepositoryInterface
{
    /**
     * Retrieve all vacations.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Find vacations by user ID.
     *
     * @param int $userId
     * @return array
     */
    public function findByUserId(int $userId): array;

    /**
     * Create a new vacation.
     *
     * @param array $data
     * @return void
     */
    public function create(array $data): void;

    /**
     * Delete a vacation by ID.
     *
     * @param string $id
     * @return void
     */
    public function delete(string $id): void;

    /**
     * Check for overlapping vacation dates.
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @return bool
     */
    public function overlappingDates(int $userId, string $startDate, string $endDate): bool;

    /**
     * Check if vacation days exceed allowed limit.
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function exceedingDays(int $userId, string $startDate, string $endDate): array;
    
    /**
     * Approve a vacation request.
     *
     * @param string $id
     * @return void
     */
    public function approve(string $id): void;
    
    /**
     * Reject a vacation request.
     *
     * @param string $id
     * @return void
     */
    public function reject(string $id): void;
}
