<?php

namespace App\Modules\V1\Billing\Application\Jobs;

use App\Modules\V1\Billing\Application\UseCases\GenerateFirstInvoiceUseCase;
use App\Modules\V1\Billing\Application\UseCases\GenerateMonthlyInvoiceUseCase;
use App\Modules\V1\Platforms\Domain\Enums\PLatformStatus;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DailyBillingCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
    ) {
    }

    public function handle(
        GenerateFirstInvoiceUseCase $generateFirstInvoice,
        GenerateMonthlyInvoiceUseCase $generateMonthlyInvoice,
    ): void {
        $now = now();

        Platform::where('status', PLatformStatus::FREE_TRIAL)
            ->whereDate('created_at', '<=', $now->copy()->subDays(3)->toDateString())
            ->get()
            ->each(fn (Platform $platform) => $generateFirstInvoice->execute($platform, $now));

        Platform::where('status', PLatformStatus::ACTIVE)
            ->whereDate('renew_at', '<=', $now->toDateString())
            ->get()
            ->each(fn (Platform $platform) => $generateMonthlyInvoice->execute($platform, $now));
    }
}

