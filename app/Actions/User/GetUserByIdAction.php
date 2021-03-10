<?php

namespace App\Actions\User;

use App\Repositories\User\UserRepositoryInterface;

final class GetUserByIdAction
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(GetUserByIdRequest $request): GetUserByIdResponse
    {
        $user = $this->userRepository->findById($request->getId());

        return new GetUserByIdResponse($user);
    }
}
