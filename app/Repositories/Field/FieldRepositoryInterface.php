<?php

namespace App\Repositories\Field;

use App\Models\Field;

interface FieldRepositoryInterface
{
    public function saveButch(array $fields): void;
    public function save(Field $field): Field;
    public function findById(int $id): ?Field;
}
