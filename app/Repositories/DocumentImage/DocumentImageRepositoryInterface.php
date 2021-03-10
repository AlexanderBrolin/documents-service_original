<?php

namespace App\Repositories\DocumentImage;

use App\Models\Document;
use App\Models\DocumentImage;

interface DocumentImageRepositoryInterface
{
    public function save(DocumentImage $documentImage): DocumentImage;
    public function associateDocument(DocumentImage $documentImage, Document $document): void;
}
