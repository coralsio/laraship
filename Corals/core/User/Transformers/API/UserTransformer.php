<?php

namespace Corals\User\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\User\Models\User;

class UserTransformer extends APIBaseTransformer
{
    /**
     * @param User $user
     * @return array
     * @throws \Throwable
     */
    public function transform(User $user)
    {
        $transformedArray = [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'confirmed' => $user->confirmed,
            'roles' => apiPluck($user->roles->pluck('label', 'name'), 'name', 'label'),
            'job_title' => $user->job_title,
            'picture' => $user->picture,
            'picture_thumb' => $user->picture_thumb,
            'about' => $user->getProperty('about'),
            'phone_country_code' => $user->phone_country_code,
            'phone_number' => $user->phone_number,
            'address' => $user->getRawOriginal('address') ? $user->address : null,
            'integration_id' => $user->integration_id,
            'gateway' => $user->gateway,
            'card_brand' => $user->card_brand,
            'card_last_four' => $user->card_last_four,
            'payment_method_token' => $user->payment_method_token,
            'created_at' => format_date($user->created_at),
            'updated_at' => format_date($user->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}