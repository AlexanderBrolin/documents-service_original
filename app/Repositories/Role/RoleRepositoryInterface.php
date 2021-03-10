<?php

namespace App\Repositories\Role;

use App\Contracts\EloquentCriterion;
use App\Models\Role;

interface RoleRepositoryInterface
{
    public function findOneByCriteria(EloquentCriterion ...$criteria): ?Role;
}
