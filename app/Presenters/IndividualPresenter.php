<?php

namespace App\Presenters;

use App\Models\Individual;
use Illuminate\Support\Collection;

class IndividualPresenter
{
    private DocumentPresenter $documentPresenter;

    public function __construct(DocumentPresenter $documentPresenter)
    {
        $this->documentPresenter = $documentPresenter;
    }

    public function present(Individual $individual): array
    {
        return [
            'id' => $individual->id,
            'created_at' => $individual->created_at,
            'documents' => $this->documentPresenter->presentCollection($individual->documents),
            'history' => $individual->history
        ];
    }

    public function presentCollection(Collection $individuals): array
    {
        return $individuals
            ->map(fn ($individual) => $this->present($individual))
            ->toArray();
    }
}
