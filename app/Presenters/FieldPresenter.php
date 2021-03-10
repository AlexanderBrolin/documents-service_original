<?php

namespace App\Presenters;

use App\Models\Field;
use Illuminate\Support\Collection;

class FieldPresenter
{
    public function present(Field $field): array
    {
        return [
            'id' => $field->id,
            'type' => $field->type,
            'value' => $field->value,
            'confidence' => $field->confidence,
            'created_at' => $field->created_at,
            'document_id' => $field->document->id,
        ];
    }

    public function presentCollection(Collection $fields): array
    {
        return $fields
            ->map(fn ($field) => $this->present($field))
            ->toArray();
    }
}
