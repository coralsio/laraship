<?php

namespace Corals\User\Widgets;


use Corals\User\Models\User;

class NewUsersWidget
{

    function __construct()
    {
    }

    function run($args)
    {
        $days = $args['days'] ?? 30;
        $date = \Carbon\Carbon::today()->subDays($days);
        $users = User::where('created_at', '>=', $date)->whereHas('roles', function ($query) {
            $query->whereName('member');
        })->get()->count();
        return '          <!-- small box -->
                            <div class="card">
                            <div class="small-box bg-yellow card-body">
                                <div class="inner">
                                    <h3>' . $users . '</h3>

                                    <p>'.trans('User::labels.new_registration').'</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <a href="' . url('users') . '" class="small-box-footer">
                                    '.trans('User::labels.more_info').'
                                </a>
                            </div>
                            </div>';

    }

}