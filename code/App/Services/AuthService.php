<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class AuthService
{
    /**
     * Attempt to log in a user.
     *
     * @param array $data
     * @return array The logged-in user data
     */
    public function login(array $data): array
    {
        validate_csrf($data['csrf_token'] ?? null);

        $user = User::findByUsername($data['username']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            $_SESSION['error'] = 'Invalid credentials';
            redirect('/');
        }

        session_regenerate_id(true);

        $_SESSION['user'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        unset($_SESSION['error']);

        return $user;
    }

    /**
     * Log out the current user.
     */
    public function logout(): void
    {
        // Destroy the session
        if (session_id() !== '' || isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_unset();
        session_destroy();

        session_start();
        session_regenerate_id(true);

        redirect('/');
    }

    /**
     * Redirect a logged-in user to their dashboard.
     */
    public function redirectDashboard(array $user): void
    {
        if ($user['role'] === 'manager') {
            redirect('/users');
        } elseif ($user['role'] === 'user') {
            redirect('/vacations');
        }
    }
}
