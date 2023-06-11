<?php

namespace Corals\User\Services;

use Corals\Foundation\Services\BaseServiceClass;

class UserAddressService extends BaseServiceClass
{
    public function storeAddress($address)
    {
        $userAddress = $this->model->address;

        if (!is_array($userAddress)) {
            $userAddress = [];
        }

        $addressType = \Arr::pull($address, 'type');

        $userAddress[$addressType] = $address;

        $this->model->address = $userAddress;

        $this->model->save();
    }

    public function destroyAddress($type)
    {
        $userAddress = $this->model->address ?? [];

        unset($userAddress[$type]);

        $this->model->address = $userAddress;

        $this->model->save();
    }
}