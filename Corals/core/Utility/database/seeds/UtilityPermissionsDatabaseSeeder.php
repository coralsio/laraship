<?php

namespace Corals\Utility\database\seeds;

use Carbon\Carbon;
use Corals\User\Models\Role;
use Illuminate\Database\Seeder;

class UtilityPermissionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permissions')->insert([
            [
                'name' => 'Administrations::admin.utility',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Utility::invite_friends.can_send_invitation',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

        ]);

        $member_role = Role::where('name', 'member')->first();

        if ($member_role) {
            $member_role->forgetCachedPermissions();
            $member_role->givePermissionTo('Utility::invite_friends.can_send_invitation');
        }

    }
}
