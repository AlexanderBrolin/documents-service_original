<?php

namespace App\Http\Controllers;

use App\Actions\Individual\GetIndividualByIdAction;
use App\Actions\Individual\GetIndividualByIdRequest;
use App\Actions\Individual\GetIndividualCollectionAction;
use App\Actions\Individual\SaveIndividualAction;
use App\Actions\Individual\SaveIndividualRequest;
use App\Actions\Individual\SearchIndividualAction;
use App\Actions\Individual\SearchIndividualRequest;
use App\Presenters\IndividualPresenter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \Illuminate\Http\JsonResponse;

class IndividualsController extends Controller
{
    private IndividualPresenter $individualPresenter;
    private GetIndividualCollectionAction $getIndividualsAction;
    private GetIndividualByIdAction $getIndividualByIdAction;
    private SearchIndividualAction $searchIndividualAction;
    private SaveIndividualAction $saveIndividualAction;

    public function __construct(
        IndividualPresenter $individualPresenter,
        GetIndividualCollectionAction $getIndividualsAction,
        GetIndividualByIdAction $getIndividualByIdAction,
        SearchIndividualAction $searchIndividualAction,
        SaveIndividualAction $saveIndividualAction
    ) {
        $this->individualPresenter = $individualPresenter;
        $this->getIndividualsAction = $getIndividualsAction;
        $this->getIndividualByIdAction = $getIndividualByIdAction;
        $this->searchIndividualAction = $searchIndividualAction;
        $this->saveIndividualAction = $saveIndividualAction;
    }

    public function individualsView()
    {
        return view('individuals');
    }

    public function getIndividuals(): JsonResponse
    {
        $individuals = $this->getIndividualsAction->execute()->getIndividuals();

        return response()->json(
            $this->individualPresenter->presentCollection($individuals)
        );
    }

    public function watchById(string $id)
    {
        return view('individual', [
            'id' => $id
        ]);
    }

    public function getIndividualById(string $id): JsonResponse
    {
        $individual = $this->getIndividualByIdAction->execute(
            new GetIndividualByIdRequest((int)$id)
        )->getIndividual();

        return response()->json(
            $this->individualPresenter->present($individual)
        );
    }

    public function save(Request $request): JsonResponse
    {
        $response = $this->saveIndividualAction->execute(
            new SaveIndividualRequest($request->payloadData)
        )->getResponse();

        return response()->json($response);
    }

    public function search(Request $request): JsonResponse
    {
        $response = $this->searchIndividualAction->execute(
            new SearchIndividualRequest(
                Str::lower($request->name),
                Str::lower($request->surname),
                Str::lower($request->patronymic),
                $request->snils,
                $request->inn,
                $request->passport,
            )
        );

        return response()->json($response->getIndividuals());
    }
}
