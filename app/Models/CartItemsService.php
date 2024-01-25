<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CartItemsService
 * 
 * @property int $id
 * @property int $cart_item_id
 * @property int $service_id
 * @property float|null $price
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CartItem $cart_item
 * @property Service $service
 *
 * @package App\Models
 */
class CartItemsService extends Model
{

	use HasFactory, SoftDeletes;

	protected $table = 'cart_items_services';

	protected $casts = [
		'cart_item_id' => 'int',
		'service_id' => 'int',
		'price' => 'float'
	];

	protected $fillable = [
		'cart_item_id',
		'service_id',
		'price'
	];

	protected $dates = ['deleted_at'];
	
	public function cart_item()
	{
		return $this->belongsTo(CartItem::class);
	}

	public function service()
	{
		return $this->belongsTo(Service::class);
	}

}
