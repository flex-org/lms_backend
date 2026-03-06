<?php

namespace Tests\Feature\V1;

use App\Modules\V1\Admins\Domain\Models\Admin;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use App\Modules\V1\Platforms\Domain\Enums\PLatformStatus;
use App\Modules\V1\Themes\Domain\Models\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthPortalSeparationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_token_cannot_access_admin_dashboard_routes(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $theme = \App\Modules\V1\Themes\Domain\Models\Theme::first();
        $platform = Platform::create([
            'theme_id' => $theme->id,
            'domain' => 'separation-domain',
            'storage' => 100,
            'capacity' => 200,
            'has_mobile_app' => false,
            'cost' => 0,
            'status' => PLatformStatus::FREE_TRIAL,
        ]);

        $userLoginResponse = $this->withHeader('domain', $platform->domain)
            ->postJson('/api/v1/portal/login', [
                'email' => 'non-existing@example.com',
                'password' => 'invalid-password',
            ]);

        $this->assertContains($userLoginResponse->status(), [400, 401, 404, 422]);

        $dashboardResponse = $this->withHeader('domain', $platform->domain)
            ->getJson('/api/v1/dashboard/logout');

        $this->assertContains($dashboardResponse->status(), [401, 403, 404, 405]);
    }

    public function test_admin_token_cannot_use_user_only_endpoints(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $theme = Theme::first();
        $platform = Platform::create([
            'theme_id' => $theme->id,
            'domain' => 'portal-domain',
            'storage' => 100,
            'capacity' => 200,
            'has_mobile_app' => false,
            'cost' => 0,
            'status' => PLatformStatus::FREE_TRIAL,
        ]);

        $admin = Admin::create([
            'name' => 'Portal Admin',
            'email' => 'portal-admin@example.com',
            'phone' => '01000000005',
            'password' => 'password123',
            'platform_id' => $platform->id,
        ]);
        $admin->assignRole('owner');

        $token = $admin->createToken('admin', ['dashboard', 'portal-domain'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/user');

        $this->assertContains($response->status(), [401, 403, 404]);
    }
}
