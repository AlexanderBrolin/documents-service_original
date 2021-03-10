<?php

namespace App\Http\Controllers;

use App\Actions\User\AddUserAction;
use App\Actions\User\BlockUserByIdAction;
use App\Actions\User\BlockUserByIdRequest;
use App\Actions\User\DeleteUserByIdAction;
use App\Actions\User\DeleteUserByIdRequest;
use App\Actions\User\GetUserByIdAction;
use App\Actions\User\GetUserByIdRequest;
use App\Actions\User\GetUsersCollectionAction;
use App\Http\Requests\CreateUserRequest;
use \Illuminate\Http\JsonResponse;
use \Illuminate\Http\RedirectResponse;
use \App\Actions\User\AddUserRequest;

class UsersController extends Controller
{
    private AddUserAction $addUserAction;
    private GetUsersCollectionAction $getUsersCollectionAction;
    private GetUserByIdAction $getUserByIdAction;
    private DeleteUserByIdAction $deleteUserByIdAction;
    private BlockUserByIdAction $blockUserByIdAction;

    public function __construct(
        AddUserAction $addUserAction,
        GetUsersCollectionAction $getUsersCollectionAction,
        GetUserByIdAction $getUserByIdAction,
        DeleteUserByIdAction $deleteUserByIdAction,
        BlockUserByIdAction $blockUserByIdAction
    ) {
        $this->addUserAction = $addUserAction;
        $this->getUsersCollectionAction = $getUsersCollectionAction;
        $this->getUserByIdAction = $getUserByIdAction;
        $this->deleteUserByIdAction = $deleteUserByIdAction;
        $this->blockUserByIdAction = $blockUserByIdAction;
    }

    public function getAllUsers(): JsonResponse
    {
        $users = $this->getUsersCollectionAction->execute()->getUsers();

        return response()->json($users);
    }

    public function usersList()
    {
        return view('users');
    }

    public function getUserById(string $id)
    {
        $user = $this->getUserByIdAction->execute(
            new GetUserByIdRequest((int)$id)
        );

        return view('user', [
            'user' => $user
        ]);
    }

    public function createUser(CreateUserRequest $request): RedirectResponse
    {
        $this->addUserAction->execute(
            new AddUserRequest(
                $request->first_name,
                $request->second_name,
                $request->patronymic,
                $request->email,
                $request->password,
                $request->department,
                $request->role
            )
        );

        return redirect()->route('editor');
    }

    public function deleteUser(string $id): JsonResponse
    {
        $this->deleteUserByIdAction->execute(
            new DeleteUserByIdRequest((int)$id)
        );

        return response()->json();
    }

    public function changeUserBlockStatus(string $id): JsonResponse
    {
        $response = $this->blockUserByIdAction->execute(
            new BlockUserByIdRequest((int)$id)
        );

        return response()->json($response->getUser());
    }
}
