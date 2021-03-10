<?php

namespace App\Repositories\Task;

use App\Contracts\EloquentCriterion;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function findByCriteria(EloquentCriterion ...$criteria): Collection
    {
        $query = Task::query();

        foreach ($criteria as $criterion) {
            $query = $criterion->apply($query);
        }

        return $query->get();
    }

    public function findById(int $id): ?Task
    {
        return Task::find($id);
    }
}
