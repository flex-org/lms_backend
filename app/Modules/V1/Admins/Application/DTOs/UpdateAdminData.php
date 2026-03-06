<?php

namespace App\Modules\V1\Admins\Application\DTOs;

final readonly class UpdateAdminData
{
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $phone,
        public ?string $password,
        public ?string $role,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            name: $payload['name'] ?? null,
            email: $payload['email'] ?? null,
            phone: $payload['phone'] ?? null,
            password: $payload['password'] ?? null,
            role: $payload['role'] ?? null,
        );
    }

    public function toAttributes(): array
    {
        return array_filter(
            [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => $this->password,
            ],
            static fn ($value) => $value !== null,
        );
    }
}

