<?php

declare(strict_types=1);

namespace Tests;

use App\Core\Database;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var array{
     *   username: string,
     *   email: string,
     *   employee_code: string,
     *   password: string,
     *   role: string
     * }
     */
    private array $userData;
    protected static \PDO $db;
    protected \PDO $connection;
    protected User $user;

    public static function setUpBeforeClass(): void
    {
        self::$db = Database::connect();
    }

    protected function setUp(): void
    {
        self::$db->beginTransaction();

        $this->user = new User();

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
        $this->user->create($this->userData);
        $users = $this->user->all();

        $user = $this->user->findByEmail($this->userData['email']);
        $this->assertSame($this->userData['username'], $user->username);
        $this->assertSame($this->userData['email'], $user->email);
    }

    #[Test]
    public function update_user(): void
    {
        $this->user->create($this->userData);
        $user = $this->user->findByEmail($this->userData['email']);

        $updatedData = [
            'username' => 'takis',
            'email' => 'takis@testakis.com',
            'employee_code' => '7654321',
        ];

        $this->user->update((string) $user->id, $updatedData);

        $updatedUser = $this->user->find((string) $user->id);
        $this->assertSame($updatedData['username'], $updatedUser->username);
        $this->assertSame($updatedData['email'], $updatedUser->email);
    }

    #[Test]
    public function delete_user(): void
    {
        $this->user->create($this->userData);

        $user = $this->user->findByEmail($this->userData['email']);
        $this->assertNotNull($user);

        $this->user->delete((string) $user->id);

        $deleted = $this->user->findByEmail($this->userData['email']);
        $this->assertNull($deleted);
    }
}
