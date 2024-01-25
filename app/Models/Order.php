<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Order
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $user_ip
 * @property int|null $store_id
 * @property int $cart_id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $country
 * @property string|null $city
 * @property string|null $street
 * @property string|null $building
 * @property string|null $floor
 * @property string|null $flat
 * @property int|null $address_id
 * @property float|null $vat
 * @property int|null $coupon_id
 * @property float|null $coupon_discount
 * @property float|null $discount
 * @property float|null $grand_total
 * @property float|null $total
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|OrderItem[] $order_items
 *
 * @package App\Models
 */
class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    protected $casts = [
        'user_id' => 'int',
        'store_id' => 'int',
        'cart_id' => 'int',
        'address_id' => 'int',
        'vat' => 'float',
        'coupon_id' => 'int',
        'coupon_discount' => 'float',
        'discount' => 'float',
        'grand_total' => 'float',
        'total' => 'float'
    ];

    protected $fillable = [
        'user_id',
        'user_ip',
        'device_id',
        'store_id',
        'cart_id',
        'status',
        'name',
        'email',
        'phone',
        'country',
        'city',
        'street',
        'building',
        'floor',
        'flat',
        'address_id',
        'vat',
        'coupon_id',
        'coupon_discount',
        'discount',
        'grand_total',
        'total',
        'is_paid'
    ];

    protected $dates = ['deleted_at'];

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
