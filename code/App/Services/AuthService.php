<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\UserRepositoryInterface;
use App\Exceptions\HttpException;
use App\Models\User;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepo,
    ) {
    }

    /**
     * Attempt to log in a user.
     *
    * @param array<string, string> $data Associative array: 'username', 'password', 'csrf_token'
    * @return User
     */
    public function login(array $data): User
    {
        validate_csrf($data['csrf_token'] ?? null);

        $user = $this->userRepo->findByUsername($data['username']);

        if (!$user || !password_verify($data['password'], $user->password)) {
            $_SESSION['error'] = 'Invalid credentials';
            redirect('/');
        }

        session_regenerate_id(true);

        $_SESSION['user'] = $user->id;
        $_SESSION['role'] = $user->role;
        unset($_SESSION['error']);

        return $user;
    }

    /**
     * Log out the current user.
     */
    public function logout(): void
    {
        // Destroy the session
        $sessionName = session_name();
        if ($sessionName !== false && (session_id() !== '' || isset($_COOKIE[$sessionName]))) {
            setcookie($sessionName, '', time() - 3600, '/');
        }

        session_unset();
        session_destroy();

        session_start();
        session_regenerate_id(true);

        redirect('/');
    }

    /**
     * Redirect a logged-in user to their dashboard.
     *
     * @param User $user
     * @return void
     */
    public function redirectDashboard(User $user): void
    {
        if ($user->role === 'manager') {
            redirect('/users');
        } elseif ($user->role === 'user') {
            redirect('/vacations');
        }
    }

    /**
     * Redirect to appropriate dashboard if user is already logged in.
     *
     * @return void
     */
    public function redirectIfLoggedIn(): void
    {
        if (isset($_SESSION['user'])) {
            $role = $_SESSION['role'];

            if ($role === 'manager') {
                redirect('/users');
            } elseif ($role === 'user') {
                redirect('/vacations');
            }
        }
    }

    /**
     * Require a user to be logged in, optionally enforcing a role.
     *
     * @param string|null $role 'user' or 'manager'
     * @return void
     * @throws HttpException
     */
    public function requireRole(?string $role = null): void
    {
        if (!isset($_SESSION['user'])) {
            throw new HttpException('Unauthorized', 401);
        }

        $userId = $_SESSION['user'];
        $user = $this->userRepo->find((string) $userId);

        if (!$user) {
            throw new HttpException('Unauthorized', 401);
        }

        if ($role !== null && $user->role !== $role) {
            // Redirect based on actual role
            $this->redirectDashboard($user);
        }
    }
}
