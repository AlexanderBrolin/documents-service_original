<?php

namespace App\Presenters;

use App\Models\Document;
use Illuminate\Support\Collection;

class DocumentPresenter
{
    private FieldPresenter $fieldPresenter;

    public function __construct(FieldPresenter $fieldPresenter)
    {
        $this->fieldPresenter = $fieldPresenter;
    }

    public function present(Document $document): array
    {
        return [
            'id' => $document->id,
            'type' => $document->type,
            'fields' => $this->fieldPresenter->presentCollection($document->fields),
            'created_at' => $document->created_at,
            'document_image' => [
                'path' => $document->lastDocumentImage()->path
            ]
        ];
    }

    public function presentCollection(Collection $documents): array
    {
        return $documents
            ->map(fn ($document) => $this->present($document))
            ->toArray();
    }
}
