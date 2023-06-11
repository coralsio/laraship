<?php

use Corals\User\Communication\Models\NotificationTemplate;

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
