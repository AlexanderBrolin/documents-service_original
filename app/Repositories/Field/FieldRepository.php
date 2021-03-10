<?php

namespace App\Repositories\Field;

use App\Models\Field;

class FieldRepository implements FieldRepositoryInterface
{
    private const FIELD_CHUNK_SIZE = 15;

    public function saveButch(array $fields): void
    {
        $fieldChunks = array_chunk($fields, self::FIELD_CHUNK_SIZE);
        foreach ($fieldChunks as $chunk) {
            Field::insertOrIgnore($chunk);
        }
    }

    public function save(Field $field): Field
    {
        $field->save();
        return $field;
    }

    public function findById(int $id): ?Field
    {
        return Field::find($id);
    }
}
