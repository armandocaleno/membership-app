<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Support;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupportPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Support');
    }

    public function view(AuthUser $authUser, Support $support): bool
    {
        return $authUser->can('View:Support');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Support');
    }

    public function update(AuthUser $authUser, Support $support): bool
    {
        return $authUser->can('Update:Support');
    }

    public function delete(AuthUser $authUser, Support $support): bool
    {
        return $authUser->can('Delete:Support');
    }

    public function restore(AuthUser $authUser, Support $support): bool
    {
        return $authUser->can('Restore:Support');
    }

    public function forceDelete(AuthUser $authUser, Support $support): bool
    {
        return $authUser->can('ForceDelete:Support');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Support');
    }

    public function restoreAny(AuthUser $authUser, Support $support): bool
    {
        return $authUser->can('RestoreAny:Support');
    }

    public function export(AuthUser $authUser, Suscription $suscription): bool
    {
        return $authUser->can('Export:Support');
    }
}