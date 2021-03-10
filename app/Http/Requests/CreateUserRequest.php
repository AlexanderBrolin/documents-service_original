<?php

namespace App\Http\Requests;

use App\Constants\Roles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'second_name' => 'required|string',
            'patronymic' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
            'department' => 'required|string',
            'role' => [
                'required', 'string',
                Rule::in([
                    Roles::ADMINISTRATOR_ALIAS,
                    Roles::MANAGER_ALIAS,
                    Roles::DEVELOPER_ALIAS,
                    Roles::WORKER_ALIAS
                ])
            ]
        ];
    }
}
