<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class HomeBanner
 *
 * @property int $id
 * @property string $image
 * @property string $direction_type
 * @property string $direction_id
 * @property Carbon $expiration_date
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class HomeBanner extends Model
{
    use SoftDeletes;

    protected $table = 'home_banners';

    protected $casts = [
        'expiration_date' => 'datetime'
    ];

    protected $fillable = [
        'image',
        'direction_type',
        'direction_id',
        'expiration_date'
    ];

    protected $dates = ['deleted_at'];

    public function buildImage()
    {
        return $this->image != "" ? url("storage/banners") . "/" . $this->image : url('dist/assets/img/empty.png');
    }
}
