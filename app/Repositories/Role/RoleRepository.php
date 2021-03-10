<?php

namespace App\Repositories\Role;

use App\Contracts\EloquentCriterion;
use App\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function findOneByCriteria(EloquentCriterion ...$criteria): ?Role
    {
        $query = Role::query();

        foreach ($criteria as $criterion) {
            $query = $criterion->apply($query);
        }

        return $query->get()->first();
    }
}
