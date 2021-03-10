<?php

namespace App\Actions\Document;

use App\Repositories\Task\Criterion\TaskKeyCriterion;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Services\DbrainApiService;

final class GetRecognizedDataByTaskKeyAction
{
    private TaskRepositoryInterface $taskRepository;
    private DbrainApiService $apiService;

    public function __construct(
        TaskRepositoryInterface $taskRepository,
        DbrainApiService $apiService
    ) {
        $this->taskRepository = $taskRepository;
        $this->apiService = $apiService;
    }

    public function execute(GetRecognizedDataByTaskKeyRequest $request): GetRecognizedDataByTaskKeyResponse
    {
        $criteria = [new TaskKeyCriterion($request->getTaskKey())];
        $tasks = $this->taskRepository->findByCriteria(...$criteria);

        $response = [];
        foreach ($tasks as $task) {
            if ($task->document_type !== 'not_document') {
                $document = fopen(storage_path('app/public/' . $task->document_path), 'rb');
                $recognizeTaskId = $this->apiService->getRecognizeTaskId($document);
                fclose($document);
                $apiResponse = $this->apiService->getRecognizeResponse($recognizeTaskId);
                $response[] = [
                    'id' => $task->id,
                    'task_id' => $task->task_id,
                    'doc_type' => $apiResponse['items'][0]['doc_type'],
                    'fields' => $apiResponse['items'][0]['fields'],
                ];
            }
        }

        return new GetRecognizedDataByTaskKeyResponse($response);
    }
}
