<?php

namespace App\Http\Controllers;

use App\Actions\Task\GetTasksCollectionAction;
use \Illuminate\Http\JsonResponse;

class TasksController extends Controller
{
    private GetTasksCollectionAction $getTasksCollectionAction;

    public function __construct(GetTasksCollectionAction $getTasksCollectionAction)
    {
        $this->getTasksCollectionAction = $getTasksCollectionAction;
    }

    public function index()
    {
        return view('tasks');
    }

    public function getAllTasks(): JsonResponse
    {
        $tasks = $this->getTasksCollectionAction->execute()->getTasks();

        return response()->json($tasks);
    }
}
