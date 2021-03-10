<?php

namespace App\Repositories\Task\Criterion;

use App\Contracts\EloquentCriterion;
use Illuminate\Database\Eloquent\Builder;

class TaskKeyCriterion implements EloquentCriterion
{
    private string $taskKey;

    public function __construct(string $taskKey)
    {
        $this->taskKey = $taskKey;
    }

    public function apply(Builder $builder): Builder
    {
        return $builder->where('task_id', '=', $this->taskKey);
    }
}
