<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property int $stock
 * @property int $category_id
 * @property int $brand_id
 * @property int $unit_id
 * @property int|null $warehouse_id
 * @property string|null $barcode
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Brand $brand
 * @property Category $category
 * @property Unit $unit
 * @property Warehouse|null $warehouse
 * @property Collection|Inventory[] $inventories
 * @property Collection|StockAdjustment[] $stock_adjustments
 * @property Collection|Stock[] $stocks
 * @property Collection|Transfer[] $transfers
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'products';

	protected $casts = [
		'price' => 'float',
		'stock' => 'int',
		'category_id' => 'int',
		'brand_id' => 'int',
		'unit_id' => 'int',
		'warehouse_id' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'price',
		'stock',
		'category_id',
		'brand_id',
		'unit_id',
		'warehouse_id',
		'barcode'
	];

	public function brand()
	{
		return $this->belongsTo(Brand::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function unit()
	{
		return $this->belongsTo(Unit::class);
	}

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class);
	}

	public function inventories()
	{
		return $this->hasMany(Inventory::class);
	}

	public function stock_adjustments()
	{
		return $this->hasMany(StockAdjustment::class);
	}

	public function stocks()
	{
		return $this->hasMany(Stock::class);
	}

	public function transfers()
	{
		return $this->hasMany(Transfer::class);
	}
}
