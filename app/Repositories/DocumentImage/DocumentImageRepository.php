<?php

namespace App\Repositories\DocumentImage;

use App\Models\Document;
use App\Models\DocumentImage;

class DocumentImageRepository implements DocumentImageRepositoryInterface
{
    public function save(DocumentImage $documentImage): DocumentImage
    {
        $documentImage->save();
        return $documentImage;
    }

    public function associateDocument(DocumentImage $documentImage, Document $document): void
    {
        $documentImage->document()->associate($document);
    }
}
