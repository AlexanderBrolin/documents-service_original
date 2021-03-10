<?php

namespace App\Exceptions\Document;

use App\Exceptions\BaseException;
use Throwable;

class DocumentForAnotherPersonException extends BaseException
{
    protected $message = "Вы не можете загрузить документ другого лица!";

    public function __construct($type = "another_person_document", $code = 0, $message = "", Throwable $previous = null)
    {
        $message = $this->message;
        parent::__construct($type, $code, $message, $previous);
    }
}
