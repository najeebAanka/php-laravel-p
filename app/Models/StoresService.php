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
 * Class StoresService
 * 
 * @property int $id
 * @property int|null $store_id
 * @property int|null $product_id
 * @property int|null $service_id
 * @property float|null $price
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product|null $product
 * @property Service|null $service
 * @property User|null $user
 * @property Collection|CartItem[] $cart_items
 * @property Collection|OrderItem[] $order_items
 *
 * @package App\Models
 */
class StoresService extends Model
{
	use SoftDeletes;

	protected $table = 'stores_services';

	protected $casts = [
		'store_id' => 'int',
		'product_id' => 'int',
		'service_id' => 'int',
		'price' => 'float'
	];

	protected $fillable = [
		'store_id',
		'product_id',
		'service_id',
		'price'
	];

	protected $dates = ['deleted_at'];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function service()
	{
		return $this->belongsTo(Service::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'store_id');
	}

	public function cart_items()
	{
		return $this->hasMany(CartItem::class, 'service_id');
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class, 'service_id');
	}
}
