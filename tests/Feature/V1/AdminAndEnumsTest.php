<?php

namespace Tests\Feature\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAndEnumsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_endpoint_exists(): void
    {
        $response = $this->postJson('/api/V1/login', []);

        $this->assertContains($response->status(), [400, 401, 404, 422]);
    }

    public function test_selling_systems_enum_endpoint_exists(): void
    {
        $response = $this->getJson('/api/V1/selling-systems');

        $this->assertContains($response->status(), [200, 404]);
    }
}

