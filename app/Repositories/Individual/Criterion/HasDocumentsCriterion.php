<?php

namespace App\Repositories\Individual\Criterion;

use App\Contracts\EloquentCriterion;
use Illuminate\Database\Eloquent\Builder;

class HasDocumentsCriterion implements EloquentCriterion
{
    public function apply(Builder $builder): Builder
    {
        return $builder->has('documents', '>=', 1);
    }
}
