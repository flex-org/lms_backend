<?php

namespace App\Listeners;

use App\Events\ProrationInvoiceCreated;
use App\Modules\V1\Billing\Application\UseCases\ApplyPendingChangeUseCase;
use App\Modules\V1\Billing\Domain\Enums\InvoiceStatus;
use App\Modules\V1\Billing\Domain\Enums\PendingChangeStatus;
use App\Modules\V1\Billing\Domain\Enums\PendingChangeType;
use App\Modules\V1\Billing\Domain\Models\PlatformPendingChange;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetPendingChange
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProrationInvoiceCreated $event): void
    {
        $pendingChange = PlatformPendingChange::create([
            'platform_id' => $event->platform,
            'invoice_id' => $event->invoice,
            'change_type' => $event->type,
            'payload' => $event->payload,
            'status' => PendingChangeStatus::PENDING,
        ]);

        $this->temporaryActivation($event->invoice, $pendingChange);
    }

    private function temporaryActivation($invoice, $pendingChange)
    {
        $invoice->status = InvoiceStatus::PAID;
        $invoice->paid_at = now();
        $invoice->save();

        (new ApplyPendingChangeUseCase)->execute($pendingChange);
    }
}
