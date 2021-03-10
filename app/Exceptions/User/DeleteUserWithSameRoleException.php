<?php


namespace App\Exceptions\User;


class DeleteUserWithSameRoleException extends \DomainException
{
    protected $message = 'Вы не можете удалить пользователя с такой же ролью!';
}
