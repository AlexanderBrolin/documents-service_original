<?php

namespace App\Repositories\Document\Criterion;

use App\Constants\FieldTypes;
use App\Contracts\EloquentCriterion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FirstNameCriterion implements EloquentCriterion
{
    private string $firstName;

    public function __construct(string $firstName)
    {
        $this->firstName = $firstName;
    }

    public function apply(Builder $builder): Builder
    {
        $name = Str::lower($this->firstName);
        return $builder->whereHas('fields', function ($query) use ($name) {
            return $query->whereIn('type', FieldTypes::getNameTypes())
                ->where(DB::raw('LOWER(value)'), 'like', '%' . $name . '%');
        });
    }
}
