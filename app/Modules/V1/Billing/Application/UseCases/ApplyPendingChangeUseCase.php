<?php

namespace App\Modules\V1\Billing\Application\UseCases;

use App\Modules\V1\Billing\Domain\Enums\PendingChangeStatus;
use App\Modules\V1\Billing\Domain\Enums\PendingChangeType;
use App\Modules\V1\Billing\Domain\Models\PlatformPendingChange;
use App\Modules\V1\Features\Domain\Models\Feature;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class ApplyPendingChangeUseCase
{
    public function execute(PlatformPendingChange $pendingChange): void
    {
        if ($pendingChange->status !== PendingChangeStatus::PENDING) {
            return;
        }

        DB::transaction(function () use ($pendingChange) {
            /** @var Platform $platform */
            $platform = $pendingChange->platform()->lockForUpdate()->first();
            $payload = $pendingChange->payload ?? [];

            switch ($pendingChange->change_type) {
                case PendingChangeType::ADD_FEATURE:
                    $feature = Feature::findOrFail($payload['feature_id']);
                    $platform->features()->attach($feature->id, ['price' => $feature->price, 'is_active' => true]);
                    $platform->cost = (float) $platform->cost + (float) $feature->price;
                    break;

                case PendingChangeType::INCREASE_STORAGE:
                    $platform->storage = $payload['new_storage'];
                    $platform->cost = (float) $platform->cost + ($payload['price_diff'] ?? 0);
                    break;

                case PendingChangeType::INCREASE_CAPACITY:
                    $platform->capacity = $payload['new_capacity'];
                    $platform->cost = (float) $platform->cost + ($payload['price_diff'] ?? 0);
                    break;

                case PendingChangeType::ENABLE_MOBILE:
                    $platform->has_mobile_app = true;
                    $platform->cost = (float) $platform->cost + ($payload['price_diff'] ?? 0);
                    break;
            }

            $platform->save();

            $pendingChange->status = PendingChangeStatus::APPLIED;
            $pendingChange->save();
        });
    }
}

