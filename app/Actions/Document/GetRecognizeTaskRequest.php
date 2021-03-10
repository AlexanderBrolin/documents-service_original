<?php

namespace App\Actions\Document;

final class GetRecognizeTaskRequest
{
    private int $taskId;

    public function __construct(int $taskId)
    {
        $this->taskId = $taskId;
    }

    public function getTaskId(): int
    {
        return $this->taskId;
    }
}
