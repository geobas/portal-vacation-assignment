<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Models\User;
use App\Models\Vacation;
use App\Exceptions\HttpException;

class UserController
{
    public function __construct()
    {
        if (!isset($_SESSION['user'])) {
            throw new HttpException('Unauthorized', 401);
        }

        if ($_SESSION['role'] !== 'manager') {
            header('Location: /vacations');
            exit;
        }
    }

    public function index(): string
    {
        $users = User::all();
        $vacations = Vacation::all();

        ob_start();
        include __DIR__ . './../Views/users/index.php';
        return ob_get_clean();
    }

    public function create(): string
    {
        ob_start();
        include __DIR__ . './../Views/users/create.php';
        return ob_get_clean();
    }

    public function store(Request $request): string
    {
        $data = $request->getBody();
        $user = User::findByEmail($data['email']);        

        if ($user) {
            $_SESSION['error'] = 'Email already in use';
            header('Location: /users/create');
            exit;
        }        

        User::create($request->getBody());
        header('Location: /users');
        exit;
    }

    public function edit(Request $request, string $id): string
    {
        $user = User::find($id);

        if (empty($user)) {
            $_SESSION['error'] = 'User not found';
            header('Location: /users');
            exit;
        }

        ob_start();
        include __DIR__ . './../Views/users/edit.php';
        return ob_get_clean();
    }

    public function update(Request $request, string $id): string
    {
        User::update($id, $request->getBody());
        header('Location: /users');
        exit;
    }

    public function destroy(Request $request, string $id): string
    {
        User::delete($id);
        header('Location: /users');
        exit;
    }

    public function approve(Request $request, string $id): string
    {
        Vacation::approve($id);
        header('Location: /users');
        exit;
    }

    public function reject(Request $request, string $id): string
    {
        Vacation::reject($id);
        header('Location: /users');
        exit;
    }    
}
