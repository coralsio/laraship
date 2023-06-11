<?php

namespace Corals\Menu\database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MenuPermissionsTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('permissions')->insert([
            [
                'name' => 'Administrations::admin.menu',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Menu::menu.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Menu::menu.create',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Menu::menu.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Menu::menu.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
