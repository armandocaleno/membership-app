<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Suscription;
use Illuminate\Auth\Access\HandlesAuthorization;

class SuscriptionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Suscription');
    }

    public function view(AuthUser $authUser, Suscription $suscription): bool
    {
        return $authUser->can('View:Suscription');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Suscription');
    }

    public function update(AuthUser $authUser, Suscription $suscription): bool
    {
        return $authUser->can('Update:Suscription');
    }

    public function delete(AuthUser $authUser, Suscription $suscription): bool
    {
        return $authUser->can('Delete:Suscription');
    }

    public function restore(AuthUser $authUser, Suscription $suscription): bool
    {
        return $authUser->can('Restore:Suscription');
    }

    public function forceDelete(AuthUser $authUser, Suscription $suscription): bool
    {
        return $authUser->can('ForceDelete:Suscription');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Suscription');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Suscription');
    }

    public function replicate(AuthUser $authUser, Suscription $suscription): bool
    {
        return $authUser->can('Replicate:Suscription');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Suscription');
    }

}