<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StockTransfer
 * 
 * @property int $id
 * @property int $from_warehouse_id
 * @property int $to_warehouse_id
 * @property Carbon $transfer_date
 * @property bool $is_received
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Warehouse $warehouse
 * @property Collection|Transfer[] $transfers
 *
 * @package App\Models
 */
class StockTransfer extends Model
{
	protected $table = 'stock_transfers';

	protected $casts = [
		'from_warehouse_id' => 'int',
		'to_warehouse_id' => 'int',
		'transfer_date' => 'datetime',
		'is_received' => 'bool'
	];

	protected $fillable = [
		'from_warehouse_id',
		'to_warehouse_id',
		'transfer_date',
		'is_received'
	];

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
	}

	public function transfers()
	{
		return $this->hasMany(Transfer::class);
	}
}
