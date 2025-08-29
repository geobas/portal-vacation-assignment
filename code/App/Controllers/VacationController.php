<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Exceptions\HttpException;
use App\Contracts\UserRepositoryInterface;
use App\Services\VacationService;

class VacationController
{
    /**
     * @throws HttpException
     */
    public function __construct(
        protected VacationService $vacationService,
        protected UserRepositoryInterface $userRepo
    ) {
        if (!isset($_SESSION['user'])) {
            throw new HttpException('Unauthorized', 401);
        }

        if ($_SESSION['role'] !== 'user') {
            redirect('/users');
        }
    }

    public function index(): string
    {
        $userId = (string) $_SESSION['user'];
        $vacations = $this->vacationService->getVacationsForUser($userId);
        $username = $this->userRepo->find($userId)['username'];

        return view('vacations/index.php', [
            'vacations' => $vacations,
            'username' => $username
        ]);
    }

    public function create(): string
    {
        return view('vacations/create.php');
    }

    public function store(Request $request): void
    {
        $this->vacationService->createVacation((string)$_SESSION['user'], $request->getBody());
        redirect('/vacations');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->vacationService->deleteVacation($id, $request->getBody());
        redirect('/vacations');
    }
}
