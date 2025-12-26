<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["ViewAny:Role","View:Role","Create:Role","Update:Role","Delete:Role","Restore:Role","ForceDelete:Role","ForceDeleteAny:Role","RestoreAny:Role","Replicate:Role","Reorder:Role","ViewAny:Customer","View:Customer","Create:Customer","Update:Customer","Delete:Customer","Restore:Customer","ForceDelete:Customer","ForceDeleteAny:Customer","RestoreAny:Customer","Replicate:Customer","Reorder:Customer","ViewAny:Device","View:Device","Create:Device","Update:Device","Delete:Device","Restore:Device","ForceDelete:Device","ForceDeleteAny:Device","RestoreAny:Device","Replicate:Device","Reorder:Device","ViewAny:Establishment","View:Establishment","Create:Establishment","Update:Establishment","Delete:Establishment","Restore:Establishment","ForceDelete:Establishment","ForceDeleteAny:Establishment","RestoreAny:Establishment","Replicate:Establishment","Reorder:Establishment","ViewAny:Income","View:Income","Create:Income","Update:Income","Delete:Income","Restore:Income","ForceDelete:Income","ForceDeleteAny:Income","RestoreAny:Income","Replicate:Income","Reorder:Income","ViewAny:PaymentMethod","View:PaymentMethod","Create:PaymentMethod","Update:PaymentMethod","Delete:PaymentMethod","Restore:PaymentMethod","ForceDelete:PaymentMethod","ForceDeleteAny:PaymentMethod","RestoreAny:PaymentMethod","Replicate:PaymentMethod","Reorder:PaymentMethod","ViewAny:Plan","View:Plan","Create:Plan","Update:Plan","Delete:Plan","Restore:Plan","ForceDelete:Plan","ForceDeleteAny:Plan","RestoreAny:Plan","Replicate:Plan","Reorder:Plan","ViewAny:Product","View:Product","Create:Product","Update:Product","Delete:Product","Restore:Product","ForceDelete:Product","ForceDeleteAny:Product","RestoreAny:Product","Replicate:Product","Reorder:Product","ViewAny:Regime","View:Regime","Create:Regime","Update:Regime","Delete:Regime","Restore:Regime","ForceDelete:Regime","ForceDeleteAny:Regime","RestoreAny:Regime","Replicate:Regime","Reorder:Regime","ViewAny:Support","View:Support","Create:Support","Update:Support","Delete:Support","Restore:Support","ForceDelete:Support","ForceDeleteAny:Support","RestoreAny:Support","Replicate:Support","Reorder:Support","ViewAny:Suscription","View:Suscription","Create:Suscription","Update:Suscription","Delete:Suscription","Restore:Suscription","ForceDelete:Suscription","ForceDeleteAny:Suscription","RestoreAny:Suscription","Replicate:Suscription","Reorder:Suscription","ViewAny:User","View:User","Create:User","Update:User","Delete:User","Restore:User","ForceDelete:User","ForceDeleteAny:User","RestoreAny:User","Replicate:User","Reorder:User","View:EditProfilePage","View:StatsOverview","View:StatsOverviewWidget","View:IncomePerMonthChart","View:SuscriptionPerPlanChart","View:IncomePerPlanChart","View:CustomerPerProvinceChart","View:CustomerPerRegimenChart","View:ExpiringSuscriptions","View:PendingSuscriptions"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
