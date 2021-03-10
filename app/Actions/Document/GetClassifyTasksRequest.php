<?php

namespace App\Actions\Document;

final class GetClassifyTasksRequest
{
    private $uploadedDocuments;

    public function __construct($uploadedDocuments)
    {
        $this->uploadedDocuments = $uploadedDocuments;
    }

    public function getUploadedDocuments()
    {
        return $this->uploadedDocuments;
    }
}
