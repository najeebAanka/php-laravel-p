<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrderItemStatusLog
 *
 * @property int $id
 * @property int $order_item_id
 * @property string|null $status
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @property OrderItem $order_item
 *
 * @package App\Models
 */
class OrderItemStatusLog extends Model
{
    use SoftDeletes;

    protected $table = 'order_item_status_log';

    protected $casts = [
        'order_item_id' => 'int'
    ];

    protected $fillable = [
        'order_item_id',
        'service_id',
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

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}
