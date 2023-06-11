<?php

namespace Corals\Menu\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Menu\Models\Menu;

class MenuTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('menu.models.menu.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Menu $menu
     * @return array
     * @throws \Throwable
     */
    public function transform(Menu $menu)
    {

        $transformedArray = [
            'id' => $menu->id,
            'created_at' => format_date($menu->created_at),
            'updated_at' => format_date($menu->updated_at),
            'action' => $this->actions($menu)
        ];

        return parent::transformResponse($transformedArray);
    }
}