<?php

namespace Database\Seeders;

use App\Constants\Roles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'first_name' => 'Главный',
            'second_name' => 'Руководитель',
            'patronymic' => '',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('123123123'),
            'unhashed_password' => '123123123',
            'department' => 'managers'
        ]);
        $role = Role::where('alias', '=', Roles::MANAGER_ALIAS)->get()->first();
        $user->role()->attach($role);
    }
}
