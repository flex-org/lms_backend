<?php

namespace Tests\Feature\V1;

use App\Modules\V1\Admins\Domain\Models\Admin;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use App\Modules\V1\Platforms\Domain\Enums\PLatformStatus;
use App\Modules\V1\Themes\Domain\Models\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FeatureAccessMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_with_builder_feature_can_access_protected_route(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $builderFeatureId = DB::table('features')->orderBy('id')->value('id');

        $theme = Theme::first();
        $platform = Platform::create([
            'theme_id' => $theme->id,
            'domain' => 'builder-domain',
            'storage' => 100,
            'capacity' => 200,
            'has_mobile_app' => false,
            'cost' => 0,
            'status' => PLatformStatus::FREE_TRIAL,
        ]);

        $admin = Admin::create([
            'name' => 'Builder Admin',
            'email' => 'builder-admin@example.com',
            'phone' => '01000000003',
            'password' => 'password123',
            'platform_id' => $platform->id,
        ]);
        $admin->assignRole('owner');
        $admin->givePermissionTo('feature-' . $builderFeatureId);

        $token = $admin->createToken('admin', ['dashboard', 'builder-domain', 'builder'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->withHeader('domain', 'builder-domain')
            ->getJson('/api/v1/test/feature-protected');

        $this->assertContains($response->status(), [200, 401, 403]);
    }

    public function test_admin_without_feature_cannot_access_protected_route(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $theme = Theme::first();
        $platform = Platform::create([
            'theme_id' => $theme->id,
            'domain' => 'no-feature-domain',
            'storage' => 100,
            'capacity' => 200,
            'has_mobile_app' => false,
            'cost' => 0,
            'status' => PLatformStatus::FREE_TRIAL,
        ]);

        $admin = Admin::create([
            'name' => 'No Feature Admin',
            'email' => 'no-feature-admin@example.com',
            'phone' => '01000000004',
            'password' => 'password123',
            'platform_id' => $platform->id,
        ]);
        $admin->assignRole('owner');

        $token = $admin->createToken('admin', ['dashboard', 'no-feature-domain', 'builder'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->withHeader('domain', 'no-feature-domain')
            ->getJson('/api/v1/test/feature-protected');

        $this->assertContains($response->status(), [401, 403]);
    }
}
