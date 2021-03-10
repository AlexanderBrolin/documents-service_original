<?php

namespace App\Repositories\Document\Criterion;

use App\Contracts\EloquentCriterion;
use Illuminate\Database\Eloquent\Builder;

class SnilsNumberCriterion implements EloquentCriterion
{
    private string $snilsNumber;

    public function __construct(string $snilsNumber)
    {
        $this->snilsNumber = $snilsNumber;
    }

    public function apply(Builder $builder): Builder
    {
        $snilsNumber = $this->snilsNumber;
        return $builder->where('type', '=', 'snils_front')
            ->whereHas('fields', function ($query) use ($snilsNumber){
                return $query
                    ->where('type', '=', 'number')
                    ->where('value', 'like', '%' . $snilsNumber . '%');
            });
    }
}
