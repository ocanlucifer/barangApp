<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset cahced roles and permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create']);
        Permission::create(['name' => 'edit']);
        Permission::create(['name' => 'delete']);

        //create roles and assign existing permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('create');
        $adminRole->givePermissionTo('edit');
        $adminRole->givePermissionTo('delete');

        $staffRole = Role::create(['name' => 'staff']);
        $staffRole->givePermissionTo('create');
        $staffRole->givePermissionTo('edit');

        // create users
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@chandra.com',
            'password' => bcrypt('12345678')
        ]);
        $user->assignRole($adminRole);

        $user = User::factory()->create([
            'name' => 'Staff',
            'email' => 'staff@chandra.com',
            'password' => bcrypt('12345678')
        ]);
        $user->assignRole($staffRole);

    }
}
