<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Get all users.
     *
     * @return array<int, User>
     */
    public function all(): array;

    /**
     * Find a user by ID.
     *
     * @param string $id
     * @return User|null
     */
    public function find(string $id): ?User;

    /**
     * Find a user by username.
     *
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username): ?User;

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create a new user.
     *
     * @param array{
     *     username: string,
     *     email: string,
     *     employee_code?: string|null,
     *     password: string,
     *     role?: string
     * } $data
     */
    public function create(array $data): void;

    /**
     * Update an existing user.
     *
     * @param string $id
     * @param array{
     *     username?: string,
     *     email?: string,
     *     employee_code?: string|null,
     *     password?: string,
     *     role?: string
     * } $data
     */
    public function update(string $id, array $data): void;

    /**
     * Delete a user.
     *
     * @param string $id
     */
    public function delete(string $id): void;
}
