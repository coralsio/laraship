<?php

namespace Corals\User\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\User\Models\User;

class UserTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('user.models.user.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param User $user
     * @return array
     * @throws \Throwable
     */
    public function transform(User $user)
    {
        $show_url = $user->getShowURL();
        $canViewUser = user() && user()->can('view', $user);
        $img = '<img src="' . $user->picture_thumb . '" class="img-circle img-responsive" alt="User Picture" width="35"/>';
        $transformedArray = [
            'id' => $user->id,
            'full_name' => $canViewUser ? ('<a href="' . $show_url . '">' . $user->full_name . '</a>') : $user->full_name,
            'checkbox' => $this->generateCheckboxElement($user),
            'name' => $canViewUser ? ('<a href="' . $show_url . '">' . $user->name . '</a>') : $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'status' => formatStatusAsLabels($user->status),
            'confirmed' => $user->confirmed ? '&#10004;' : '-',
            'groups' => formatArrayAsLabels($user->groups->pluck('name'), 'warning'),
            'roles' => formatArrayAsLabels($user->roles->pluck('label'), 'success'),
            'picture' => $user->picture,
            'picture_thumb' => $canViewUser ? ('<a href="' . $show_url . '">' . $img . '</a>') : $img,
            'created_at' => format_date($user->created_at),
            'updated_at' => format_date($user->updated_at),
            'action' => $this->actions($user),
        ];

        return parent::transformResponse($transformedArray, $user);
    }
}
