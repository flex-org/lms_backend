<?php

namespace App\Modules\V1\Catalog\Application\DTOs;

use Illuminate\Http\UploadedFile;

final readonly class CreateCourseData
{
    public function __construct(
        public array $translations,
        public ?int $categoryId,
        public ?float $price,
        public bool $active,
        public ?UploadedFile $image,
    ) {}

    public static function fromArray(array $payload): self
    {
        return new self(
            translations: $payload['translations'],
            categoryId: $payload['category_id'] ?? null,
            price: $payload['price'] ?? null,
            active: $payload['active'] ?? true,
            image: $payload['image'] ?? null,
        );
    }

    public function toAttributes(): array
    {
        return [
            'category_id' => $this->categoryId,
            'price' => $this->price,
            'active' => $this->active,
        ];
    }
}
