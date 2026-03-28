<?php

namespace Tests\Feature\V1;

use App\Modules\V1\Platforms\Domain\Models\Platform;
use App\Modules\V1\Platforms\Domain\Enums\PLatformStatus;
use App\Modules\V1\Themes\Domain\Models\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileClientMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    private function createPlatform(string $domain, bool $hasMobileApp): Platform
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $theme = Theme::first();

        return Platform::create([
            'theme_id' => $theme->id,
            'domain' => $domain,
            'storage' => 100,
            'capacity' => 200,
            'has_mobile_app' => $hasMobileApp,
            'cost' => 0,
            'status' => PLatformStatus::FREE_TRIAL,
        ]);
    }

    public function test_mobile_header_rejected_when_platform_has_no_mobile_app(): void
    {
        $this->createPlatform('no-mobile.test', false);

        $response = $this->withHeader('domain', 'no-mobile.test')
            ->withHeader('X-Client-Type', 'mobile')
            ->getJson('/api/v1/test/mobile-client-ping');

        $response->assertForbidden();
    }

    public function test_mobile_header_allowed_when_platform_has_mobile_app(): void
    {
        $this->createPlatform('has-mobile.test', true);

        $response = $this->withHeader('domain', 'has-mobile.test')
            ->withHeader('X-Client-Type', 'mobile')
            ->getJson('/api/v1/test/mobile-client-ping');

        $response->assertOk();
    }

    public function test_web_header_allowed_without_mobile_app(): void
    {
        $this->createPlatform('web-only.test', false);

        $response = $this->withHeader('domain', 'web-only.test')
            ->withHeader('X-Client-Type', 'web')
            ->getJson('/api/v1/test/mobile-client-ping');

        $response->assertOk();
    }

    public function test_missing_client_type_header_defaults_to_web(): void
    {
        $this->createPlatform('default-web.test', false);

        $response = $this->withHeader('domain', 'default-web.test')
            ->getJson('/api/v1/test/mobile-client-ping');

        $response->assertOk();
    }

    public function test_invalid_client_type_returns_unprocessable(): void
    {
        $this->createPlatform('invalid-client.test', true);

        $response = $this->withHeader('domain', 'invalid-client.test')
            ->withHeader('X-Client-Type', 'tablet')
            ->getJson('/api/v1/test/mobile-client-ping');

        $response->assertUnprocessable();
    }
}
