<?php

namespace App\Repositories\Individual;

use App\Contracts\EloquentCriterion;
use App\Models\Individual;
use Illuminate\Support\Collection;

class IndividualRepository implements IndividualRepositoryInterface
{
    public function findByCriteria(EloquentCriterion ...$criteria): Collection
    {
        $query = Individual::query();

        foreach ($criteria as $criterion) {
            $query = $criterion->apply($query);
        }

        return $query->get();
    }

    public function findById(int $id): ?Individual
    {
        return Individual::find($id);
    }

    public function save(Individual $individual): Individual
    {
        $individual->save();
        return $individual;
    }
}
