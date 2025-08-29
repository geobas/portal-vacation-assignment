<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Services\AuthService;

class AuthController
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function loginForm(): string
    {
        if (isset($_SESSION['user'])) {
            redirect('/users');
        }

        return view('auth/login.php');
    }

    public function login(Request $request): void
    {
        $user = $this->authService->login($request->getBody());
        $this->authService->redirectDashboard($user);
    }

    public function logout(): void
    {
        $this->authService->logout();
    }
}
