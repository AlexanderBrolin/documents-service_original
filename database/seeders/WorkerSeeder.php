<?php

namespace Database\Seeders;

use App\Constants\Roles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WorkerSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'first_name' => 'Сотрудник',
            'second_name' => 'Номер 1',
            'patronymic' => '',
            'email' => 'worker1@gmail.com',
            'password' => Hash::make('123123123'),
            'unhashed_password' => '123123123',
            'department' => 'workers'
        ]);
        $role = Role::where('alias', '=', Roles::WORKER_ALIAS)->get()->first();
        $user->role()->attach($role);
    }
}
