<?php

namespace App\Actions\User;

use App\Repositories\User\UserRepositoryInterface;
use App\Services\UserService;

final class DeleteUserByIdAction
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(DeleteUserByIdRequest $request): void
    {
        $user = $this->userRepository->findById($request->getId());

        UserService::isAvailableToBlockOrDeleteUser($user, 'delete');

        $this->userRepository->deleteById($request->getId());
    }
}
