<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\Vacation;

class VacationController
{
    public function __construct()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        if ($_SESSION['role'] !== 'user') {
            header('Location: /users');
            exit;
        }        
    }

    public function index(): string
    {
        $vacations = Vacation::findByUserId($_SESSION['user']);
        include __DIR__ . './../Views/vacations/index.php';
        return ob_get_clean();
    }

    public function create(): string
    {
        ob_start();
        include __DIR__ . './../Views/vacations/create.php';
        return ob_get_clean();
    }

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
        if (Vacation::overlappingDates($_SESSION['user'], $data['start_date'], $data['end_date'])) {
            $_SESSION['error'] = 'Vacation dates overlap with existing vacation';
            header('Location: /vacations/create');
            exit;
        }

        Vacation::create($request->getBody());
        header('Location: /vacations');
        exit;
    }

    public function destroy(Request $request, int $id): string
    {
        Vacation::delete($id);
        header('Location: /vacations');
        exit;
    }    
}