<?php

namespace App\Actions\Document;

use App\Constants\HistoryTypes;
use App\Exceptions\Document\DocumentForAnotherPersonException;
use App\Exceptions\Individual\IndividualNotFoundException;
use App\Exceptions\Task\TaskNotFoundException;
use App\Models\Document;
use App\Models\DocumentImage;
use App\Models\History;
use App\Repositories\Document\DocumentRepositoryInterface;
use App\Repositories\DocumentImage\DocumentImageRepositoryInterface;
use App\Repositories\Field\FieldRepositoryInterface;
use App\Repositories\Individual\IndividualRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Services\DbrainApiService;
use App\Services\FioService;
use Illuminate\Support\Facades\Auth;

final class AddDocumentForIndividualAction
{
    private TaskRepositoryInterface $taskRepository;
    private IndividualRepositoryInterface $individualRepository;
    private DocumentRepositoryInterface $documentRepository;
    private DocumentImageRepositoryInterface $documentImageRepository;
    private FieldRepositoryInterface $fieldRepository;
    private DbrainApiService $apiService;
    private FioService $fioService;

    public function __construct(
        TaskRepositoryInterface $taskRepository,
        IndividualRepositoryInterface $individualRepository,
        DocumentRepositoryInterface $documentRepository,
        DocumentImageRepositoryInterface $documentImageRepository,
        FieldRepositoryInterface $fieldRepository,
        DbrainApiService $apiService,
        FioService $fioService
    ) {
        $this->taskRepository = $taskRepository;
        $this->individualRepository = $individualRepository;
        $this->documentRepository = $documentRepository;
        $this->documentImageRepository = $documentImageRepository;
        $this->fieldRepository = $fieldRepository;
        $this->apiService = $apiService;
        $this->fioService = $fioService;
    }

    public function execute(AddDocumentForIndividualRequest $request): AddDocumentForIndividualResponse
    {
        $task = $this->taskRepository->findById($request->getTaskId());

        if (!$task) {
            throw new TaskNotFoundException();
        }

        $individual = $this->individualRepository->findById($request->getIndividualId());

        if (!$individual) {
            throw new IndividualNotFoundException();
        }

        $document = fopen(storage_path('app/public/' . $task->document_path), 'rb');
        $recognizeTaskId = $this->apiService->getRecognizeTaskId($document);
        $response = $this->apiService->getRecognizeResponse($recognizeTaskId);

        if (!$request->getForce()) {
            $individualName = $this->fioService->getIndividualName($individual);
            $nameFromResponse = $this->fioService->getNameFromResponse($response['items'][0]['fields']);

            if (
                $individualName
                && $nameFromResponse
                && $individualName !== $nameFromResponse
            ) {
                throw new DocumentForAnotherPersonException();
            }

            $individualSurname = $this->fioService->getIndividualSurname($individual);
            $surnameFromResponse = $this->fioService->getSurnameFromResponse($response['items'][0]['fields']);

            if (
                $individualSurname
                && $surnameFromResponse
                && $individualSurname !== $surnameFromResponse
            ) {
                throw new DocumentForAnotherPersonException();
            }

            $individualPatronymic = $this->fioService->getIndividualPatronymic($individual);
            $patronymicFromResponse = $this->fioService->getPatronymicFromResponse($response['items'][0]['fields']);

            if (
                $individualPatronymic
                && $patronymicFromResponse
                && $individualPatronymic !== $patronymicFromResponse
            ) {
                throw new DocumentForAnotherPersonException();
            }

            $individualFio = $this->fioService->getIndividualFio($individual);
            $fioFromResponse = $this->fioService->getFioFromResponse($response['items'][0]['fields']);

            if (
                $individualFio
                && $fioFromResponse
                && $individualFio !== $fioFromResponse
            ) {
                throw new DocumentForAnotherPersonException();
            }
        }

        $documentObj = new Document();
        $documentObj->type = $task->document_type;
        $this->documentRepository->associateWithIndividual($documentObj, $individual);
        $documentObj = $this->documentRepository->save($documentObj);

        $documentImage = new DocumentImage();
        $documentImage->path = $task->document_path;
        $this->documentImageRepository->associateDocument($documentImage, $documentObj);
        $this->documentImageRepository->save($documentImage);

        $fieldModels = [];
        foreach ($response['items'][0]['fields'] as $fieldType => $field) {
            $fieldModels[] = [
                'type' => $fieldType,
                'value' => $field['text'] ?: '',
                'confidence' => $field['confidence'] ?? 0,
                'document_id' => $documentObj->id,
            ];
        }
        $this->fieldRepository->saveButch($fieldModels);

        History::create([
            'type' => HistoryTypes::DOCUMENT_ADD,
            'author_id' => Auth::id(),
            'document_id' => $documentObj->id,
            'individual_id' => $individual->id,
            'before' => $task->document_path
        ]);

        return new AddDocumentForIndividualResponse($documentObj);
    }
}
