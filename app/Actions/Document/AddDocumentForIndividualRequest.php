<?php

namespace App\Actions\Document;

final class AddDocumentForIndividualRequest
{
    private int $taskId;
    private int $individualId;
    private bool $force;

    public function __construct(int $taskId, int $individualId, bool $force)
    {
        $this->taskId = $taskId;
        $this->individualId = $individualId;
        $this->force = $force;
    }

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function getIndividualId(): int
    {
        return $this->individualId;
    }

    public function getForce(): bool
    {
        return $this->force;
    }
}
