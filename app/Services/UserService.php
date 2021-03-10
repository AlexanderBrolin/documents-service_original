<?php

namespace App\Services;

use App\Constants\Roles;
use App\Exceptions\User\BlockDeveloperException;
use App\Exceptions\User\BlockUserWithSameRoleException;
use App\Exceptions\User\BlockYourselfException;
use App\Exceptions\User\DeleteDeveloperException;
use App\Exceptions\User\DeleteUserWithSameRoleException;
use App\Exceptions\User\DeleteYourselfException;
use App\Exceptions\User\UserNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public const DELETE_ACTION = 'delete';
    public const BLOCK_ACTION = 'block';

    public static function isAvailableToBlockOrDeleteUser($user, string $action): void
    {
        if (!$user) {
            throw new UserNotFoundException();
        }

        $authUser = Auth::user();

        if (!$authUser) {
            throw new AuthenticationException();
        }

        if ((int)$user->id === (int)$authUser->id) {
            if ($action === self::DELETE_ACTION) {
                throw new DeleteYourselfException();
            }
            if ($action === self::BLOCK_ACTION) {
                throw new BlockYourselfException();
            }
        }

        if ($user->getRole()->alias === $authUser->getRole()->alias) {
            if ($action === self::DELETE_ACTION) {
                throw new DeleteUserWithSameRoleException();
            }
            if ($action === self::BLOCK_ACTION) {
                throw new BlockUserWithSameRoleException();
            }
        }

        if (
            $user->getRole()->alias === Roles::DEVELOPER_ALIAS
            && $authUser->getRole()->alias === Roles::ADMINISTRATOR_ALIAS
        ) {
            if ($action === self::DELETE_ACTION) {
                throw new DeleteDeveloperException();
            }
            if ($action === self::BLOCK_ACTION) {
                throw new BlockDeveloperException();
            }
        }
    }
}
