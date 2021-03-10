<?php

namespace App\Actions\Field;

final class UpdateFieldByIdRequest
{
    private int $fieldId;
    private string $fieldValue;

    public function __construct(int $fieldId, string $fieldValue)
    {
        $this->fieldId = $fieldId;
        $this->fieldValue = $fieldValue;
    }

    public function getFieldId(): int
    {
        return $this->fieldId;
    }

    public function getFieldValue(): string
    {
        return $this->fieldValue;
    }
}
