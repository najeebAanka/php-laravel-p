<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Notification
 * 
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property int $is_read
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Notification extends Model
{
	use SoftDeletes;

	protected $table = 'notifications';

	protected $casts = [
		'user_id' => 'int',
		'order_id' => 'int',
		'is_read' => 'int'
	];

	protected $fillable = [
		'user_id',
		'order_id',
		'content_en',
		'content_ar',
		'is_read'
	];

	protected $dates = ['deleted_at'];
}
