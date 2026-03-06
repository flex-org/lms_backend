<?php

namespace App\Modules\V1\Platforms\Application\CreatePlatform;

use App\Modules\V1\Platforms\Domain\ValueObjects\Capacity;
use App\Modules\V1\Platforms\Domain\ValueObjects\DomainName;
use App\Modules\V1\Platforms\Domain\ValueObjects\StorageQuota;

final readonly class CreatePlatformData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $phone,
        public DomainName $domain,
        public StorageQuota $storage,
        public Capacity $capacity,
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
            domain: new DomainName($payload['domain']),
            storage: new StorageQuota($payload['storage']),
            capacity: new Capacity($payload['capacity']),
            mobileApp: $payload['mobile_app'] ?? false,
            sellingSystems: $payload['selling_systems'],
            features: $payload['features'],
        );
    }

    public function toPricePayload(): array
    {
        return [
            'storage' => $this->storage->megabytes,
            'capacity' => $this->capacity->students,
            'mobile_app' => $this->mobileApp,
        ];
    }
}
