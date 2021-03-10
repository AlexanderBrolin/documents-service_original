<?php

namespace App\Actions\Individual;

use App\Exceptions\Individual\IndividualNotFoundException;
use App\Repositories\Individual\IndividualRepositoryInterface;

final class GetIndividualByIdAction
{
    private IndividualRepositoryInterface $individualRepository;

    public function __construct(IndividualRepositoryInterface $individualRepository)
    {
        $this->individualRepository = $individualRepository;
    }

    public function execute(GetIndividualByIdRequest $request): GetIndividualByIdResponse
    {
        $individual = $this->individualRepository->findById($request->getId());

        if (!$individual) {
            throw new IndividualNotFoundException();
        }

        return new GetIndividualByIdResponse($individual);
    }
}
