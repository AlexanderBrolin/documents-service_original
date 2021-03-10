<?php

namespace App\Actions\Document;

use App\Exceptions\Document\DocumentNotFoundException;
use App\Exceptions\Task\TaskNotFoundException;
use App\Models\DocumentImage;
use App\Models\History;
use App\Repositories\Document\DocumentRepositoryInterface;
use App\Repositories\DocumentImage\DocumentImageRepositoryInterface;
use App\Repositories\Field\FieldRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Services\DbrainApiService;
use Illuminate\Support\Facades\Auth;

final class ReplaceDocumentAction
{
    private DbrainApiService $apiService;
    private FieldRepositoryInterface $fieldRepository;
    private TaskRepositoryInterface $taskRepository;
    private DocumentRepositoryInterface $documentRepository;
    private DocumentImageRepositoryInterface $documentImageRepository;

    public function __construct(
        DbrainApiService $apiService,
        FieldRepositoryInterface $fieldRepository,
        TaskRepositoryInterface $taskRepository,
        DocumentRepositoryInterface $documentRepository,
        DocumentImageRepositoryInterface $documentImageRepository
    ) {
        $this->apiService = $apiService;
        $this->fieldRepository = $fieldRepository;
        $this->taskRepository = $taskRepository;
        $this->documentRepository = $documentRepository;
        $this->documentImageRepository = $documentImageRepository;
    }

    public function execute(ReplaceDocumentRequest $request): void
    {
        $task = $this->taskRepository->findById($request->getTaskId());

        if (!$task) {
            throw new TaskNotFoundException();
        }

        $documentObj = $this->documentRepository->getById($request->getDocumentId());

        if (!$documentObj) {
            throw new DocumentNotFoundException();
        }

        $pathBefore = $documentObj->lastDocumentImage()->path;

        $document = fopen(storage_path('app/public/' . $task->document_path), 'rb');
        $recognizeTaskId = $this->apiService->getRecognizeTaskId($document);
        $response = $this->apiService->getRecognizeResponse($recognizeTaskId);

        $newDocImage = new DocumentImage();
        $newDocImage->path = $task->document_path;
        $newDocImage->document()->associate($documentObj);
        $this->documentImageRepository->save($newDocImage);

        $documentObj->fields()->delete();
        $this->documentRepository->save($documentObj);

        History::create([
            'type' => 'document_update',
            'author_id' => Auth::id(),
            'document_id' => $documentObj->id,
            'individual_id' => $documentObj->individual->id,
            'before' => $pathBefore,
            'after' => $documentObj->lastDocumentImage()->path
        ]);

        if (isset($response['items'])) {
            if (isset($response['items'][0])) {
                if (isset($response['items'][0]['fields'])) {
                    $fieldModels = [];
                    foreach ($response['items'][0]['fields'] as $fieldType => $field) {
                        $fieldModels[] = [
                            'type' => $fieldType,
                            'value' => $field['text'] ?: '',
                            'confidence' => $field['confidence'],
                            'document_id' => $documentObj->id,
                        ];
                    }
                    $this->fieldRepository->saveButch($fieldModels);
                }
            }
        }
    }
}
