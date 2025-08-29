<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Request;
use App\Models\Vacation;
use App\Contracts\VacationRepositoryInterface;

class VacationService
{
    public function __construct(
        private VacationRepositoryInterface $vacationRepo
    ) {}

    /**
     * Get vacations for a user.
     */
    public function getVacationsForUser(string $userId): array
    {
        return $this->vacationRepo->findByUserId((int) $userId);
    }

    /**
     * Create a vacation after all validations.
     */
    public function createVacation(string $userId, array $data): void
    {
        validate_csrf($data['csrf_token'] ?? null);

        // check for valid date range
        if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
            $_SESSION['error'] = 'Start date must be before end date';
            redirect('/vacations/create');
        }

        // check for overlapping dates
        if ($this->vacationRepo->overlappingDates((int) $userId, $data['start_date'], $data['end_date'])) {
            $_SESSION['error'] = 'Vacation dates overlap with existing vacation';
            redirect('/vacations/create');
        }

        // check for exceeding vacation days
        $result = $this->vacationRepo->exceedingDays((int) $userId, $data['start_date'], $data['end_date']);
        if ($result['exceeding']) {
            $_SESSION['error'] = sprintf(
                'Limit for vacation days has been exceeded.<br>You have %d days left.',
                Vacation::TOTAL_VACATION_DAYS - $result['usedDays']
            );
            redirect('/vacations/create');
        }

        // finally, create vacation
        $this->vacationRepo->create($data);
    }

    /**
     * Delete a vacation.
     */
    public function deleteVacation(string $id, array $data): void
    {
        validate_csrf($data['csrf_token'] ?? null);
        
        $this->vacationRepo->delete($id);
    }
}
