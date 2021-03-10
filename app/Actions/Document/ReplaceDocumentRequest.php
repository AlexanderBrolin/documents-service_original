<?php

namespace App\Actions\Document;

final class ReplaceDocumentRequest
{
    private int $taskId;
    private int $documentId;

    public function __construct(int $taskId, int $documentId)
    {
        $this->taskId = $taskId;
        $this->documentId = $documentId;
    }

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function getDocumentId(): int
    {
        return $this->documentId;
    }
}
