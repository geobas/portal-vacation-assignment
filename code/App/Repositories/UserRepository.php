<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function all(): array
    {
        return User::all();
    }

    public function find(string $id): ?array
    {
        return User::find($id) ?? [];
    }

    public function findByUsername(string $username): ?array
    {
        return User::findByUsername($username);
    }

    public function findByEmail(string $email): ?array
    {
        return User::findByEmail($email);
    }

    public function create(array $data): void
    {
        User::create($data);
    }

    public function update(string $id, array $data): void
    {
        User::update($id, $data);
    }

    public function delete(string $id): void
    {
        User::delete($id);
    }
}
