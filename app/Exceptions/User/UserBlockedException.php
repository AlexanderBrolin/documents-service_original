<?php

namespace App\Exceptions\User;

class UserBlockedException extends \DomainException
{
    protected $message = 'Пользователь заблокирован!';
}
