<?php

namespace App\Exceptions\User;

class DeleteDeveloperException extends \DomainException
{
    protected $message = 'Вы не можете удалить разработчика!';
}
