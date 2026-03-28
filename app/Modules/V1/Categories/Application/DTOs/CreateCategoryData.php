<?php

namespace App\Modules\V1\Catalog\Application\DTOs;

use Illuminate\Http\UploadedFile;

final readonly class CreateCategoryData
{
    public function __construct(
        public array $translations,
        public ?float $price,
        public bool $active,
        public ?UploadedFile $image,
    ) {}

    public static function fromArray(array $payload): self
    {
        return new self(
            translations: $payload['translations'],
            price: $payload['price'] ?? null,
            active: $payload['active'] ?? true,
            image: $payload['image'] ?? null,
        );
    }

    public function toAttributes(): array
    {
        return [
            'price' => $this->price,
            'active' => $this->active,
        ];
    }
}
