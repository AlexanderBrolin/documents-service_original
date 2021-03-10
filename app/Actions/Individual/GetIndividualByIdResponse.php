<?php

namespace App\Actions\Individual;

use App\Models\Individual;

final class GetIndividualByIdResponse
{
    private ?Individual $individual;

    public function __construct(?Individual $individual)
    {
        $this->individual = $individual;
    }

    public function getIndividual(): ?Individual
    {
        return $this->individual;
    }
}
