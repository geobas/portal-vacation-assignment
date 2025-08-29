<?php

declare(strict_types=1);

namespace App\Contracts;

interface UserRepositoryInterface
{
    /**
     * Get all users.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Find a user by ID.
     *
     * @param string $id
     * @return array|null
     */
    public function find(string $id): ?array;

    /**
     * Find a user by username.
     *
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username): ?array;

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array;

    /**
     * Create a new user.
     *
     * @param array $data
     */
    public function create(array $data): void;

    /**
     * Update an existing user.
     *
     * @param string $id
     * @param array $data
     */
    public function update(string $id, array $data): void;

    /**
     * Delete a user.
     *
     * @param string $id
     */
    public function delete(string $id): void;
}
