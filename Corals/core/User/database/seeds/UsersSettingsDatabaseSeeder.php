<?php

namespace Corals\User\database\seeds;


use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersSettingsDatabaseSeeder extends Seeder
{

    public function run()
    {
        \DB::table('settings')->insert([
            [
                'code' => 'confirm_user_registration_email',
                'type' => 'BOOLEAN',
                'category' => 'User',
                'label' => 'Confirm email on registration?',
                'value' => 'false',
                'editable' => 1,
                'hidden' => 0,
                'is_public' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'cookie_consent',
                'type' => 'BOOLEAN',
                'category' => 'User',
                'label' => 'Enable Cookie Consent',
                'value' => 'false',
                'editable' => 1,
                'hidden' => 0,
                'is_public' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'cookie_consent_config',
                'type' => 'TEXTAREA',
                'category' => 'User',
                'label' => 'Cookie Consent Configuration',
                'value' => '{
                        type: "opt-in",
                        position: "bottom",
                        palette: { "popup": { "background": "#252e39" }, "button": { "background": "#14a7d0", padding: "5px 50px" } }
            
                    }',
                'editable' => 1,
                'hidden' => 0,
                'is_public' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'available_registration_roles',
                'type' => 'SELECT',
                'category' => 'User',
                'label' => 'Available registration roles',
                'value' => json_encode(['member' => 'Member']),
                'editable' => 1,
                'hidden' => 0,
                'is_public' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [   'code' => 'customer_classifications',
                'type' => 'SELECT',
                'category' => 'User',
                'label' => 'Customer Classifications',
                'value' => json_encode(['standard' => 'Standard', 'silver' => 'Silver', 'gold' => 'Gold']),
                'editable' => 1,
                'hidden' => 0,
                'is_public' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'default_user_role',
                'type' => 'TEXT',
                'category' => 'User',
                'label' => 'Default User Role',
                'value' => 'member',
                'editable' => 1,
                'hidden' => 0,
                'is_public' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'two_factor_auth_enabled',
                'type' => 'BOOLEAN',
                'category' => 'User',
                'label' => 'Enable 2FA Authentication',
                'value' => 'false',
                'editable' => 1,
                'hidden' => 0,
                'is_public' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'two_factor_auth_provider',
                'type' => 'TEXT',
                'category' => 'User',
                'label' => '2FA provider',
                'value' => '',
                'editable' => 1,
                'hidden' => 0,
                'is_public' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'address_types',
                'type' => 'SELECT',
                'category' => 'User',
                'label' => 'Address Types',
                'value' => '{"home":"Home","office":"Office","shipping":"Shipping","billing":"Billing"}',
                'editable' => 1,
                'hidden' => 0,
                'is_public' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'registration_enabled',
                'type' => 'BOOLEAN',
                'category' => 'User',
                'label' => 'Enable Registration',
                'value' => 'true',
                'editable' => 1,
                'hidden' => 0,
                'is_public' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }

}
