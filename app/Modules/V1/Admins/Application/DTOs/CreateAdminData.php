<?php

namespace App\Modules\V1\Admins\Application\DTOs;

final readonly class CreateAdminData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $password,
        public string $role,
        public int $platformId,
    ) {
    }

    public static function fromArray(array $payload, int $platformId): self
    {
        return new self(
            name: $payload['name'],
            email: $payload['email'],
            phone: $payload['phone'],
            password: $payload['password'],
            role: $payload['role'],
            platformId: $platformId,
        );
    }

    public function toAttributes(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => $this->password,
            'platform_id' => $this->platformId,
        ];
    }
}
