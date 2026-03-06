<?php

namespace Tests\Feature\V1;

use App\Modules\V1\Admins\Domain\Models\Admin;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use App\Modules\V1\Platforms\Domain\Enums\PLatformStatus;
use App\Modules\V1\Themes\Domain\Models\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRolesPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_and_admin_roles_are_seeded(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $owner = \Spatie\Permission\Models\Role::where('name', 'owner')
            ->where('guard_name', 'admins')
            ->first();
        $admin = \Spatie\Permission\Models\Role::where('name', 'admin')
            ->where('guard_name', 'admins')
            ->first();

        $this->assertNotNull($owner);
        $this->assertNotNull($admin);
    }

    public function test_owner_can_have_more_permissions_than_admin(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $theme = Theme::first();
        $platform = Platform::create([
            'theme_id' => $theme->id,
            'domain' => 'roles-domain',
            'storage' => 100,
            'capacity' => 200,
            'has_mobile_app' => false,
            'cost' => 0,
            'status' => PLatformStatus::FREE_TRIAL,
        ]);

        $owner = Admin::create([
            'name' => 'Owner',
            'email' => 'owner-role@example.com',
            'phone' => '01000000006',
            'password' => 'password123',
            'platform_id' => $platform->id,
        ]);
        $owner->assignRole('owner');
        $owner->givePermissionTo('feature-1');

        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin-role@example.com',
            'phone' => '01000000007',
            'password' => 'password123',
            'platform_id' => $platform->id,
        ]);
        $admin->assignRole('admin');

        $this->assertTrue($owner->hasPermissionTo('feature-1', 'admins'));
        $this->assertFalse($admin->hasPermissionTo('feature-1', 'admins'));
    }
}
