<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Regime;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegimePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Regime');
    }

    public function view(AuthUser $authUser, Regime $regime): bool
    {
        return $authUser->can('View:Regime');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Regime');
    }

    public function update(AuthUser $authUser, Regime $regime): bool
    {
        return $authUser->can('Update:Regime');
    }

    public function delete(AuthUser $authUser, Regime $regime): bool
    {
        return $authUser->can('Delete:Regime');
    }

    public function restore(AuthUser $authUser, Regime $regime): bool
    {
        return $authUser->can('Restore:Regime');
    }

    public function forceDelete(AuthUser $authUser, Regime $regime): bool
    {
        return $authUser->can('ForceDelete:Regime');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Regime');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Regime');
    }

    public function replicate(AuthUser $authUser, Regime $regime): bool
    {
        return $authUser->can('Replicate:Regime');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Regime');
    }

}