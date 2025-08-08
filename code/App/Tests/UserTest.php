<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Core\Database;

class UserTest extends TestCase
{
    private array $userData;
    protected static \PDO $db;
    protected \PDO $connection;

    public static function setUpBeforeClass(): void
    {
        self::$db = Database::connect();
    }

    protected function setUp(): void
    {
        self::$db->beginTransaction();

        $this->userData = [
            'username' => 'akis',
            'email' => 'akis@testakis.com',
            'employee_code' => '1234567',
            'password' => 'pass123',
            'role' => 'user',
        ];
    }

    protected function tearDown(): void
    {
        if (self::$db->inTransaction()) {
            self::$db->rollBack();
        }
    }

    #[Test]
    public function create_and_find_user(): void
    {
        User::create($this->userData);
        $users = User::all();

        $user = User::findByEmail($this->userData['email']);
        $this->assertSame($this->userData['username'], $user['username']);
        $this->assertSame($this->userData['email'], $user['email']);
    }

    #[Test]
    public function update_user(): void
    {
        User::create($this->userData);
        $user = User::findByEmail($this->userData['email']);

        $updatedData = [
            'username' => 'takis',
            'email' => 'takis@testakis.com',
            'employee_code' => '7654321',
        ];

        User::update((string) $user['id'], $updatedData);

        $updatedUser = User::find((string) $user['id']);
        $this->assertSame($updatedData['username'], $updatedUser['username']);
        $this->assertSame($updatedData['email'], $updatedUser['email']);
    }

    #[Test]
    public function delete_user(): void
    {
        User::create($this->userData);

        $user = User::findByEmail($this->userData['email']);
        $this->assertNotNull($user);

        User::delete((string) $user['id']);

        $deleted = User::findByEmail($this->userData['email']);
        $this->assertNull($deleted);
    }
}
