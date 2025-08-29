<?php

declare(strict_types=1);

namespace Tests;

use App\Core\Database;
use App\Enums\StatusEnum;
use App\Models\User;
use App\Models\Vacation;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class VacationTest extends TestCase
{
    private array $userData;
    private array $vacationData;
    protected static \PDO $db;
    protected \PDO $connection;

    public static function setUpBeforeClass(): void
    {
        self::$db = Database::connect();
    }

    protected function setUp(): void
    {
        self::$db->beginTransaction();

        // $db->exec("DELETE FROM vacations");

        $this->userData = [
            'username' => 'akis',
            'email' => 'akis@testakis.com',
            'employee_code' => '1234567',
            'password' => 'pass123',
            'role' => 'user',
        ];

        $this->vacationData = [
            'start_date' => '2025-08-11',
            'end_date' => '2025-08-22',
            'reason' => 'Summer Vacation',
        ];

        User::create($this->userData);

        // Simulate user login by setting session variable
        $_SESSION['user'] = (int) User::findByEmail($this->userData['email'])['id'];
    }

    protected function tearDown(): void
    {
        if (self::$db->inTransaction()) {
            self::$db->rollBack();
        }
    }

    #[Test]
    public function create_and_find_vacation(): void
    {
        Vacation::create($this->vacationData);

        $vacations = Vacation::findByUserId($_SESSION['user']);
        $this->assertCount(1, $vacations);
        $this->assertSame($this->vacationData['start_date'], $vacations[0]['start_date']);
        $this->assertSame($this->vacationData['reason'], $vacations[0]['reason']);
        $this->assertSame(StatusEnum::PENDING->value, (int)$vacations[0]['status_id']);
    }

    #[Test]
    public function overlapping_dates_detected(): void
    {
        Vacation::create($this->vacationData);

        // Overlapping
        $result = Vacation::overlappingDates($_SESSION['user'], '2025-08-04', '2025-08-14');
        $this->assertTrue($result);

        // Not overlapping
        $result = Vacation::overlappingDates($_SESSION['user'], '2025-08-06', '2025-08-10');
        $this->assertFalse($result);
    }

    #[Test]
    public function exceeding_days_calculation(): void
    {
        // First vacation 5 days
        Vacation::create([
            'start_date' => '2025-08-01',
            'end_date' => '2025-08-05',
            'reason' => 'Summer Vacation',
        ]);

        $check = Vacation::exceedingDays($_SESSION['user'], '2025-08-10', '2025-08-20'); // 11 days

        // Add 11 days, Total days 16, less than limit
        $this->assertFalse($check['exceeding']);
        $this->assertEquals(5, $check['usedDays']);

        // Add 32 days, exceeding the limit
        $check = Vacation::exceedingDays($_SESSION['user'], '2025-08-10', '2025-09-10'); // 32 days
        $this->assertTrue($check['exceeding']);
    }

    #[Test]
    public function approve_and_reject_vacation(): void
    {
        Vacation::create($this->vacationData);

        $vacations = Vacation::findByUserId($_SESSION['user']);
        $vacationId = (string) $vacations[0]['id'];

        Vacation::approve($vacationId);
        $approvedVacation = Vacation::findByUserId($_SESSION['user'])[0];
        $this->assertEquals(StatusEnum::APPROVED->value, (int) $approvedVacation['status_id']);

        Vacation::reject($vacationId);
        $rejectedVacation = Vacation::findByUserId($_SESSION['user'])[0];
        $this->assertEquals(StatusEnum::REJECTED->value, (int) $rejectedVacation['status_id']);
    }

    #[Test]
    public function delete_vacation(): void
    {
        Vacation::create($this->vacationData);
        $vacations = Vacation::findByUserId($_SESSION['user']);
        $this->assertCount(1, $vacations);

        Vacation::delete((string) $vacations[0]['id']);

        $this->assertCount(0, Vacation::findByUserId($_SESSION['user']));
    }
}
