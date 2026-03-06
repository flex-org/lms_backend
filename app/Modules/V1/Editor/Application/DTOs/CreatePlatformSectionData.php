<?php

namespace App\Modules\V1\Editor\Application\DTOs;

final readonly class CreatePlatformSectionData
{
    public function __construct(
        public int $platformPageId,
        public int $sectionId,
        public bool $active = true,
        public ?int $position = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            platformPageId: $payload['platform_page_id'],
            sectionId: $payload['section_id'],
            active: $payload['active'] ?? true,
            position: $payload['position'] ?? null,
        );
    }

    public function toAttributes(): array
    {
        return array_filter([
            'platform_page_id' => $this->platformPageId,
            'section_id' => $this->sectionId,
            'active' => $this->active,
            'position' => $this->position,
        ], static fn ($v) => $v !== null);
    }
}
