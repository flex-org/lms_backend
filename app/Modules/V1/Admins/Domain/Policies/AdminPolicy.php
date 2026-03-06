<?php

namespace App\Modules\V1\Admins\Domain\Policies;

use App\Modules\V1\Admins\Domain\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $authAdmin): bool
    {
        return $authAdmin->hasRole('owner');
    }

    public function create(Admin $authAdmin): bool
    {
        return $authAdmin->hasRole('owner');
    }

    public function update(Admin $authAdmin, Admin $targetAdmin): bool
    {
        return $authAdmin->hasRole('owner')
            && $authAdmin->platform_id === $targetAdmin->platform_id;
    }

    public function delete(Admin $authAdmin, Admin $targetAdmin): bool
    {
        return $authAdmin->hasRole('owner')
            && $authAdmin->platform_id === $targetAdmin->platform_id
            && $authAdmin->id !== $targetAdmin->id;
    }
}
