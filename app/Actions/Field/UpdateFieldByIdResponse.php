<?php

namespace App\Actions\Field;

use App\Models\Field;

final class UpdateFieldByIdResponse
{
    private Field $field;

    public function __construct(Field $field)
    {
        $this->field = $field;
    }

    public function getField(): Field
    {
        return $this->field;
    }
}
