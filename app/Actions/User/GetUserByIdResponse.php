<?php

namespace App\Actions\User;

use App\Models\User;

final class GetUserByIdResponse
{
    private ?User $user;

    public function __construct(?User $user)
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
