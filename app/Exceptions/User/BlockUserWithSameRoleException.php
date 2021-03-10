<?php

namespace App\Exceptions\User;

class BlockUserWithSameRoleException extends \DomainException
{
    protected $message = 'Вы не можете блокировать пользователя с такой же ролью!';
}
