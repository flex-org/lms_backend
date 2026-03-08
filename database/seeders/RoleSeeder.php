<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $owner = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'admins']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admins']);

        $allCapabilities = config('features.admin_capabilities', []);
        $ownerOnly = config('features.owner_only_capabilities', []);

        foreach ($allCapabilities as $capability) {
            $perm = Permission::firstOrCreate([
                'name' => 'admin:' . $capability,
                'guard_name' => 'admins',
            ]);

            if (! $owner->hasPermissionTo($perm)) {
                $owner->givePermissionTo($perm);
            }

            if (! in_array($capability, $ownerOnly) && ! $admin->hasPermissionTo($perm)) {
                $admin->givePermissionTo($perm);
            }
        }

        Permission::firstOrCreate([
            'name' => 'feature-builder',
            'guard_name' => 'admins',
        ]);
    }
}
