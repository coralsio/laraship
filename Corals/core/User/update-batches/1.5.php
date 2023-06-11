<?php

\Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->dateTime('confirmed_at')->nullable()->after('properties');
    $table->string('confirmation_code')->nullable()->after('properties');
});

\DB::table('settings')->insert([
    [
        'code' => 'confirm_user_registration_email',
        'type' => 'BOOLEAN',
        'category' => 'User',
        'label' => 'Confirm email on registration?',
        'value' => 'false',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]
]);


\Corals\User\Communication\Models\NotificationTemplate::updateOrCreate(['name' => 'notifications.user.confirmation'], [
    'friendly_name' => 'New user email confirmation',
    'title' => 'Email confirmation',
    'body' => [
        'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%"> <tbody> <tr> <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;"> <p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Hello {name},</p><p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;"> Please confirm your email address in order to access corals website. Click on the button below to confirm your email. </p></td></tr><tr> <td align="center" style="padding: 10px 0 25px 0;"> <table border="0" cellpadding="0" cellspacing="0"> <tbody> <tr> <td align="center" bgcolor="#ed8e20" style="border-radius: 5px;"> <a href="{confirmation_link}" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #ed8e20; padding: 15px 30px; border: 1px solid #ed8e20; display: block;" target="_blank"> Confirm now </a> </td></tr></tbody> </table> </td></tr></tbody></table>',
    ],
    'via' => ["mail"]
]);