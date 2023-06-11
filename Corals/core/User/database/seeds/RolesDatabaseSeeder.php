<?php

namespace Corals\User\database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->delete();

        \DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'superuser',
                'label' => 'Super User',
                'guard_name' => config('auth.defaults.guard'),
                'subscription_required' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'member',
                'label' => 'Member',
                'guard_name' => config('auth.defaults.guard'),
                'subscription_required' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
