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
     * @var array|string[]
     */
    protected array $allowedFields = ['slug', 'username'];

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param mixed $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if (in_array($field, $this->allowedFields)) {
            return $this->where($field, $value)->first();
        }

        return $this->where($this->getRouteKeyName(), hashids_decode($value))->first();
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
        if (in_array($field, $this->allowedFields)) {
            return $this->where($field, $value)->withTrashed()->first();
        }

        return $this->where($this->getRouteKeyName(), hashids_decode($value))->withTrashed()->first();
    }
}