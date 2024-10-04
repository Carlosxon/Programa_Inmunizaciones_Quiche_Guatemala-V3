<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transfer
 * 
 * @property int $id
 * @property int $stock_transfer_id
 * @property int $from_warehouse_id
 * @property int $to_warehouse_id
 * @property int $product_id
 * @property int $quantity
 * @property Carbon $transfer_date
 * @property string $status
 * @property bool $is_received
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Warehouse $warehouse
 * @property Product $product
 * @property StockTransfer $stock_transfer
 *
 * @package App\Models
 */
class Transfer extends Model
{
	protected $table = 'transfers';

	protected $casts = [
		'stock_transfer_id' => 'int',
		'from_warehouse_id' => 'int',
		'to_warehouse_id' => 'int',
		'product_id' => 'int',
		'quantity' => 'int',
		'transfer_date' => 'datetime',
		'is_received' => 'bool'
	];

	protected $fillable = [
		'stock_transfer_id',
		'from_warehouse_id',
		'to_warehouse_id',
		'product_id',
		'quantity',
		'transfer_date',
		'status',
		'is_received'
	];

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function stock_transfer()
	{
		return $this->belongsTo(StockTransfer::class);
	}
}
