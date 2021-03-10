<?php

namespace App\Repositories\Task\Criterion;

use App\Contracts\EloquentCriterion;
use Illuminate\Database\Eloquent\Builder;

class OrderByCriterion implements EloquentCriterion
{
    private string $column;
    private string $direction;

    public function __construct(string $column = 'created_at', string $direction = 'desc')
    {
        $this->column = $column;
        $this->direction = $direction;
    }

    public function apply(Builder $builder): Builder
    {
        return $builder->orderBy($this->column, $this->direction);
    }
}
