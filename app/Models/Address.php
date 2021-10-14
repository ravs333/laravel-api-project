<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $line_1
 * @property string $line_2
 * @property string $city
 * @property string $country
 * @property string $state
 * @property string $postcode
 * @property string $created_at
 * @property string $updated_at
 * @property Order[] $orders
 * @property Order[] $orders
 */
class Address extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'address';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'phone', 'line_1', 'line_2', 'city', 'country', 'state', 'postcode', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function billingOrders()
    {
        return $this->hasMany('App\Models\Order', 'billing_address_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shippingOrders()
    {
        return $this->hasMany('App\Models\Order', 'shipping_address_id');
    }
}
