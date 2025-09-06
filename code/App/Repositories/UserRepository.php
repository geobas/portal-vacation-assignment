<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $user = new User(),
    ) {
    }

    public function all(): array
    {
        return $this->user->all();
    }

    public function find(string $id): ?User
    {
        return $this->user->find($id);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->user->findByUsername($username);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->user->findByEmail($email);
    }

    public function create(array $data): void
    {
        $this->user->create($data);
    }

    public function update(string $id, array $data): void
    {
        $this->user->update($id, $data);
    }

    public function delete(string $id): void
    {
        $this->user->delete($id);
    }
}
