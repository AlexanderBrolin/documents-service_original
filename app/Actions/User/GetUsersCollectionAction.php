<?php

namespace App\Actions\User;

use App\Repositories\User\UserRepositoryInterface;

final class GetUsersCollectionAction
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(): GetUsersCollectionResponse
    {
        $users = $this->userRepository->getAll();

        return new GetUsersCollectionResponse($users);
    }
}
