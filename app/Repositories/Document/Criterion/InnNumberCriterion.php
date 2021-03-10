<?php

namespace App\Repositories\Document\Criterion;

use App\Contracts\EloquentCriterion;
use Illuminate\Database\Eloquent\Builder;

class InnNumberCriterion implements EloquentCriterion
{
    private string $innNumber;

    public function __construct(string $innNumber)
    {
        $this->innNumber = $innNumber;
    }

    public function apply(Builder $builder): Builder
    {
        $innNumber = $this->innNumber;
        return $builder->where('type', '=', 'inn_person')
            ->whereHas('fields', function ($query) use ($innNumber){
                return $query
                    ->where('type', '=', 'number')
                    ->where('value', 'like', '%' . $innNumber . '%');
            });
    }
}
