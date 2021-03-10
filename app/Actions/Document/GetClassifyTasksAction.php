<?php

namespace App\Actions\Document;

use App\Constants\TaskTypes;
use App\Models\Document;
use App\Models\Task;
use App\Services\DbrainApiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class GetClassifyTasksAction
{
    private DbrainApiService $apiService;

    public function __construct(DbrainApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function execute(GetClassifyTasksRequest $request): GetClassifyTasksResponse
    {
        $documents = $request->getUploadedDocuments();

        $tasks = [];
        foreach ($documents as $document) {
            $tasks[] = $this->apiService->getClassifyTaskId($document);
        }

        $responses = [];
        foreach ($tasks as $taskId) {
            $classifyResponse = $this->apiService->getClassifyResponse($taskId);

            foreach ($classifyResponse['items'] as $item) {
                $imageParts = explode(";base64,", $item['crop']);
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1];
                $image = base64_decode($imageParts[1]);
                $name = time() . '_' . Str::random(12) . '.' . $imageType;
                Storage::put(Document::PATH . $name, $image);

                $task = Task::create([
                    'user_id' => Auth::id(),
                    'document_path' => 'documents/' . $name,
                    'task_id' => $taskId,
                    'type' => TaskTypes::CLASSIFY_TYPE,
                    'document_type' => $item['document']['type']
                ]);
                $responses[] = $task;
            }
        }

        return new GetClassifyTasksResponse($responses);
    }
}
