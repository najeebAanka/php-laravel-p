<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $name_ar
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string $user_type
 * @property string|null $otp
 * @property int $isEmailVerified
 * @property int $isPhoneVerified
 * @property int|null $phone
 * @property string|null $forgot_password_token
 * @property string|null $provider
 * @property string|null $provider_id
 * @property string|null $provider_access_token
 * @property int $current_order
 * @property string $status
 * @property float $latitude
 * @property float $longitude
 * @property string|null $image
 * @property int $receive_notification
 * @property int $delivery_fee
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|CartItem[] $cart_items
 * @property Collection|OrderItem[] $order_items
 * @property Collection|StoresService[] $stores_services
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use SoftDeletes;
	use HasApiTokens;
	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime',
		'isEmailVerified' => 'int',
		'isPhoneVerified' => 'int',
		'phone' => 'int',
		'current_order' => 'int',
		'latitude' => 'float',
		'longitude' => 'float',
		'receive_notification' => 'int',
		'delivery_fee' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token',
		'forgot_password_token',
		'provider_access_token'
	];

	protected $fillable = [
		'name',
		'name_ar',
		'email',
		'email_verified_at',
		'password',
		'remember_token',
		'user_type',
		'otp',
		'isEmailVerified',
		'isPhoneVerified',
		'phone',
		'forgot_password_token',
		'provider',
		'provider_id',
		'device_token',
		'provider_access_token',
		'current_order',
		'status',
		'latitude',
		'longitude',
		'image',
		'receive_notification',
		'delivery_fee'
	];

	protected $dates = ['deleted_at'];

	public function cart_items()
	{
		return $this->hasMany(CartItem::class, 'store_id');
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class, 'store_id');
	}

	public function stores_services()
	{
		return $this->hasMany(StoresService::class, 'store_id');
	}

	public function buildImage()
    {
        return $this->image != "" ? url("storage/users") . "/" . $this->image : url('dist/assets/img/empty.png');
    }

	public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'target_id');
    }
}
