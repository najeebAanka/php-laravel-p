<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AppSetting
 * 
 * @property int $id
 * @property string $code
 * @property string $value_en
 * @property string $value_ar
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class AppSetting extends Model
{
	use SoftDeletes;

	protected $table = 'app_settings';

	protected $fillable = [
		'code',
		'value_en',
		'value_ar'
	];

	protected $dates = ['deleted_at'];
}
