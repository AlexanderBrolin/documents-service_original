<?php

namespace Database\Seeders;

use App\Constants\Roles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DeveloperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'Dmitriy',
            'second_name' => 'Uvin',
            'patronymic' => 'Igorevich',
            'email' => 'developer@developer.com',
            'password' => Hash::make('password'),
            'unhashed_password' => 'password',
            'department' => 'dev'
        ]);
        $role = Role::where('alias', '=', Roles::DEVELOPER_ALIAS)->get()->first();
        $user->role()->attach($role);
    }
}
