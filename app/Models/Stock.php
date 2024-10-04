<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Stock
 * 
 * @property int $id
 * @property int $product_id
 * @property int $warehouse_id
 * @property int $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 * @property Warehouse $warehouse
 *
 * @package App\Models
 */
class Stock extends Model
{
	protected $table = 'stocks';

	protected $casts = [
		'product_id' => 'int',
		'warehouse_id' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'product_id',
		'warehouse_id',
		'quantity'
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
