<?php

\Corals\User\Communication\Models\NotificationTemplate::updateOrCreate(['name' => 'notifications.rate.rate_created'], [
    'friendly_name' => 'New Rating Created',
    'title' => 'New Rating has been created',
    'extras' => [
        "bcc_users" => ["1"]
    ],
    'body' => [
        'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%"><tbody><tr><td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;">
<p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Hello there,</p>
<p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
<br/>
New Rating for "<b>{reviewrateable_identifier}</b>" ({reviewrateable_class}) has been created by {author_name} ({author_email}).
<br/>
Check the following details:
<br/>
Rating value: {rating},<br/>
Rating title: {rating_title},<br/>
Rating Body: {rating_body},<br/>
Rating Criteria: {rating_criteria},<br/>

<br/>
Thanks.
</p></td></tr></tbody></table>',
        'database' => 'New Rating for "<b>{reviewrateable_identifier}</b>" ({reviewrateable_class}) has been created by {author_name} ({author_email}).<br/>
Check the following details:
<br/>
Rating value: {rating},<br/>
Rating title: {rating_title},<br/>
Rating Body: {rating_body},<br/>
Rating Criteria: {rating_criteria},<br/>
'],
    'via' => ["mail", "database"]
]);
