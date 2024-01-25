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
 * Class CartItem
 *
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $store_id
 * @property int $qty
 * @property float $price
 * @property float|null $total
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Cart $cart
 * @property User $user
 * @property Product $product
 * @property Collection|Service[] $services
 * @property Collection|OrderItem[] $order_items
 *
 * @package App\Models
 */
class CartItem extends Model
{
    use SoftDeletes;

    protected $table = 'cart_items';

    protected $casts = [
        'cart_id' => 'int',
        'product_id' => 'int',
        'store_id' => 'int',
        'qty' => 'int',
        'price' => 'float',
        'total' => 'float'
    ];

    protected $fillable = [
        'cart_id',
        'store_id',
        'product_id',
        'qty',
        'price',
        'notes'
    ];

    protected $dates = ['deleted_at'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'store_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function services()
    // {
    // 	return $this->belongsToMany(Service::class, 'cart_items_services')
    // 				->withPivot('id', 'price')
    // 				->withTimestamps();
    // }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function storeService()
    {
        return $this->belongsTo(StoresService::class);
    }

    public function services()
    {
        return $this->hasMany(CartItemService::class, 'cart_item_id');
    }
}
