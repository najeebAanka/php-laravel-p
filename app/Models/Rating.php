<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Rating
 * 
 * @property int $id
 * @property string $target_type
 * @property int $target_id
 * @property int $rated_by
 * @property int $rate_value
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Rating extends Model
{
	use SoftDeletes;

	protected $table = 'ratings';

	protected $casts = [
		'target_id' => 'int',
		'rated_by' => 'int',
		'rate_value' => 'int'
	];

	protected $fillable = [
		'target_type',
		'target_id',
		'rated_by',
		'rate_value'
	];

	protected $dates = ['deleted_at'];

}
