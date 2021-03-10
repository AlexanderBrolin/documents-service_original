<?php

namespace App\Exceptions\User;

class BlockDeveloperException extends \DomainException
{
    protected $message = 'Вы не можете заблокировать разработчика!';
}
