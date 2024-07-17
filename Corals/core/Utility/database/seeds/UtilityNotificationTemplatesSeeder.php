<?php

namespace Corals\Utility\database\seeds;

use Corals\User\Communication\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class UtilityNotificationTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        NotificationTemplate::updateOrCreate([
            'name' => 'notifications.user.invitation',
        ], [
            'friendly_name' => 'Send User Invitation',
            'title' => '{subject}',
            'body' => [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%">
    <tbody>
        <tr>
            <td align="left"
                style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;">
                 <p style="font-size: 16px; font-weight: 650; line-height: 18px; color: #333333;">{body}</p>
            </td>
        </tr>
    </tbody>
</table>',
            ],
            'via' => ["mail"]
        ]);

        NotificationTemplate::updateOrCreate(['name' => 'notifications.invitation.accepted',
        ], [
            'friendly_name' => 'New Invitation Accepted',
            'title' => 'New Invitation Accepted',
            'body' => [
                'database' => '{invitee_name} accepted your invitation'
            ],
            'via' => ["database"]
        ]);
    }
}
