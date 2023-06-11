<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 12/4/2017
 * Time: 10:49 AM
 */

namespace Corals\Foundation\Traits;


use Illuminate\Database\Eloquent\Model;

trait HashTrait
{
    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param mixed $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $decoded_value = hashids_decode($value);

        return $this->where($this->getRouteKeyName(), $decoded_value)->first();
    }

    public function getHashedIdAttribute()
    {
        return hashids_encode($this->{$this->getRouteKeyName()});
    }

    public static function findByHash($value)
    {
        $decoded_value = hashids_decode($value);

        return self::find($decoded_value);
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param string|null $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveSoftDeletableRouteBinding($value, $field = null)
    {
        $decoded_value = hashids_decode($value);

        return $this->where($this->getRouteKeyName(), $decoded_value)->withTrashed()->first();
    }
}