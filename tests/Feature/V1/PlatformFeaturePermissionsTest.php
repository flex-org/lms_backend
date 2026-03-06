<?php

namespace Tests\Feature\V1;

use App\Modules\V1\Admins\Domain\Models\Admin;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PlatformFeaturePermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_platform_and_owner_receive_feature_permissions(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $featureIds = DB::table('features')
            ->orderBy('id')
            ->limit(2)
            ->pluck('id')
            ->toArray();

        $sellingSystemIds = DB::table('selling_systems')
            ->orderBy('id')
            ->limit(1)
            ->pluck('id')
            ->toArray();

        $payload = [
            'name' => 'Permissions Test LMS',
            'email' => 'owner-perms@example.com',
            'password' => 'password123',
            'phone' => '01000000002',
            'domain' => 'perms',
            'storage' => 50,
            'capacity' => 100,
            'mobile_app' => false,
            'selling_systems' => $sellingSystemIds,
            'features' => $featureIds,
        ];

        $response = $this->postJson('/api/v1/platform/create', $payload);
        $response->assertCreated();

        /** @var Platform $platform */
        $platform = Platform::where('domain', 'perms')->firstOrFail();

        foreach ($featureIds as $id) {
            $permissionName = 'feature-' . $id;
            $this->assertTrue($platform->hasPermissionTo($permissionName, 'sanctum'));
        }

        /** @var Admin $owner */
        $owner = Admin::where('email', 'owner-perms@example.com')->firstOrFail();

        foreach ($featureIds as $id) {
            $permissionName = 'feature-' . $id;
            $this->assertTrue($owner->hasPermissionTo($permissionName, 'admins'));
        }
    }
}

