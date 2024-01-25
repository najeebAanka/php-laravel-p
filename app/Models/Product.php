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
 * Class Product
 * 
 * @property int $id
 * @property string|null $name_en
 * @property string|null $name_ar
 * @property string|null $image
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|CartItem[] $cart_items
 * @property Collection|OrderItem[] $order_items
 * @property Collection|StoresService[] $stores_services
 *
 * @package App\Models
 */
class Product extends Model
{
	use SoftDeletes;

	protected $table = 'products';

	protected $fillable = [
		'name_en',
		'name_ar',
		'image'
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

	public function stores_services()
	{
		return $this->hasMany(StoresService::class);
	}
	
	public function buildImage()
	{
		return $this->image!="" ? url("storage/products") . "/" .$this->image:url('dist/assets/img/empty.png');
	}
}
