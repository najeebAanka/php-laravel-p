<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrderItemsService
 * 
 * @property int $id
 * @property int $order_item_id
 * @property int $service_id
 * @property float|null $price
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property OrderItem $order_item
 * @property Service $service
 *
 * @package App\Models
 */
class OrderItemsService extends Model
{
	use SoftDeletes;

	protected $table = 'order_items_services';

	protected $casts = [
		'order_item_id' => 'int',
		'service_id' => 'int',
		'price' => 'float'
	];

	protected $fillable = [
		'order_item_id',
		'service_id',
		'price',
		'status'
	];

	protected $dates = ['deleted_at'];

	public function order_item()
	{
		return $this->belongsTo(OrderItem::class);
	}

	public function service()
	{
		return $this->belongsTo(Service::class);
	}
}
