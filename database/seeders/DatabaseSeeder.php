<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(DeveloperSeeder::class);
        $this->call(AdministratorSeeder::class);
        $this->call(ManagerSeeder::class);
        $this->call(WorkerSeeder::class);
    }
}
