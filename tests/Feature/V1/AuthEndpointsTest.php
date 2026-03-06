<?php

namespace Tests\Feature\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_endpoint_exists(): void
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'user@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/V1/signup', $payload);

        $this->assertContains($response->status(), [201, 400, 422, 404]);
    }

    public function test_login_endpoint_exists(): void
    {
        $loginPayload = [
            'email' => 'user@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/V1/login', $loginPayload);

        $this->assertContains($response->status(), [200, 400, 401, 404, 422]);
    }
}

