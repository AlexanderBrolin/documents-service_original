<?php

namespace App\Actions\Individual;

use App\Repositories\Individual\Criterion\HasDocumentsCriterion;
use App\Repositories\Individual\Criterion\OrderByCriterion;
use App\Repositories\Individual\IndividualRepositoryInterface;

final class GetIndividualCollectionAction
{
    private IndividualRepositoryInterface $individualRepository;

    public function __construct(IndividualRepositoryInterface $individualRepository)
    {
        $this->individualRepository = $individualRepository;
    }

    public function execute(): GetIndividualCollectionResponse
    {
        $criteria = [
            new HasDocumentsCriterion(),
            new OrderByCriterion(),
        ];

        $individuals = $this->individualRepository->findByCriteria(...$criteria);

        return new GetIndividualCollectionResponse($individuals);
    }
}
