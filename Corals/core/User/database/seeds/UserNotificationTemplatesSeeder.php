<?php

namespace Corals\User\database\seeds;

use Corals\User\Communication\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class UserNotificationTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NotificationTemplate::updateOrCreate(['name' => 'User::user_registered'], [
            'event_name' => 'notifications.user.registered',
            'friendly_name' => 'New user registration',
            'title' => 'Welcome to ' . config('app.name'),
            'body' => [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%"><tbody><tr><td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;"><p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Hello {name},</p><p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">Welcome to ' . config('app.name') . ' and thanks for registration! hope you find what you are looking for in our platform.</p></td></tr><tr><td align="center" style="padding: 10px 0 25px 0;"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center" bgcolor="#ed8e20" style="border-radius: 5px;"><a href="{dashboard_link}" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #ed8e20; padding: 15px 30px; border: 1px solid #ed8e20; display: block;" target="_blank">Visit your Dashboard</a></td></tr></tbody></table></td></tr></tbody></table>',
                'database' => '<p>Welcome to <strong>' . config('app.name') . '</strong> and thanks for registration! hope you find what you are looking for in <em>our platform</em>.</p>'
            ],
            'via' => ["mail", "database"]
        ]);

        NotificationTemplate::updateOrCreate(['name' => 'User::user_confirmation'], [
            'event_name' => 'notifications.user.confirmation',
            'friendly_name' => 'New user email confirmation',
            'title' => 'Email confirmation',
            'body' => [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%"> <tbody> <tr> <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;"> <p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Hello {name},</p><p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;"> Please confirm your email address in order to access' . config('app.name') . 'website. Click on the button below to confirm your email. </p></td></tr><tr> <td align="center" style="padding: 10px 0 25px 0;"> <table border="0" cellpadding="0" cellspacing="0"> <tbody> <tr> <td align="center" bgcolor="#ed8e20" style="border-radius: 5px;"> <a href="{confirmation_link}" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #ed8e20; padding: 15px 30px; border: 1px solid #ed8e20; display: block;" target="_blank"> Confirm now </a> </td></tr></tbody> </table> </td></tr></tbody></table>',
            ],
            'via' => ["mail"]
        ]);

        NotificationTemplate::updateOrCreate([
            'name' => 'User::send_login_details',
            'event_name' => 'notifications.user.send_login_details'
        ], [
            'friendly_name' => 'Send Login Details',
            'title' => 'Login Details',
            'body' => [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%"> <tbody> <tr> <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;"> <p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Hello {full_name},</p><p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;"> You can use the following details to login: <br/><br/> email: {email}  <br/> password: {password}<br/><br/>Click on the button below to login. </p></td></tr><tr> <td align="center" style="padding: 10px 0 25px 0;"> <table border="0" cellpadding="0" cellspacing="0"> <tbody> <tr> <td align="center" bgcolor="#ed8e20" style="border-radius: 5px;"> <a href="{login_url}" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #ed8e20; padding: 15px 30px; border: 1px solid #ed8e20; display: block;" target="_blank"> Login </a> </td></tr></tbody> </table> </td></tr></tbody></table>',
            ],
            'via' => ["mail"]
        ]);
        NotificationTemplate::updateOrCreate(['name' => 'notifications.import_status'], [
            'friendly_name' => 'Import Status',
            'title' => '{import_file_name} Import Status',
            'body' => [
                'mail' => '<p>Hi {user_name},</p><p>Here you are the {import_file_name} Import Status:</p><p>Success Imported Records: <b>{success_records_count}</b></p>
                           <p style="color:red;">Failed Imported Records: <b>{failed_records_count}</b><br/>{import_log_file}</p>',
                'database' => '<p>Here you are the {import_file_name} Import Status:</p><p>Success Imported Records: <b>{success_records_count}</b></p><p>Failed Imported Records: <b>{failed_records_count}</b><br/>{import_log_file}</p>',
            ],
            'via' => ["mail", "database"]
        ]);


    }
}
