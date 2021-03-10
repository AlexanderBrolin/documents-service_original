<?php

namespace App\Repositories\Task;

use App\Contracts\EloquentCriterion;
use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function findByCriteria(EloquentCriterion ...$criteria): Collection;
    public function findById(int $id): ?Task;
}
