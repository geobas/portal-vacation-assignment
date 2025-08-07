<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\User;

class UserController
{
    public function __construct()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        if ($_SESSION['role'] !== 'manager') {
            header('Location: /vacations');
            exit;
        }
    }

    public function index(): string
    {
        $users = User::all();
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

    public function edit(Request $request, int $id): string
    {
        $user = User::find($id);
        ob_start();
        include __DIR__ . './../Views/users/edit.php';
        return ob_get_clean();
    }

    public function update(Request $request, int $id): string
    {
        User::update($id, $request->getBody());
        header('Location: /users');
        exit;
    }

    public function destroy(Request $request, int $id): string
    {
        User::delete($id);
        header('Location: /users');
        exit;
    }
}
