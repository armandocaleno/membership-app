<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Establishment;
use Illuminate\Auth\Access\HandlesAuthorization;

class EstablishmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Establishment');
    }

    public function view(AuthUser $authUser, Establishment $establishment): bool
    {
        return $authUser->can('View:Establishment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Establishment');
    }

    public function update(AuthUser $authUser, Establishment $establishment): bool
    {
        return $authUser->can('Update:Establishment');
    }

    public function delete(AuthUser $authUser, Establishment $establishment): bool
    {
        return $authUser->can('Delete:Establishment');
    }

    public function restore(AuthUser $authUser, Establishment $establishment): bool
    {
        return $authUser->can('Restore:Establishment');
    }

    public function forceDelete(AuthUser $authUser, Establishment $establishment): bool
    {
        return $authUser->can('ForceDelete:Establishment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Establishment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Establishment');
    }

    public function replicate(AuthUser $authUser, Establishment $establishment): bool
    {
        return $authUser->can('Replicate:Establishment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Establishment');
    }

}