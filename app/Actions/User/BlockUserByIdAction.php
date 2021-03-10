<?php

namespace App\Actions\User;

use App\Exceptions\User\UserNotFoundException;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\UserService;

final class BlockUserByIdAction
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(BlockUserByIdRequest $request): BlockUserByIdResponse
    {
        $user = $this->userRepository->findById($request->getId());

        if (!$user) {
            throw new UserNotFoundException();
        }

        UserService::isAvailableToBlockOrDeleteUser($user, UserService::BLOCK_ACTION);

        $user->is_blocked = !$user->is_blocked;
        $user = $this->userRepository->save($user);

        return new BlockUserByIdResponse($user);
    }
}
