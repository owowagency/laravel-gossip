<?php

namespace OwowAgency\Gossip\Tests\Support\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run(): void
    {
        $admin = Role::create([
            'name' => \OwowAgency\Gossip\Tests\Support\Enumerations\Role::ADMIN,
        ]);

        $permissions = ['do all', 'view any message', 'view any conversation'];
        $adminPermissions = [];

        foreach ($permissions as $permission) {
            $adminPermissions[] = Permission::create([
                'name' => $permission,
            ]);
        }

        $admin->syncPermissions($adminPermissions);
    }
}
