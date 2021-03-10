<?php

namespace App\Exceptions\Task;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskNotFoundException extends ModelNotFoundException
{
    protected $message = "Задание не найдено!";
}
