<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\UserRepositoryInterface;
use App\Contracts\VacationRepositoryInterface;
use App\Models\User;
use App\Models\Vacation;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepo,
        private VacationRepositoryInterface $vacationRepo
    ) {
    }

    /**
     * Get all users and their vacations.
     *
     * @return array{
     *     users: array<int, User>,
     *     vacations: array<int, Vacation>
     * }
     */
    public function getUsersAndVacations(): array
    {
        return [
            'users' => $this->userRepo->all(),
            'vacations' => $this->vacationRepo->all(),
        ];
    }

    /**
     * Create a user after validation.
     *
     * @param array{
     *     username: string,
     *     email: string,
     *     employee_code?: string|null,
     *     password: string,
     *     role?: string,
     *     csrf_token?: string
     * } $data
     */
    public function createUser(array $data): void
    {
        validate_csrf($data['csrf_token'] ?? null);

        $this->validateUserData($data);

        if (isset($_SESSION['error'])) {
            redirect('/users/create');
        }

        $this->userRepo->create($data);
    }

    /**
     * Update a user after validation.
     *
     * @param array<string, string> $data
     */
    public function updateUser(string $id, array $data): void
    {
        validate_csrf($data['csrf_token'] ?? null);

        $this->validateUserData($data, (int)$id);

        if (isset($_SESSION['error'])) {
            redirect("/users/{$id}/edit");
        }

        $this->userRepo->update($id, $data);
    }

    /**
     * Delete a user.
     *
     * @param array<string, string> $data
     */
    public function deleteUser(string $id, array $data): void
    {
        validate_csrf($data['csrf_token'] ?? null);

        $this->userRepo->delete($id);
    }

    /**
     * Approve a vacation request.
     *
     * @param array<string, string> $data
     */
    public function approveVacation(string $id, array $data): void
    {
        validate_csrf($data['csrf_token'] ?? null);

        $this->vacationRepo->approve($id);
    }

    /**
     * Reject a vacation request.
     *
     * @param array<string, string> $data
     */
    public function rejectVacation(string $id, array $data): void
    {
        validate_csrf($data['csrf_token'] ?? null);

        $this->vacationRepo->reject($id);
    }

    /**
     * Validate user data before creating or updating.
     *
     * @param array<string, string> $data
     * @param int|null $excludeUserId
     */
    private function validateUserData(array $data, ?int $excludeUserId = null): void
    {
        if (strlen($data['employee_code']) < 7) {
            $_SESSION['error'] = 'Employee code must be 7 characters long';
        }

        $existingEmailUser = $this->userRepo->findByEmail($data['email']);
        if (!empty($existingEmailUser) && (int)$existingEmailUser->id !== $excludeUserId) {
            $_SESSION['error'] = 'Email already in use';
        }

        $existingUsernameUser = $this->userRepo->findByUsername($data['username']);
        if (!empty($existingUsernameUser) && (int)$existingUsernameUser->id !== $excludeUserId) {
            $_SESSION['error'] = 'Username already in use';
        }

        if (!empty($data['password'])) {
            $password = $data['password'];
            $errors = [];

            if (strlen($password) < 8) {
                $errors[] = 'Password must be at least 8 characters long.';
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $errors[] = 'Password must contain at least one uppercase letter.';
            }
            if (!preg_match('/[a-z]/', $password)) {
                $errors[] = 'Password must contain at least one lowercase letter.';
            }
            if (!preg_match('/[0-9]/', $password)) {
                $errors[] = 'Password must contain at least one number.';
            }
            if (!preg_match('/[\W_]/', $password)) { // special character
                $errors[] = 'Password must contain at least one special character.';
            }

            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
            }
        }
    }

    /**
     * Find a user by ID.
     */
    public function findUser(string $id): ?User
    {
        return $this->userRepo->find($id);
    }
}
