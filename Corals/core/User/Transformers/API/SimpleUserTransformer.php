<?php

namespace Corals\User\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\HireSkills\Facades\HireSkills;
use Corals\Modules\HireSkills\Transformers\API\CompanySimplePresenter;
use Corals\Modules\HireSkills\Transformers\API\EmployeeSimplePresenter;
use Corals\User\Models\User;

class SimpleUserTransformer extends APIBaseTransformer
{
    /**
     * @param User $user
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function transform(User $user)
    {
        $transformedArray = [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'picture_thumb' => $user->picture_thumb,
            'phone_number' => $user->phone_number,
            'created_at' => format_date($user->created_at),
            'updated_at' => format_date($user->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
