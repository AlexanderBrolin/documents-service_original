<?php

namespace App\Repositories\User;

use App\Contracts\EloquentCriterion;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function save(User $user): User;
    public function attachRole(User $user, Role $role): User;
    public function findByCriteria(EloquentCriterion ...$criteria): Collection;
    public function getAll(): Collection;
    public function findById(int $id): ?User;
    public function deleteById(int $id): void;
}
