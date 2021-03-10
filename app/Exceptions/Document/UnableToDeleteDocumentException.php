<?php

namespace App\Exceptions\Document;

class UnableToDeleteDocumentException extends \DomainException
{
    protected $message = "Невозможно удалить документ!";
}
