<?php

namespace App\Actions\Document;

use App\Constants\HistoryTypes;
use App\Exceptions\Document\DocumentNotFoundException;
use App\Exceptions\Document\UnableToDeleteDocumentException;
use App\Models\History;
use App\Repositories\Document\DocumentRepositoryInterface;
use Illuminate\Support\Facades\Auth;

final class DeleteDocumentByIdAction
{
    private DocumentRepositoryInterface $documentRepository;

    public function __construct(DocumentRepositoryInterface $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    public function execute(DeleteDocumentByIdRequest $request)
    {
        $document = $this->documentRepository->getById($request->getId());

        if (!$document) {
            throw new DocumentNotFoundException();
        }
        $individual = $document->individual;

        if ($individual->documents()->count() <= 1) {
            throw new UnableToDeleteDocumentException();
        }
        $docId = $document->id;

        $this->documentRepository->delete($document);

        History::create([
            'type' => HistoryTypes::DOCUMENT_DELETE,
            'author_id' => Auth::id(),
            'individual_id' => $individual->id,
            'document_id' => $docId
        ]);
    }
}
