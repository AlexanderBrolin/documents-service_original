<?php

namespace App\Actions\Document;

final class GetClassifyTasksResponse
{
    private array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getResponse(): array
    {
        return $this->response;
    }
}
