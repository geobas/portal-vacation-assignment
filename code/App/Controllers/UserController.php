<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Models\User;
use App\Models\Vacation;
use App\Exceptions\HttpException;

class UserController
{
    /**
     * @throws HttpException
     */
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

    /**
     * Display the list of users and their vacations.
     *
     * @return string
     */
    public function index(): string
    {
        $users = User::all();
        $vacations = Vacation::all();

        ob_start();
        include __DIR__ . './../Views/users/index.php';
        return ob_get_clean();
    }

    /**
     * Show the form to create a new user.
     *
     * @return string
     */
    public function create(): string
    {
        ob_start();
        include __DIR__ . './../Views/users/create.php';
        return ob_get_clean();
    }
    
    /**
     * Store a new user.
     *
     * @param Request $request
     * @return string
     */
    public function store(Request $request): string
    {
        $data = $request->getBody();
        $this->validateUserData($data);

        if (isset($_SESSION['error'])) {
            header('Location: /users/create');
            exit;
        }

        User::create($data);
        header('Location: /users');
        exit;
    }

    /**
     * Show the form to edit a user.
     *
     * @param Request $request
     * @param string $id
     * @return string
     */
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

    /**
     * Update a user.
     *
     * @param Request $request
     * @param string $id
     * @return string
     */
    public function update(Request $request, string $id): string
    {
        $data = $request->getBody();
        $this->validateUserData($data, (int) $id);

        if (isset($_SESSION['error'])) {
            header('Location: /users/' . $id . '/edit');
            exit;
        }

        User::update($id, $data);
        header('Location: /users');
        exit;
    }

    /**
     * Delete a user.
     *
     * @param Request $request
     * @param string $id
     * @return string
     */
    public function destroy(Request $request, string $id): string
    {
        User::delete($id);
        header('Location: /users');
        exit;
    }

    /**
     * Approve a vacation request.
     *
     * @param Request $request
     * @param string $id
     * @return string
     */
    public function approve(Request $request, string $id): string
    {
        Vacation::approve($id);
        header('Location: /users');
        exit;
    }

    /**
     * Reject a vacation request.
     *
     * @param Request $request
     * @param string $id
     * @return string
     */
    public function reject(Request $request, string $id): string
    {
        Vacation::reject($id);
        header('Location: /users');
        exit;
    }

    /**
     * Validate user data before creating or updating.
     *
     * @param array $data
     * @return void
     */
    private function validateUserData(array $data, ?int $excludeUserId = null): void
    {
        if (strlen($data['employee_code']) < 7) {
            $_SESSION['error'] = 'Employee code must be 7 characters long';
        }

        $existingEmailUser = User::findByEmail($data['email']);
        if (!empty($existingEmailUser) && (int) $existingEmailUser['id'] !== $excludeUserId) {
            $_SESSION['error'] = 'Email already in use';
        }

        $existingUsernameUser = User::findByUsername($data['username']);
        if (!empty($existingUsernameUser) && (int) $existingUsernameUser['id'] !== $excludeUserId) {
            $_SESSION['error'] = 'Username already in use';
        }
    }
}
