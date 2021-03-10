<?php

namespace App\Exceptions\Document;

class DocumentAlreadyRestoredException extends \DomainException
{
    protected $message = "Документ уже восстановлен!";
}
