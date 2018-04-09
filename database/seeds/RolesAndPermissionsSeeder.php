<?php

use App\Models\Permission;
use App\Models\Role;

class RolesAndPermissionsSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = config('permission.list');
        foreach ($permissions as $permission) {
            Permission::query()->create([
                'name'       => $permission,
                'guard_name' => 'api'
            ]);
        }

        /** @var Role $admin */
        $admin = Role::query()->create([
            'name'       => 'admin',
            'guard_name' => 'api'
        ]);

        Role::query()->create([
            'name'       => 'user',
            'guard_name' => 'api'
        ]);

        $admin->givePermissionTo($permissions);
    }
}
