<?php

namespace App\Actions\User;

use App\Exceptions\User\UserWithEmailAlreadyExistsException;
use App\Models\User;
use App\Repositories\Role\Criterion\AliasCriterion;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\User\Criterion\EmailCriterion;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

final class AddUserAction
{
    private UserRepositoryInterface $userRepository;
    private RoleRepositoryInterface $roleRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function execute(AddUserRequest $request): void
    {
        $criteria = [new EmailCriterion($request->getEmail())];
        $users = $this->userRepository->findByCriteria(...$criteria);

        if ($users->count()) {
            throw new UserWithEmailAlreadyExistsException();
        }

        $user = new User([
            'first_name' => $request->getFirstName(),
            'second_name' => $request->getSecondName(),
            'patronymic' => $request->getPatronymic(),
            'email' => $request->getEmail(),
            'unhashed_password' => $request->getPassword(),
            'password' => Hash::make($request->getPassword()),
            'department' => $request->getDepartment(),
        ]);
        $user = $this->userRepository->save($user);

        $roleCriteria = [new AliasCriterion($request->getRoleAlias())];
        $role = $this->roleRepository->findOneByCriteria(...$roleCriteria);

        $this->userRepository->attachRole($user, $role);
    }
}
