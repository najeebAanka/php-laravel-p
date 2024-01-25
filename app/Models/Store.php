<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Store
 * 
 * @property int $id
 * @property string|null $name_en
 * @property string|null $name_ar
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Store extends Model
{
	use SoftDeletes;

	protected $table = 'stores';

	protected $fillable = [
		'name_en',
		'name_ar'
	];

	protected $dates = ['deleted_at'];

}
