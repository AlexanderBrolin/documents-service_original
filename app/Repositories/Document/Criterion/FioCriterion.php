<?php

namespace App\Repositories\Document\Criterion;

use App\Constants\FieldTypes;
use App\Contracts\EloquentCriterion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FioCriterion implements EloquentCriterion
{
    private string $fio;

    public function __construct(string $fio)
    {
        $this->fio = $fio;
    }

    public function apply(Builder $builder)
    {
        $fio = $this->fio;
        return $builder->whereHas('fields', function ($query) use ($fio) {
            return $query->whereIn('type', FieldTypes::getFioTypes())
                ->where(DB::raw('LOWER(value)'), 'like', '%' . $fio . '%');
        });
    }
}
