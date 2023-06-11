<?php

namespace Corals\Foundation\Traits;


use Corals\Foundation\Models\GatewayStatus;
use Corals\Modules\Payment\Payment;
use Illuminate\Database\Eloquent\Collection;

/**
 * Trait GatewayStatusTrait
 * @package Corals\Foundation\Traits
 */
trait GatewayStatusTrait
{
    public function gatewayStatus()
    {
        return $this->morphMany(GatewayStatus::class, 'objectType', 'object_type', 'object_id');
    }

    /**
     * @param $gateway
     * @param $status
     * @param null $message
     * @param null $reference
     * @param null $status_type
     * @param array $properties
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function setGatewayStatus(
        $gateway,
        $status,
        $message = null,
        $reference = null,
        $status_type = null,
        $properties = []
    ) {
        $data = array_merge([
            'status' => $status,
            'message' => $message,
            'updated_at' => now(),
            'properties' => $properties,
        ], $reference ? ['object_reference' => $reference] : []);

        return $this->gatewayStatus()->updateOrCreate([
            'object_id' => $this->getKey(),
            'object_type' => getMorphAlias($this),
            'gateway' => $gateway,
            'status_type' => $status_type,
        ], $data);
    }

    /**
     * @param null $gateway
     * @param null $status_type
     * @param bool $objects
     * @return string | Collection
     */
    public function getGatewayStatus($gateway = null, $status_type = null, $objects = false)
    {
        $gateways = $this->gatewayStatus();

        $gateways->when($status_type, function ($statusTypeQy, $status_type) {
            $statusTypeQy->where('status_type', $status_type);
        });

        if ($gateway) {
            $gateways = $gateways->where('gateway', $gateway)->get();
        } else {
            $gateways = $gateways->get();
        }

        if ($objects) {
            return $gateways;
        }

        $status = '<ul>';

        if ($gateways->count()) {
            foreach ($gateways as $gateway) {
                $status .= "<li>{$gateway->gateway}: " . $this->formatGatewayStatus($gateway) . '</li>';
            }
        } else {
            $status .= "<li>NA</li>";
        }

        $status = $status . '</ul>';

        return $status;
    }

    /**
     * @param $object
     * @param null $gateway
     * @param null $status_type
     * @return array
     */
    public function getGatewayActions($object, $gateway = null, $status_type = null)
    {
        $gateways = $this->gatewayStatus();

        $gateways->when($status_type, function ($statusTypeQy, $status_type) {
            $statusTypeQy->where('status_type', $status_type);
        });

        $object_class = strtolower(class_basename(get_class($object)));

        if ($gateway) {
            $gateways = $gateways->where('gateway', $gateway)->get();
        } else {
            $gateways = $gateways->get();
        }

        $supported_gateways = \Payments::getAvailableGateways();

        $actions = [];

        if ($gateways->count()) {
            foreach ($gateways as $gateway) {
                if (isset($supported_gateways[$gateway->gateway])) {
                    unset($supported_gateways[$gateway->gateway]);
                }
                if (!in_array($gateway->status, ['NA', 'CREATE_FAILED'])) {
                    continue;
                }

                $href = sprintf("%s/create-gateway-%s?gateway=", $object->getShowUrl(), $object_class,
                    $gateway->gateway);

                $actions = array_merge([
                    'create_' . $gateway->gateway => [
                        'icon' => 'fa fa-fw fa-thumbs-o-up',
                        'href' => $href,
                        'label' => trans('Payment::labels.gateways.create',
                            ['gateway' => $gateway->gateway, 'class' => class_basename($this)]),
                        'data' => [
                            'action' => 'post',
                            'table' => '.dataTableBuilder'
                        ]
                    ]
                ], $actions);
            }
        }

        foreach ($supported_gateways as $gateway => $gateway_title) {
            $gatewayObj = Payment::create($gateway);
            if (!$gatewayObj->getConfig('manage_remote_' . $object_class)) {
                continue;
            }

            $href = sprintf("%s/create-gateway-%s?gateway=", $object->getShowUrl(), $object_class, $gateway);

            $actions = array_merge([
                'create_' . $gateway => [
                    'icon' => 'fa fa-fw fa-thumbs-o-up',
                    'href' => $href,
                    'label' => trans('Payment::labels.gateways.create_title',
                        ['gateway' => $gateway_title, 'class' => class_basename($this)]),
                    'data' => [
                        'action' => 'post',
                        'table' => '.dataTableBuilder'
                    ]
                ]
            ], $actions);
        }

        return $actions;
    }

    /**
     * @param $gatewayModel
     * @return string
     */
    private function formatGatewayStatus($gatewayModel)
    {
        switch ($gatewayModel->status) {
            case 'CREATED':
            case 'UPDATED':
            case 'DELETED':
                $formatted = '<i class="fa fa-check-circle-o text-success"></i> ' . ucfirst($gatewayModel->status);
                break;
            case 'CREATE_FAILED':
            case 'UPDATE_FAILED':
            case 'DELETE_FAILED':
                $formatted = generatePopover($gatewayModel->message, ucfirst($gatewayModel->status),
                    'fa fa-times-circle-o text-danger');
                break;
            default:
                $formatted = ucfirst($gatewayModel->status);
        }

        return $formatted;
    }

    /**
     * @param $gateway
     * @param null $status_type
     * @return mixed|null
     */
    public function getObjectReference($gateway, $status_type = null)
    {
        $gatewayStatus = $this->gatewayStatus()
            ->where('gateway', $gateway)
            ->when($status_type, function ($statusTypeQy, $status_type) {
                $statusTypeQy->where('status_type', $status_type);
            })
            ->first();

        return optional($gatewayStatus)->object_reference;
    }

    /**
     * @param $builder
     * @param $gateway
     * @param $objectReference
     */
    public function scopeByObjectReference($builder, $gateway, $objectReference)
    {
        $gatewayStatusTable = GatewayStatus::getTableName();

        $keyName = $this->qualifyColumn($this->getKeyName());

        $builder->join($gatewayStatusTable, function ($join) use ($gatewayStatusTable, $keyName) {
            $join->on($gatewayStatusTable . '.object_id', $keyName)
                ->where($gatewayStatusTable . '.object_type', getMorphAlias($this));
        })->where([
            $gatewayStatusTable . '.object_reference' => $objectReference,
            $gatewayStatusTable . '.gateway' => $gateway
        ])->select($this->getTable() . '.*');
    }

    /**
     * @param $gateway
     * @param $objectReference
     * @return mixed
     */
    public static function getByObjectReference($gateway, $objectReference)
    {
        return with(new self())->byObjectReference($gateway, $objectReference)->first();
    }
}
