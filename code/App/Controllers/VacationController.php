<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Models\User;
use App\Models\Vacation;
use App\Exceptions\HttpException;
use App\Contracts\VacationRepositoryInterface;
use App\Repositories\VacationRepository;

class VacationController
{
    private VacationRepositoryInterface $vacationRepo;
    
    /**
     * @throws HttpException
     */
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            throw new HttpException('Unauthorized', 401);
        }

        if ($_SESSION['role'] !== 'user') {
            header('Location: /users');
            exit;
        }

        $this->vacationRepo = new VacationRepository;
    }

    /**
     * Display the list of vacations for the logged-in user.
     *
     * @return string
     */
    public function index(): string
    {
        $vacations = $this->vacationRepo->findByUserId($_SESSION['user']);
        $username = User::find((string)$_SESSION['user'])['username'];

        ob_start();
        include __DIR__ . './../Views/vacations/index.php';
        return ob_get_clean();
    }

    /**
     * Show the form to create a new vacation.
     *
     * @return string
     */
    public function create(): string
    {
        ob_start();
        include __DIR__ . './../Views/vacations/create.php';
        return ob_get_clean();
    }

    /**
     * Store a new vacation.
     *
     * @param Request $request
     * @return string
     */
    public function store(Request $request): string
    {
        $data = $request->getBody();

        // check for valid date range
        if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
            $_SESSION['error'] = 'Start date must be before end date';
            header('Location: /vacations/create');
            exit;
        }

        // check for overlapping dates
        if ($this->vacationRepo->overlappingDates($_SESSION['user'], $data['start_date'], $data['end_date'])) {
            $_SESSION['error'] = 'Vacation dates overlap with existing vacation';
            header('Location: /vacations/create');
            exit;
        }

        // check for exceeding vacation days
        if (($result = $this->vacationRepo->exceedingDays($_SESSION['user'], $data['start_date'], $data['end_date']))['exceeding']) {
            $_SESSION['error'] = sprintf(
                'Limit for vacation days has been exceeded.<br>You have %d days left.',
                Vacation::TOTAL_VACATION_DAYS - $result['usedDays']
            );
            header('Location: /vacations/create');
            exit;
        }

        $this->vacationRepo->create($request->getBody());
        header('Location: /vacations');
        exit;
    }

    /**
     * Show the form to edit a vacation.
     *
     * @param string $id
     * @return string
     */
    public function destroy(Request $request, string $id): void
    {
        $this->vacationRepo->delete($id);
        header('Location: /vacations');
        exit;
    }
}