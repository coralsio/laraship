<?php

use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;

if (!\Schema::hasTable('utility_comments')) {

    \Schema::create('utility_comments', function (Blueprint $table) {
        $table->increments('id');
        $table->string('body');
        $table->morphs('commentable');
        $table->morphs('author');

        $table->unsignedInteger('created_by')->nullable()->index();
        $table->unsignedInteger('updated_by')->nullable()->index();

        $table->softDeletes();
        $table->timestamps();
    });
}
if (!\Schema::hasColumn('utility_ratings', 'status')) {

    \Schema::table('utility_ratings', function (Blueprint $table) {
        $table->enum('status', ['approved', 'disapproved', 'spam', 'pending'])
            ->default('approved')->after('criteria');
    });
}

\DB::table('permissions')->insert([

    //rating
    [
        'name' => 'Utility::rating.view',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],

    [
        'name' => 'Utility::rating.update',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],

    [
        'name' => 'Utility::rating.delete',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],

    //comment
    [
        'name' => 'Utility::comment.create',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],


]);

$member_role = \Corals\User\Models\Role::where('name', 'member')->first();
if ($member_role) {
    $member_role->forgetCachedPermissions();
    $member_role->givePermissionTo('Utility::comment.create');
}


$utilities_menu = \DB::table('menus')->where([
    'parent_id' => 1,// admin
    'key' => 'utility',

])->first();

$utilities_menu_id = $utilities_menu->id;

\DB::table('menus')->insert([
        [
            'parent_id' => $utilities_menu_id,
            'key' => null,
            'url' => 'utilities/ratings',
            'active_menu_url' => 'utilities/ratings*',
            'name' => 'Ratings',
            'description' => 'Ratings List Menu Item',
            'icon' => 'fa fa-star',
            'target' => null,
            'roles' => '["1"]',
            'order' => 0
        ],
        [
            'parent_id' => $utilities_menu_id,
            'key' => null,
            'url' => 'utilities/comments',
            'active_menu_url' => 'utilities/comments*',
            'name' => 'Comments',
            'description' => 'Comments List Menu Item',
            'icon' => 'fa fa-comment',
            'target' => null,
            'roles' => '["1"]',
            'order' => 0
        ],
    ]
);
\Corals\User\Communication\Models\NotificationTemplate::updateOrCreate(['name' => 'notifications.rate.rate_toggle_status'], [
    'friendly_name' => 'Rating Status',
    'title' => 'Rating status has beed changed',
    'extras' => [
        "bcc_users" => ["1"]
    ],
    'body' => [
        'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%"><tbody><tr><td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;">
<p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Hello there,</p>
<p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
<br/>
Rating status for "<b>{reviewrateable_identifier}</b>" ({reviewrateable_class}) has beed changed for {author_name} ({author_email}) To {rating_status}.
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
        'database' => 'Rating status 
 for "<b>{reviewrateable_identifier}</b>" ({reviewrateable_class}) has beed changed for {author_name} ({author_email}) To {rating_status}.<br/>
Check the following details:
<br/>
Rating value: {rating},<br/>
Rating title: {rating_title},<br/>
Rating Body: {rating_body},<br/>
Rating Criteria: {rating_criteria},<br/>
'], 'via' => ["mail", "database"]
]);

