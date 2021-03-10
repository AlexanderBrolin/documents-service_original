<?php

namespace App\Actions\Document;

use App\Exceptions\Task\TaskNotFoundException;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Services\DbrainApiService;

final class GetRecognizeTaskAction
{
    private DbrainApiService $apiService;
    private TaskRepositoryInterface $taskRepository;

    public function __construct(
        DbrainApiService $apiService,
        TaskRepositoryInterface $taskRepository
    ) {
        $this->apiService = $apiService;
        $this->taskRepository = $taskRepository;
    }

    public function execute(GetRecognizeTaskRequest $request): GetClassifyTasksResponse
    {
        $task = $this->taskRepository->findById($request->getTaskId());

        if (!$task) {
            throw new TaskNotFoundException();
        }

        $document = fopen(storage_path('app/public/' . $task->document_path), 'rb');
        $recognizeTaskId = $this->apiService->getRecognizeTaskId($document);
        fclose($document);
        $response = $this->apiService->getRecognizeResponse($recognizeTaskId);

        return new GetClassifyTasksResponse($response);
    }
}
