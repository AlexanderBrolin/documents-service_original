<?php

namespace App\Actions\Individual;

use App\Repositories\Document\Criterion\FioCriterion;
use App\Repositories\Document\Criterion\FirstNameCriterion;
use App\Repositories\Document\Criterion\InnNumberCriterion;
use App\Repositories\Document\Criterion\PassportNumberCriterion;
use App\Repositories\Document\Criterion\PatronymicCriterion;
use App\Repositories\Document\Criterion\SecondNameCriterion;
use App\Repositories\Document\Criterion\SnilsNumberCriterion;
use App\Repositories\Document\DocumentRepositoryInterface;

final class SearchIndividualAction
{
    private DocumentRepositoryInterface $documentRepository;

    public function __construct(DocumentRepositoryInterface $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    public function execute(SearchIndividualRequest $request): SearchIndividualResponse
    {
        $criteria = [];
        if ($request->getFirstName()) {
            $criteria[] = new FirstNameCriterion($request->getFirstName());
        }

        if ($request->getSecondName()) {
            $criteria[] = new SecondNameCriterion($request->getSecondName());
        }

        if ($request->getPatronymic()) {
            $criteria[] = new PatronymicCriterion($request->getPatronymic());
        }

        if ($request->getSnilsNumber()) {
            $criteria[] = new SnilsNumberCriterion($request->getSnilsNumber());
        }

        if ($request->getInnNumber()) {
            $criteria[] = new InnNumberCriterion($request->getInnNumber());
        }

        if ($request->getPassportNumber()) {
            $criteria[] = new PassportNumberCriterion($request->getPassportNumber());
        }

        $individuals = $this->documentRepository->findByCriteria(...$criteria)
            ->map(fn($document) => $document->individual)
            ->unique('id');

        $fio = trim($request->getSecondName()
            . ' ' .
            $request->getFirstName()
            . ' ' .
            $request->getPatronymic()
        );

        $criteria = [new FioCriterion($fio)];
        $individualsNext = $this->documentRepository->findByCriteria(...$criteria)
            ->map(fn($document) => $document->individual)
            ->unique('id');

        $individuals = $individuals->merge($individualsNext);

        return new SearchIndividualResponse($individuals);
    }
}
