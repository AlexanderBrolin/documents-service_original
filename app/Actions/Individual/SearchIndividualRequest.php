<?php

namespace App\Actions\Individual;

final class SearchIndividualRequest
{
    private ?string $firstName;
    private ?string $secondName;
    private ?string $patronymic;
    private ?string $snilsNumber;
    private ?string $innNumber;
    private ?string $passportNumber;

    public function __construct(
        ?string $firstName,
        ?string $secondName,
        ?string $patronymic,
        ?string $snilsNumber,
        ?string $innNumber,
        ?string $passportNumber
    ) {
        $this->firstName = $firstName;
        $this->secondName = $secondName;
        $this->patronymic = $patronymic;
        $this->snilsNumber = $snilsNumber;
        $this->innNumber = $innNumber;
        $this->passportNumber = $passportNumber;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getSecondName(): ?string
    {
        return $this->secondName;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function getSnilsNumber(): ?string
    {
        return $this->snilsNumber;
    }

    public function getInnNumber(): ?string
    {
        return $this->innNumber;
    }

    public function getPassportNumber(): ?string
    {
        return $this->passportNumber;
    }
}
