<?php

namespace App\Exceptions;

use Throwable;

class BaseException extends \DomainException
{
    public function __construct($type = "", $code = 0, $message = "", Throwable $previous = null)
    {
        $this->type = $type;
        $this->message = $this->message;
        parent::__construct($message, $code, $previous);
    }

    public function getType()
    {
        return $this->type;
    }
}
