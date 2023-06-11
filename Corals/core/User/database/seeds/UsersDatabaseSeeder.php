<?php

namespace Corals\User\database\seeds;

use Carbon\Carbon;
use Corals\User\Communication\database\seeds\NotificationDatabaseSeeder;
use Corals\User\Models\Role;
use Corals\User\Models\User;
use Illuminate\Database\Seeder;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsDatabaseSeeder::class);
        $this->call(RolesDatabaseSeeder::class);
        $this->call(NotificationDatabaseSeeder::class);
        $this->call(UserNotificationTemplatesSeeder::class);
        $this->call(UsersSettingsDatabaseSeeder::class);


        \DB::table('users')->delete();

        $superuser_id = \DB::table('users')->insertGetId([
            'name' => 'Super',
            'last_name' => 'User',
            'email' => 'superuser@corals.io',
            'password' => bcrypt('123456'),
            'job_title' => 'Administrator',
            'address' => null,
            'confirmed_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $superuser_role = Role::whereName('superuser')->first();

        if ($superuser_role) {
            $superuser_role->users()->attach($superuser_id);
        }

        $member = User::create([
            'name' => 'Corals',
            'last_name' => 'Member',
            'email' => 'member@corals.io',
            'password' => '123456',
            'job_title' => 'Ads Coordinator',
            'address' => null,
            'confirmed_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $member_role = Role::whereName('member')->first();

        if ($member_role) {
            $member_role->users()->attach($member->id);
        }
    }
}
