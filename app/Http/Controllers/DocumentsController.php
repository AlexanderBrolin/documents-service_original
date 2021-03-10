<?php

namespace App\Http\Controllers;

use App\Actions\Document\AddDocumentForIndividualAction;
use App\Actions\Document\AddDocumentForIndividualRequest;
use App\Actions\Document\DeleteDocumentByIdAction;
use App\Actions\Document\DeleteDocumentByIdRequest;
use App\Actions\Document\GetClassifyTasksAction;
use App\Actions\Document\GetClassifyTasksRequest;
use App\Actions\Document\GetRecognizedDataByTaskKeyAction;
use App\Actions\Document\GetRecognizedDataByTaskKeyRequest;
use App\Actions\Document\GetRecognizeTaskAction;
use App\Actions\Document\GetRecognizeTaskRequest;
use App\Actions\Document\ReplaceDocumentAction;
use App\Actions\Document\ReplaceDocumentRequest;
use App\Actions\Field\UpdateFieldByIdAction;
use App\Actions\Field\UpdateFieldByIdRequest;
use App\Http\Requests\AddDocumentForIndividual;
use App\Http\Requests\GetRecognizedDataHttpRequest;
use App\Http\Requests\ReplaceDocumentHttpRequest;
use App\Http\Requests\UpdateFieldByIdHttpRequest;
use App\Presenters\DocumentPresenter;
use App\Presenters\FieldPresenter;
use App\Services\DbrainApiService;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;

class DocumentsController extends Controller
{
    private DbrainApiService $apiService;
    private DeleteDocumentByIdAction $deleteDocumentByIdAction;
    private AddDocumentForIndividualAction $addDocumentForIndividualAction;
    private DocumentPresenter $documentPresenter;
    private FieldPresenter $fieldPresenter;
    private UpdateFieldByIdAction $updateFieldByIdAction;
    private GetRecognizedDataByTaskKeyAction $getRecognizedDataByTaskKeyAction;
    private ReplaceDocumentAction $replaceDocumentAction;
    private GetClassifyTasksAction $getClassifyTasksAction;
    private GetRecognizeTaskAction $getRecognizeTaskAction;

    public function __construct(
        DbrainApiService $service,
        DeleteDocumentByIdAction $deleteDocumentByIdAction,
        AddDocumentForIndividualAction $addDocumentForIndividualAction,
        UpdateFieldByIdAction $updateFieldByIdAction,
        DocumentPresenter $documentPresenter,
        FieldPresenter $fieldPresenter,
        GetRecognizedDataByTaskKeyAction $getRecognizedDataByTaskKeyAction,
        ReplaceDocumentAction $replaceDocumentAction,
        GetClassifyTasksAction $getClassifyTasksAction,
        GetRecognizeTaskAction $getRecognizeTaskAction
    ) {
        $this->apiService = $service;
        $this->deleteDocumentByIdAction = $deleteDocumentByIdAction;
        $this->addDocumentForIndividualAction = $addDocumentForIndividualAction;
        $this->updateFieldByIdAction = $updateFieldByIdAction;
        $this->documentPresenter = $documentPresenter;
        $this->fieldPresenter = $fieldPresenter;
        $this->getRecognizedDataByTaskKeyAction = $getRecognizedDataByTaskKeyAction;
        $this->replaceDocumentAction = $replaceDocumentAction;
        $this->getClassifyTasksAction = $getClassifyTasksAction;
        $this->getRecognizeTaskAction = $getRecognizeTaskAction;
    }

    public function index()
    {
        return view('documents');
    }

    public function getClassifyTasks(Request $request): JsonResponse
    {
        $response = $this->getClassifyTasksAction->execute(
            new GetClassifyTasksRequest($request->file('documents'))
        )->getResponse();

        return response()->json($response);
    }

    public function getRecognizeTask(string $id): JsonResponse
    {
        $response = $this->getRecognizeTaskAction->execute(
            new GetRecognizeTaskRequest((int)$id)
        )->getResponse();

        return response()->json($response);
    }

    public function replaceDocument(ReplaceDocumentHttpRequest $request): JsonResponse
    {
        $this->replaceDocumentAction->execute(
            new ReplaceDocumentRequest(
                (int)$request->task_id,
                (int)$request->document_id
            )
        );
        return response()->json();
    }

    public function addDocumentForIndividual(AddDocumentForIndividual $request): JsonResponse
    {
        $response = $this->addDocumentForIndividualAction->execute(
            new AddDocumentForIndividualRequest(
                (int)$request->task_id,
                (int)$request->individual_id,
                (bool)$request->force,
            )
        );

        return response()->json(
            $this->documentPresenter->present($response->getDocument())
        );
    }

    public function updateField(UpdateFieldByIdHttpRequest $request): JsonResponse
    {
        $response = $this->updateFieldByIdAction->execute(
            new UpdateFieldByIdRequest(
                (int)$request->field_id,
                $request->new_value
            )
        );

        return response()->json(
            $this->fieldPresenter->present(
                $response->getField()
            )
        );
    }

    public function getRecognizedDataByTaskKey(GetRecognizedDataHttpRequest $request): JsonResponse
    {
        $response = $this->getRecognizedDataByTaskKeyAction->execute(
            new GetRecognizedDataByTaskKeyRequest(
                $request->task_key
            )
        )->getResponse();
        return response()->json($response);
    }

    public function deleteDocument(string $id): JsonResponse
    {
        $this->deleteDocumentByIdAction->execute(
            new DeleteDocumentByIdRequest((int)$id)
        );

        return response()->json(null, 204);
    }
}
