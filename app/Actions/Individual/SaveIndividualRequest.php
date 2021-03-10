<?php

namespace App\Actions\Individual;

final class SaveIndividualRequest
{
    private array $payloadData;

    public function __construct(array $payloadData)
    {
        $this->payloadData = $payloadData;
    }

    public function getPayloadData(): array
    {
        return $this->payloadData;
    }
}
