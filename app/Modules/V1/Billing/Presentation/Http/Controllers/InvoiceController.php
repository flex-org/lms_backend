<?php

namespace App\Modules\V1\Billing\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Billing\Application\UseCases\ApplyPendingChangeUseCase;
use App\Modules\V1\Billing\Domain\Enums\InvoiceStatus;
use App\Modules\V1\Billing\Domain\Models\Invoice;
use App\Modules\V1\Billing\Domain\Models\PlatformPendingChange;
use App\Modules\V1\Platforms\Domain\Enums\PLatformStatus;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly TenantContextInterface $tenantContext,
        private readonly ApplyPendingChangeUseCase $applyPendingChange,
    ) {
    }

    public function index()
    {
        $platform = $this->tenantContext->getPlatform();

        $invoices = $platform->invoices()->with('items')->latest()->get();

        return ApiResponse::success(['invoices' => $invoices]);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items');

        return ApiResponse::success(['invoice' => $invoice]);
    }

    public function pay(Request $request, Invoice $invoice)
    {
        if ($invoice->status === InvoiceStatus::PAID) {
            return ApiResponse::success(['invoice' => $invoice]);
        }

        $invoice->status = InvoiceStatus::PAID;
        $invoice->paid_at = now();
        $invoice->save();

        $platform = $invoice->platform;

        if ($invoice->type->value === 'monthly') {
            $platform->status = PLatformStatus::ACTIVE;
            $platform->save();
        }

        /** @var PlatformPendingChange|null $pendingChange */
        $pendingChange = $invoice->pendingChange;

        if ($pendingChange) {
            $this->applyPendingChange->execute($pendingChange);
        }

        return ApiResponse::updated(['invoice' => $invoice->fresh('items')]);
    }
}

