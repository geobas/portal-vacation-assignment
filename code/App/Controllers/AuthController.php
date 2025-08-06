<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\User;

class AuthController
{
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
        unset($_SESSION['error']);
        header('Location: /users');
        exit;
    }

    public function logout(): string
    {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /');
        exit;
    }
}
