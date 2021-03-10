<?php

namespace App\Exceptions\User;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserWithEmailAlreadyExistsException extends \DomainException
{
    protected $message = 'Пользователь с таким E-mail уже существует!';
}
