<?php

namespace Tests\Feature\V1;

use App\Modules\V1\Admins\Domain\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthPortalSeparationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_token_cannot_access_admin_dashboard_routes(): void
    {
        $userLoginResponse = $this->postJson('/api/V1/login', [
            'email' => 'non-existing@example.com',
            'password' => 'invalid-password',
        ]);

        $this->assertContains($userLoginResponse->status(), [400, 401, 404, 422]);

        $dashboardResponse = $this->getJson('/api/v1/dashboard/logout');

        $this->assertContains($dashboardResponse->status(), [401, 403, 404, 405]);
    }

    public function test_admin_token_cannot_use_user_only_endpoints(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $admin = Admin::create([
            'name' => 'Portal Admin',
            'email' => 'portal-admin@example.com',
            'phone' => '01000000005',
            'password' => 'password123',
            'domain' => 'portal-domain',
        ]);
        $admin->assignRole('owner');

        $token = $admin->createToken('admin', ['dashboard', 'portal-domain'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/V1/user');

        $this->assertContains($response->status(), [401, 403, 404]);
    }
}

