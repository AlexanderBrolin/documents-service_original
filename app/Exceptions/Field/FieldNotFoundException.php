<?php

namespace App\Exceptions\Field;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class FieldNotFoundException extends ModelNotFoundException
{
    protected $message = "Поле не найдено!";
}
