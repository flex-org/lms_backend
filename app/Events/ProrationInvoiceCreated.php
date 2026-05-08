<?php

namespace App\Events;

use App\Modules\V1\Billing\Domain\Enums\PendingChangeType;
use App\Modules\V1\Billing\Domain\Models\Invoice;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProrationInvoiceCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $platform;
    public $invoice;
    public $type;
    public $payload;
    /**
     * Create a new event instance.
     */
    public function __construct(
        Platform $platform,
        Invoice $invoice,
        PendingChangeType $type,
        array $payload
    )
    {
        $this->platform = $platform;
        $this->invoice = $invoice;
        $this->type = $type;
        $this->payload = $payload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
