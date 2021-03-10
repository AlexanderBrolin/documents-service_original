<?php

namespace Database\Seeders;

use App\Constants\Roles;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{

    public function run()
    {
        foreach (Roles::getAllRoles() as $role) {
            (
                new Role([
                    'name' => $role['name'],
                    'alias' => $role['alias']
                ])
            )->save();
        }
    }
}
