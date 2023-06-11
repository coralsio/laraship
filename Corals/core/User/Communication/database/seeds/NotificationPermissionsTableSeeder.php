<?php

namespace Corals\User\Communication\database\seeds;

use Carbon\Carbon;
use Corals\User\Models\Role;
use Illuminate\Database\Seeder;

class NotificationPermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'name' => 'Notification::notification_template.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Notification::notification_template.create',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Notification::notification_template.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Notification::notification_template.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Notification::my_notification.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Notification::my_notification.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Notification::my_notification.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($permissions as $permission) {
            \DB::table('permissions')->updateOrInsert(['name' => $permission['name']], $permission);
        }
        $roles = Role::where('name', '<>', 'superuser')->get();

        if (count($roles) > 0) {
            $end_user_permissions = ['Notification::my_notification.view', 'Notification::my_notification.update', 'Notification::my_notification.delete'];
            foreach ($roles as $role) {
                $role->forgetCachedPermissions();

                foreach ($end_user_permissions as $end_user_permission) {
                    if (!$role->hasPermissionTo($end_user_permission)) {
                        $role->givePermissionTo($end_user_permission);
                    }
                }

            }
        }
    }
}
