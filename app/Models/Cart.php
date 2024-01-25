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
 * Class Cart
 * 
 * @property int $id
 * @property int $user_id
 * @property string $user_ip
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|CartItem[] $cart_items
 * @property Collection|OrderItem[] $order_items
 *
 * @package App\Models
 */
class Cart extends Model
{
	use SoftDeletes;

	protected $table = 'carts';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'user_ip',
		'device_id',
		'status'
	];

	protected $dates = ['deleted_at'];

	public function cart_items()
	{
		return $this->hasMany(CartItem::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
