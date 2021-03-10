<?php

namespace App\Exceptions\User;

class BlockYourselfException extends \DomainException
{
    protected $message = 'Вы не можете заблокировать себя!';
}
