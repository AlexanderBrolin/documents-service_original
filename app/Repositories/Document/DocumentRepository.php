<?php

namespace App\Repositories\Document;

use App\Contracts\EloquentCriterion;
use App\Models\Document;
use App\Models\Individual;
use Illuminate\Support\Collection;

class DocumentRepository implements DocumentRepositoryInterface
{
    public function findByCriteria(EloquentCriterion ...$criteria): Collection
    {
        $query = Document::query();

        foreach ($criteria as $criterion) {
            $query = $criterion->apply($query);
        }

        return $query->get();
    }

    public function getById(int $id): ?Document
    {
        return Document::find($id);
    }

    public function delete(Document $document): void
    {
        $document->delete();
    }

    public function associateWithIndividual(Document $document, Individual $individual): void
    {
        $document->individual()->associate($individual);
    }

    public function save(Document $document): Document
    {
        $document->save();
        return $document;
    }
}
