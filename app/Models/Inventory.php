<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Inventory
 * 
 * @property int $id
 * @property int $warehouse_id
 * @property int $product_id
 * @property string $product_name
 * @property int $quantity
 * @property Carbon $acquisition_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 * @property Warehouse $warehouse
 * @property Collection|InventoryExitItem[] $inventory_exit_items
 *
 * @package App\Models
 */
class Inventory extends Model
{
	protected $table = 'inventories';

	protected $casts = [
		'warehouse_id' => 'int',
		'product_id' => 'int',
		'quantity' => 'int',
		'acquisition_date' => 'datetime'
	];

	protected $fillable = [
		'warehouse_id',
		'product_id',
		'product_name',
		'quantity',
		'acquisition_date'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class);
	}

	public function inventory_exit_items()
	{
		return $this->hasMany(InventoryExitItem::class, 'inventory_item_id');
	}
}
