<?php

namespace App\Repositories\Document\Criterion;

use App\Constants\FieldTypes;
use App\Contracts\EloquentCriterion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SecondNameCriterion implements EloquentCriterion
{
    private string $secondName;

    public function __construct(string $secondName)
    {
        $this->secondName = $secondName;
    }

    public function apply(Builder $builder): Builder
    {
        $name = Str::lower($this->secondName);
        return $builder->whereHas('fields', function ($query) use ($name) {
            return $query->whereIn('type', FieldTypes::getSurnameTypes())
                ->where(DB::raw('LOWER(value)'), 'like', '%' . $name . '%');
        });
    }
}
