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
 * Class OrderItem
 *
 * @property int $id
 * @property int $order_id
 * @property int $store_id
 * @property int $product_id
 * @property int $cart_item_id
 * @property float $price
 * @property int $qty
 * @property float|null $total
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @property CartItem $cart_item
 * @property Product $product
 * @property User $user
 * @property Order $order
 * @property Collection|OrderItemStatusLog[] $order_item_status_logs
 * @property Collection|Service[] $services
 *
 * @package App\Models
 */
class OrderItem extends Model
{
    use SoftDeletes;

    protected $table = 'order_items';

    protected $casts = [
        'order_id' => 'int',
        'store_id' => 'int',
        'product_id' => 'int',
        'cart_item_id' => 'int',
        'price' => 'float',
        'qty' => 'int',
        'total' => 'float'
    ];

    protected $fillable = [
        'order_id',
        'store_id',
        'product_id',
        'cart_item_id',
        'price',
        'qty',
        'total',
        'notes'
    ];

    public function cart_item()
    {
        return $this->belongsTo(CartItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'store_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function order_item_status_logs()
    {
        return $this->hasMany(OrderItemStatusLog::class);
    }

    // public function services()
    // {
    //     return $this->belongsToMany(Service::class, 'order_items_services')
    //         ->withPivot('id', 'price')
    //         ->withTimestamps();
    // }
    public function service()
    {
        return $this->belongsTo(StoreService::class, 'service_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function store()
    {
        return $this->belongsTo(User::class, 'store_id');
    }

    public function orderItemStatusLog()
    {
        return $this->hasOne(OrderItemStatusLog::class, 'order_item_id');
    }

    public function orderItemServices()
    {
        return $this->hasMany(OrderItemsService::class, 'order_item_id');
    }
}
