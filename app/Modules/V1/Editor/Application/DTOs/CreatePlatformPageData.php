<?php

namespace App\Modules\V1\Editor\Application\DTOs;

final readonly class CreatePlatformPageData
{
    public function __construct(
        public int $platformId,
        public int $pageId,
        public bool $active = true,
    ) {
    }

    public static function fromArray(array $payload, int $platformId): self
    {
        return new self(
            platformId: $platformId,
            pageId: $payload['page_id'],
            active: $payload['active'] ?? true,
        );
    }

    public function toAttributes(): array
    {
        return [
            'platform_id' => $this->platformId,
            'page_id' => $this->pageId,
            'active' => $this->active,
        ];
    }
}
