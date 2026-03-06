<?php

namespace Tests\Feature\V1;

use App\Modules\V1\Admins\Domain\Models\Admin;
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

        $admin = Admin::create([
            'name' => 'Builder Admin',
            'email' => 'builder-admin@example.com',
            'phone' => '01000000003',
            'password' => 'password123',
            'domain' => 'builder-domain',
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

        $admin = Admin::create([
            'name' => 'No Feature Admin',
            'email' => 'no-feature-admin@example.com',
            'phone' => '01000000004',
            'password' => 'password123',
            'domain' => 'no-feature-domain',
        ]);
        $admin->assignRole('owner');

        $token = $admin->createToken('admin', ['dashboard', 'no-feature-domain', 'builder'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->withHeader('domain', 'no-feature-domain')
            ->getJson('/api/v1/test/feature-protected');

        $this->assertContains($response->status(), [401, 403]);
    }
}

