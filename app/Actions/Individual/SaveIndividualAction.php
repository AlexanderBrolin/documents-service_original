<?php

namespace App\Actions\Individual;

use App\Constants\FieldTypes;
use App\Constants\HistoryTypes;
use App\Exceptions\Individual\CantCreateWithoutFioException;
use App\Models\Document;
use App\Models\DocumentImage;
use App\Models\History;
use App\Models\Individual;
use App\Repositories\Document\DocumentRepositoryInterface;
use App\Repositories\DocumentImage\DocumentImageRepositoryInterface;
use App\Repositories\Field\FieldRepositoryInterface;
use App\Repositories\Individual\IndividualRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Services\IndividualService;
use Illuminate\Support\Facades\Auth;

final class SaveIndividualAction
{
    private IndividualRepositoryInterface $individualRepository;
    private TaskRepositoryInterface $taskRepository;
    private DocumentImageRepositoryInterface $documentImageRepository;
    private DocumentRepositoryInterface $documentRepository;
    private FieldRepositoryInterface $fieldRepository;

    public function __construct(
        IndividualRepositoryInterface $individualRepository,
        TaskRepositoryInterface $taskRepository,
        DocumentImageRepositoryInterface $documentImageRepository,
        DocumentRepositoryInterface $documentRepository,
        FieldRepositoryInterface $fieldRepository
    ) {
        $this->individualRepository = $individualRepository;
        $this->taskRepository = $taskRepository;
        $this->documentImageRepository = $documentImageRepository;
        $this->documentRepository = $documentRepository;
        $this->fieldRepository = $fieldRepository;
    }

    public function execute(SaveIndividualRequest $request): SaveIndividualResponse
    {
        $canCreate = false;
        $response = [];
        foreach ($request->getPayloadData() as $dbrainTaskKey => $value) {
            foreach ($request->getPayloadData()[$dbrainTaskKey] as $taskId => $item) {
                if (isset($item['fields'])) {
                    foreach ($item['fields'] as $fieldType => $field) {
                        if (
                            in_array($fieldType, FieldTypes::getNameTypes(), true)
                            ||
                            in_array($fieldType, FieldTypes::getSurnameTypes(), true)
                            ||
                            in_array($fieldType, FieldTypes::getPatronymicTypes(), true)
                            ||
                            in_array($fieldType, FieldTypes::getFioTypes(), true)
                        ) {
                            if ($field['text']) {
                                $canCreate = true;
                            }
                        }
                    }
                }
            }
        }

        if (!$canCreate) {
            throw new CantCreateWithoutFioException();
        }

        foreach ($request->getPayloadData() as $dbrainTaskKey => $value) {
            IndividualService::checkIfIndividualExists($request->getPayloadData()[$dbrainTaskKey]);

            $individual = new Individual();
            $individual = $this->individualRepository->save($individual);

            foreach ($request->getPayloadData()[$dbrainTaskKey] as $taskId => $item) {
                $task = $this->taskRepository->findById($taskId);

                $document = new Document();
                $document->type = $item['document_type'];
                $this->documentRepository->associateWithIndividual($document, $individual);
                $document = $this->documentRepository->save($document);

                History::create([
                    'type' => HistoryTypes::DOCUMENT_ADD,
                    'author_id' => Auth::id(),
                    'document_id' => $document->id,
                    'individual_id' => $individual->id,
                    'before' => $task->document_path
                ]);

                $documentImage = new DocumentImage();
                $documentImage->path = $task->document_path;
                $this->documentImageRepository->associateDocument($documentImage, $document);
                $this->documentImageRepository->save($documentImage);

                $fieldModels = [];
                if (isset($item['fields'])) {
                    foreach ($item['fields'] as $fieldType => $field) {
                        $fieldModels[] = [
                            'type' => $fieldType,
                            'value' => $field['text'] ?: '',
                            'confidence' => $field['confidence'],
                            'document_id' => $document->id
                        ];
                    }
                }

                $this->fieldRepository->saveButch($fieldModels);
            }
            $response[$dbrainTaskKey] = $individual->id;
        }

        return new SaveIndividualResponse($response);
    }
}
