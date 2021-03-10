<?php

namespace App\Actions\Document;

use App\Models\Document;

final class AddDocumentForIndividualResponse
{
    private Document $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function getDocument(): Document
    {
        return $this->document;
    }
}
