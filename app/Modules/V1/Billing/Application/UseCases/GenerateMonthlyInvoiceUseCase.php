<?php

namespace App\Modules\V1\Billing\Application\UseCases;

use App\Modules\V1\Billing\Domain\Services\BillingCycleService;
use App\Modules\V1\Platforms\Domain\Enums\PLatformStatus;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Carbon\CarbonInterface;

final readonly class GenerateMonthlyInvoiceUseCase
{
    public function __construct(
        private BillingCycleService $billingCycleService,
    ) {
    }

    public function execute(Platform $platform, CarbonInterface $now): void
    {
        if ($platform->status !== PLatformStatus::ACTIVE) {
            return;
        }

        $periodStart = $now->copy();
        $periodEnd = $now->copy()->addDays(30);

        $this->billingCycleService->generateMonthlyInvoice($platform, $periodStart, $periodEnd);

        $platform->renew_at = $periodEnd->toDateString();
        $platform->status = PLatformStatus::PENDING;
        $platform->save();
    }
}

