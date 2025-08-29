<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Exceptions\HttpException;
use App\Services\UserService;

class UserController
{
    /**
     * @throws HttpException
     */
    public function __construct(
        protected UserService $userService
    ) {
        if (!isset($_SESSION['user'])) {
            throw new HttpException('Unauthorized', 401);
        }

        if ($_SESSION['role'] !== 'manager') {
            redirect('/vacations');
        }
    }

    public function index(): string
    {
        $data = $this->userService->getUsersAndVacations();

        return view('users/index.php', $data);
    }

    public function create(): string
    {
        return view('users/create.php');
    }

    public function store(Request $request): void
    {
        $this->userService->createUser($request->getBody());
        redirect('/users');
    }

    public function edit(Request $request, string $id): string
    {
        $user = $this->userService->findUser($id);

        if (empty($user)) {
            $_SESSION['error'] = 'User not found';
            redirect('/users');
        }

        return view('users/edit.php', ['user' => $user]);
    }

    public function update(Request $request, string $id): void
    {
        $this->userService->updateUser($id, $request->getBody());
        redirect('/users');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->userService->deleteUser($id, $request->getBody());
        redirect('/users');
    }

    public function approve(Request $request, string $id): void
    {
        $this->userService->approveVacation($id, $request->getBody());
        redirect('/users');
    }

    public function reject(Request $request, string $id): void
    {
        $this->userService->rejectVacation($id, $request->getBody());
        redirect('/users');
    }
}
