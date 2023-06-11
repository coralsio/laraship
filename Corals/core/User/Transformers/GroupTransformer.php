<?php

namespace Corals\User\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\User\Models\Group;


class GroupTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('user.models.group.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Group $group
     * @return array
     * @throws \Throwable
     */
    public function transform(Group $group)
    {
        $show_url = $group->getShowURL();

        $transformedArray = [
            'id' => $group->id,
            'name' => $this->getModelLink($group, $group->name, [], false, false),
            'created_at' => format_date($group->created_at),
            'updated_at' => format_date($group->updated_at),
            'action' => $this->actions($group)
        ];

        return parent::transformResponse($transformedArray);
    }
}
