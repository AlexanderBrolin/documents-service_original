<?php

namespace App\Actions\Task;

use App\Repositories\Task\Criterion\OrderByCriterion;
use App\Repositories\Task\TaskRepositoryInterface;

final class GetTasksCollectionAction
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function execute(): GetTasksCollectionResponse
    {
        $criteria = [new OrderByCriterion()];
        $tasks = $this->taskRepository->findByCriteria(...$criteria);
        return new GetTasksCollectionResponse($tasks);
    }
}
