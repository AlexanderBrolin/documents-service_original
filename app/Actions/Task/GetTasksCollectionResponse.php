<?php

namespace App\Actions\Task;

use Illuminate\Support\Collection;

final class GetTasksCollectionResponse
{
    private Collection $tasks;

    public function __construct(Collection $tasks)
    {
        $this->tasks = $tasks;
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }
}
