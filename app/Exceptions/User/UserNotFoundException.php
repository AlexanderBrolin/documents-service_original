<?php

namespace App\Exceptions\User;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserNotFoundException extends ModelNotFoundException
{
    protected $message = 'Пользователь не найден!';
}
