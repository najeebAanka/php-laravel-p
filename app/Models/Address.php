<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Address
 * 
 * @property int $id
 * @property int $user_id
 * @property string $country
 * @property string $city
 * @property string $street
 * @property string $building
 * @property string $floor
 * @property string $flat
 * @property int $is_default
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Address extends Model
{
	use SoftDeletes;

	protected $table = 'addresses';

	protected $casts = [
		'user_id' => 'int',
		'is_default' => 'int'
	];

	protected $fillable = [
		'user_id',
		'country',
		'city',
		'street',
		'building',
		'floor',
		'flat',
		'is_default'
	];

	protected $dates = ['deleted_at'];
}
