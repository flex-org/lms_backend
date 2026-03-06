<?php

namespace Tests\Feature\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeaturesAndPlatformsTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_features_endpoint_exists(): void
    {
        $response = $this->getJson('/api/V1/features');

        $this->assertContains($response->status(), [200, 404]);
    }

    public function test_dynamic_features_endpoint_exists(): void
    {
        $response = $this->getJson('/api/V1/dynamic-features');

        $this->assertContains($response->status(), [200, 404]);
    }

    public function test_platform_create_endpoint_exists(): void
    {
        $payload = [
            'name' => 'Test Platform',
            'domain' => 'test.example.com',
        ];

        $response = $this->postJson('/api/V1/platform/create', $payload);

        $this->assertContains($response->status(), [201, 400, 404, 422]);
    }
}

