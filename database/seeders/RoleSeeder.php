<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::create([
            'name' => 'Admin',
            'description' => 'Administrator of the system',
            'created_by' => 1
        ]);

        \App\Models\Role::create([
            'name' => 'Approver',
            'description' => 'Approver of the transactions',
            'created_by' => 1
        ]);

        \App\Models\Role::create([
            'name' => 'User',
            'description' => 'Normal User',
            'created_by' => 1
        ]);

        \App\Models\Role::create([
            'name' => 'Guest',
            'description' => 'Views and tests the system',
            'created_by' => 1
        ]);
    }
}
