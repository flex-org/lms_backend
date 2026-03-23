<?php

namespace App\Modules\V1\Billing\Application\UseCases;

use App\Modules\V1\Billing\Domain\Services\BillingCycleService;
use App\Modules\V1\Platforms\Domain\Enums\PLatformStatus;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Carbon\CarbonInterface;

final readonly class GenerateFirstInvoiceUseCase
{
    public function __construct(
        private BillingCycleService $billingCycleService,
    ) {
    }

    public function execute(Platform $platform, CarbonInterface $now): void
    {
        if ($platform->status !== PLatformStatus::FREE_TRIAL) {
            return;
        }

        $periodStart = $now->copy();
        $periodEnd = $now->copy()->addDays(30);

        $this->billingCycleService->generateMonthlyInvoice($platform, $periodStart, $periodEnd);

        $platform->status = PLatformStatus::PENDING;
        $platform->started_at = $periodStart->toDateString();
        $platform->renew_at = $periodEnd->toDateString();
        $platform->save();
    }
}

