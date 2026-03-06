<?php

namespace App\Modules\V1\Admins\Application\Services;

use App\Modules\V1\Admins\Domain\Models\Admin;

class AdminManagementService
{
    public function listByDomain(string $domain)
    {
        return Admin::where('domain', $domain)->get();
    }

    public function create(array $data, string $domain): Admin
    {
        $admin = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'domain' => $domain,
        ]);

        $admin->assignRole($data['role']);

        return $admin;
    }

    public function update(Admin $admin, array $data): Admin
    {
        if (array_key_exists('name', $data)) {
            $admin->name = $data['name'];
        }

        if (array_key_exists('email', $data)) {
            $admin->email = $data['email'];
        }

        if (array_key_exists('phone', $data)) {
            $admin->phone = $data['phone'];
        }

        if (array_key_exists('password', $data)) {
            $admin->password = $data['password'];
        }

        if (array_key_exists('role', $data)) {
            $admin->syncRoles([$data['role']]);
        }

        $admin->save();

        return $admin;
    }

    public function delete(Admin $admin): void
    {
        $admin->delete();
    }
}

