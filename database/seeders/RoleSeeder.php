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
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admins']);

        $capabilities = config('features.admin_capabilities', []);

        foreach ($capabilities as $capability) {
            $perm = Permission::firstOrCreate([
                'name' => 'admin:' . $capability,
                'guard_name' => 'admins',
            ]);

            if (! $owner->hasPermissionTo($perm)) {
                $owner->givePermissionTo($perm);
            }
        }
    }
}
