<?php

namespace App\Exceptions;

class SomethingWentWrongException extends \DomainException
{
    protected $message = "Что-то пошлно не так!";
}
