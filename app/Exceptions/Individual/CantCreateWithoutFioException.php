<?php

namespace App\Exceptions\Individual;

class CantCreateWithoutFioException extends \DomainException
{
    protected $message = "Для сохранения этого документа, разместите его на странице физического лица!";
}
