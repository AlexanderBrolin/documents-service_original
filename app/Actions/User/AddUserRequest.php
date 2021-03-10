<?php

namespace App\Actions\User;

final class AddUserRequest
{
    private string $firstName;
    private string $secondName;
    private string $patronymic;
    private string $email;
    private string $password;
    private string $department;
    private string $roleAlias;

    public function __construct(
        string $firstName,
        string $secondName,
        string $patronymic,
        string $email,
        string $password,
        string $department,
        string $roleAlias
    ) {
        $this->firstName = $firstName;
        $this->secondName = $secondName;
        $this->patronymic = $patronymic;
        $this->email = $email;
        $this->password = $password;
        $this->department = $department;
        $this->roleAlias = $roleAlias;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getSecondName(): string
    {
        return $this->secondName;
    }

    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function getRoleAlias(): string
    {
        return $this->roleAlias;
    }
}
