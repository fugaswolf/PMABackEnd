<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin', 'manager', 'consultant', 'hr', 'worker'];

        foreach ($roles as $role) {
            factory(Role::class)->create([
                'name' => $role
            ]);
        }
    }
}
