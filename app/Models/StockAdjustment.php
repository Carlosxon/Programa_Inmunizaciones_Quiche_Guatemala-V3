<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StockAdjustment
 * 
 * @property int $id
 * @property int $product_id
 * @property int $warehouse_id
 * @property int $adjustment_quantity
 * @property string|null $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 * @property Warehouse $warehouse
 *
 * @package App\Models
 */
class StockAdjustment extends Model
{
	protected $table = 'stock_adjustments';

	protected $casts = [
		'product_id' => 'int',
		'warehouse_id' => 'int',
		'adjustment_quantity' => 'int'
	];

	protected $fillable = [
		'product_id',
		'warehouse_id',
		'adjustment_quantity',
		'reason'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class);
	}
}
