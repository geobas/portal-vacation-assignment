<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\UserRepositoryInterface;
use App\Core\Request;
use App\Exceptions\HttpException;
use App\Services\AuthService;
use App\Services\VacationService;

class VacationController
{
    /**
     * @throws HttpException
     */
    public function __construct(
        protected VacationService $vacationService,
        protected UserRepositoryInterface $userRepo,
        protected AuthService $authService,
    ) {
        $this->authService->requireRole('user');
    }

    public function index(): string
    {
        $userId = (string) $_SESSION['user'];
        $vacations = $this->vacationService->getVacationsForUser($userId);
        $username = $this->userRepo->find($userId)->username;

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
        $body = $request->getBody();

        $data = [
            'start_date'   => (string)($body['start_date'] ?? ''),
            'end_date'     => (string)($body['end_date'] ?? ''),
            'reason'       => (string)($body['reason'] ?? ''),
            'csrf_token'   => $body['csrf_token'] ?? null,
            'submitted_at' => $body['submitted_at'] ?? null,
            'status_id'    => isset($body['status_id']) ? (int)$body['status_id'] : null,
        ];

        $this->vacationService->createVacation((string) $_SESSION['user'], $data);
        redirect('/vacations');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->vacationService->deleteVacation($id, $request->getBody());
        redirect('/vacations');
    }
}
