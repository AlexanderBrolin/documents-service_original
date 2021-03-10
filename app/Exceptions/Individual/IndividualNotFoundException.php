<?php

namespace App\Exceptions\Individual;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class IndividualNotFoundException extends ModelNotFoundException
{
    protected $message = "Физическое лицо не найдено";
}
