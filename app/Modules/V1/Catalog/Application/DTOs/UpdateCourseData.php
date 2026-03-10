<?php

namespace App\Modules\V1\Catalog\Application\DTOs;

use Illuminate\Http\UploadedFile;

final readonly class UpdateCourseData
{
    public function __construct(
        public ?array $translations,
        public ?int $categoryId,
        public ?float $price,
        public ?bool $active,
        public ?UploadedFile $image,
    ) {}

    public static function fromArray(array $payload): self
    {
        return new self(
            translations: $payload['translations'] ?? null,
            categoryId: array_key_exists('category_id', $payload) ? $payload['category_id'] : null,
            price: array_key_exists('price', $payload) ? $payload['price'] : null,
            active: $payload['active'] ?? null,
            image: $payload['image'] ?? null,
        );
    }

    public function toAttributes(): array
    {
        return collect([
            'category_id' => $this->categoryId,
            'price' => $this->price,
            'active' => $this->active,
        ])->filter(fn ($v, $k) => $v !== null || $k === 'category_id')->all();
    }
}
