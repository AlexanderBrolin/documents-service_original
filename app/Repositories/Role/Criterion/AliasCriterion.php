<?php

namespace App\Repositories\Role\Criterion;

use App\Contracts\EloquentCriterion;
use Illuminate\Database\Eloquent\Builder;

class AliasCriterion implements EloquentCriterion
{
    private string $alias;

    public function __construct(string $alias)
    {
        $this->alias = $alias;
    }

    public function apply(Builder $builder): Builder
    {
        return $builder->where('alias', '=', $this->alias);
    }
}
