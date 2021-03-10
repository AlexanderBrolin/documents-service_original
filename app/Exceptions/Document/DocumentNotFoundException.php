<?php

namespace App\Exceptions\Document;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class DocumentNotFoundException extends ModelNotFoundException
{
    protected $message = "Документ не найдено!";
}
