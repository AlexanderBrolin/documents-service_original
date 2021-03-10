<?php

namespace App\Repositories\Document\Criterion;

use App\Constants\FieldTypes;
use App\Contracts\EloquentCriterion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PatronymicCriterion implements EloquentCriterion
{
    private string $patronymic;

    public function __construct(string $patronymic)
    {
        $this->patronymic = $patronymic;
    }

    public function apply(Builder $builder): Builder
    {
        $patronymic = Str::lower($this->patronymic);
        return $builder->whereHas('fields', function ($query) use ($patronymic) {
            return $query->whereIn('type', FieldTypes::getPatronymicTypes())
                ->where(DB::raw('LOWER(value)'), 'like', '%' . $patronymic . '%');
        });
    }
}
