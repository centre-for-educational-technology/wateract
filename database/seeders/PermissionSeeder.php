<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'administrate']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'edit spring']);
        Permission::create(['name' => 'confirm spring']);
        Permission::create(['name' => 'delete spring']);
        Permission::create(['name' => 'edit observation']);
        Permission::create(['name' => 'delete observation']);
        Permission::create(['name' => 'add measurement']);
        Permission::create(['name' => 'edit measurement']);
        Permission::create(['name' => 'delete measurement']);

        // create roles and assign existing permissions
        $editor_role = Role::create(['name' => 'editor']);
        $editor_role->givePermissionTo('edit spring');
        $editor_role->givePermissionTo('confirm spring');
        $editor_role->givePermissionTo('delete spring');
        $editor_role->givePermissionTo('edit observation');
        $editor_role->givePermissionTo('delete observation');
        $editor_role->givePermissionTo('add measurement');
        $editor_role->givePermissionTo('edit measurement');
        $editor_role->givePermissionTo('delete measurement');

        $admin_role = Role::create(['name' => 'admin']);
        $admin_role->givePermissionTo('view users');
        $admin_role->givePermissionTo('edit user');
        $admin_role->givePermissionTo('edit spring');
        $admin_role->givePermissionTo('confirm spring');
        $admin_role->givePermissionTo('delete spring');
        $admin_role->givePermissionTo('edit observation');
        $admin_role->givePermissionTo('delete observation');
        $admin_role->givePermissionTo('add measurement');
        $admin_role->givePermissionTo('edit measurement');
        $admin_role->givePermissionTo('delete measurement');

        $super_admin_role = Role::create(['name' => 'super-admin']);
        $super_admin_role->givePermissionTo('administrate');
        $super_admin_role->givePermissionTo('view users');
        $super_admin_role->givePermissionTo('edit user');
        $super_admin_role->givePermissionTo('edit spring');
        $super_admin_role->givePermissionTo('confirm spring');
        $super_admin_role->givePermissionTo('delete spring');
        $super_admin_role->givePermissionTo('edit observation');
        $super_admin_role->givePermissionTo('delete observation');
        $super_admin_role->givePermissionTo('add measurement');
        $super_admin_role->givePermissionTo('edit measurement');
        $super_admin_role->givePermissionTo('delete measurement');

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Example User',
            'email' => 'test@example.com',
            'password' => Hash::make('testkasutaja'),
        ]);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Editor User',
            'email' => 'editor@example.com',
            'password' => Hash::make('editorkasutaja'),
        ]);
        $user->assignRole($editor_role);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('adminkasutaja'),
        ]);
        $user->assignRole($admin_role);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Super-Admin User',
            'email' => 'ayly@tlu.ee',
            'password' => Hash::make('superadminkasutaja'),
        ]);
        $user->assignRole($super_admin_role);
    }
}
