<?php

namespace App\Modules\V1\Catalog\Application\DTOs;

use Illuminate\Http\UploadedFile;

final readonly class UpdateCategoryData
{
    public function __construct(
        public ?array $translations,
        public ?float $price,
        public ?bool $active,
        public ?UploadedFile $image,
    ) {}

    public static function fromArray(array $payload): self
    {
        return new self(
            translations: $payload['translations'] ?? null,
            price: array_key_exists('price', $payload) ? $payload['price'] : null,
            active: $payload['active'] ?? null,
            image: $payload['image'] ?? null,
        );
    }

    public function toAttributes(): array
    {
        return collect([
            'price' => $this->price,
            'active' => $this->active,
        ])->whereNotNull()->all();
    }
}
