<?php

namespace Tests\Feature\V1;

use App\Modules\V1\Platforms\Domain\Models\Platform;
use App\Modules\V1\Platforms\Domain\Services\PlatformPricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PlatformCreationPricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_platform_creation_persists_relations_and_pricing(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $featureIds = DB::table('features')
            ->orderBy('id')
            ->limit(2)
            ->pluck('id')
            ->toArray();

        $sellingSystemIds = DB::table('selling_systems')
            ->orderBy('id')
            ->limit(2)
            ->pluck('id')
            ->toArray();

        $payload = [
            'name' => 'Acme LMS',
            'email' => 'owner@example.com',
            'password' => 'password123',
            'phone' => '01000000001',
            'domain' => 'acme',
            'storage' => 50,
            'capacity' => 100,
            'mobile_app' => true,
            'selling_systems' => $sellingSystemIds,
            'features' => $featureIds,
        ];

        $response = $this->postJson('/api/v1/platform/create', $payload);

        $response->assertCreated();
        $response->assertJsonStructure([
            'success',
            'data' => [
                'platform_url',
                'token',
            ],
        ]);

        /** @var Platform $platform */
        $platform = Platform::where('domain', 'acme')->firstOrFail();

        $this->assertSame(50, $platform->storage);
        $this->assertSame(100, $platform->capacity);
        $this->assertTrue((bool) $platform->has_mobile_app);

        $attachedFeatureIds = $platform->features()->pluck('features.id')->toArray();
        $this->assertEqualsCanonicalizing($featureIds, $attachedFeatureIds);

        $attachedSellingSystemIds = $platform->sellingSystems()->pluck('selling_systems.id')->toArray();
        $this->assertEqualsCanonicalizing($sellingSystemIds, $attachedSellingSystemIds);

        $featuresForPricing = DB::table('features')
            ->whereIn('id', $featureIds)
            ->get();

        /** @var PlatformPricingService $pricingService */
        $pricingService = app(PlatformPricingService::class);

        $expectedCost = $pricingService->calculate($featuresForPricing, [
            'storage' => 50,
            'capacity' => 100,
            'mobile_app' => true,
        ]);

        $this->assertEquals($expectedCost, $platform->cost);
    }
}

