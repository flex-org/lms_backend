<?php

namespace Tests\Feature\V1;

use App\Modules\V1\Platforms\Domain\Enums\PLatformStatus;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use App\Modules\V1\Themes\Domain\Models\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthEndpointsTest extends TestCase
{
    use RefreshDatabase;

    private Platform $platform;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $theme = Theme::first();
        $this->platform = Platform::create([
            'theme_id' => $theme->id,
            'domain' => 'auth-test-domain',
            'storage' => 100,
            'capacity' => 200,
            'has_mobile_app' => false,
            'cost' => 0,
            'status' => PLatformStatus::FREE_TRIAL,
        ]);
    }

    public function test_signup_endpoint_exists(): void
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'user@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->withHeader('domain', $this->platform->domain)
            ->postJson('/api/v1/portal/signup', $payload);

        $this->assertContains($response->status(), [200, 201, 400, 422]);
    }

    public function test_login_endpoint_exists(): void
    {
        $loginPayload = [
            'email' => 'user@example.com',
            'password' => 'password123',
        ];

        $response = $this->withHeader('domain', $this->platform->domain)
            ->postJson('/api/v1/portal/login', $loginPayload);

        $this->assertContains($response->status(), [200, 400, 401, 422]);
    }
}

