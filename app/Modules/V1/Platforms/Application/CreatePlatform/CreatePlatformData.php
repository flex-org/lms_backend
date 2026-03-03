<?php

namespace App\Modules\V1\Platforms\Application\CreatePlatform;

final readonly class CreatePlatformData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $phone,
        public string $domain,
        public int $storage,
        public int $capacity,
        public bool $mobileApp,
        public array $sellingSystems,
        public array $features,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            name: $payload['name'],
            email: $payload['email'],
            password: $payload['password'],
            phone: $payload['phone'],
            domain: $payload['domain'],
            storage: $payload['storage'],
            capacity: $payload['capacity'],
            mobileApp: $payload['mobile_app'] ?? false,
            sellingSystems: $payload['selling_systems'],
            features: $payload['features'],
        );
    }

    public function toPricePayload(): array
    {
        return [
            'storage' => $this->storage,
            'capacity' => $this->capacity,
            'mobile_app' => $this->mobileApp,
        ];
    }
}
