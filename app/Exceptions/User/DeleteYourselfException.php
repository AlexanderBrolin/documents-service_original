<?php

namespace App\Exceptions\User;

class DeleteYourselfException extends \DomainException
{
    protected $message = 'Вы не можете удалить себя!';
}
