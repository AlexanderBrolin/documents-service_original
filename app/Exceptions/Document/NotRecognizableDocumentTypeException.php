<?php

namespace App\Exceptions\Document;

class NotRecognizableDocumentTypeException extends \DomainException
{
    protected $message = 'Документ не распознаваемый!';
}
