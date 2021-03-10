<?php

namespace App\Repositories\Individual;

use App\Contracts\EloquentCriterion;
use App\Models\Individual;
use Illuminate\Support\Collection;

interface IndividualRepositoryInterface
{
    public function findByCriteria(EloquentCriterion ...$criteria): Collection;
    public function findById(int $id): ?Individual;
    public function save(Individual $individual): Individual;
}
