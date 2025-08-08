<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Models\User;

class AuthController
{
    /**
     * Render the login form.
     *
     * @return string
     */
    public function loginForm(): string
    {
        if (isset($_SESSION['user'])) {
            header('Location: /users');
            exit;
        }

        ob_start();
        include __DIR__ . '../../Views/auth/login.php';
        return ob_get_clean();
    }

    /**
     * Handle the login request.
     *
     * @param Request $request
     * @return string
     */
    public function login(Request $request): string
    {
        $data = $request->getBody();
        
        $user = User::findByUsername($data['username']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            $_SESSION['error'] = 'Invalid credentials';
            header('Location: /');
            exit;
        }

        $_SESSION['user'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        unset($_SESSION['error']);

        if ($user['role'] === 'manager') {
            header('Location: /users');
        }

        if ($user['role'] === 'user') {
            header('Location: /vacations');
        }
        
        exit;
    }

    public function logout(): string
    {
        unset($_SESSION['user'], $_SESSION['role']);
        session_destroy();
        header('Location: /');
        exit;
    }
}
