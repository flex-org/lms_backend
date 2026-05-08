<?php

namespace App\Modules\V1\Platforms\Application\UseCases;

use App\Modules\V1\Features\Domain\Models\Feature;
use App\Modules\V1\Features\Domain\Repositories\FeatureRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class RemoveFeatureUseCase
{
    public function __construct(
        public FeatureRepositoryInterface $featureRepository
    )
    {
    }

    public function execute(Platform $platform, string $key): void
    {
        $feature = $this->featureRepository->findOrFailByKey($key);
        DB::transaction(function () use ($platform, $feature) {
            $platform->features()->detach($feature->id);
            if ($feature->price) {
                $platform->cost = max(0, (float) $platform->cost - (float) $feature->price);
                $platform->save();
            }
        });
    }
}

