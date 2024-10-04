<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Warehouse
 * 
 * @property int $id
 * @property string $name
 * @property string|null $location
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $type
 * @property string|null $status
 * 
 * @property Collection|Inventory[] $inventories
 * @property Collection|Product[] $products
 * @property Collection|StockAdjustment[] $stock_adjustments
 * @property Collection|StockTransfer[] $stock_transfers
 * @property Collection|Stock[] $stocks
 * @property Collection|Transfer[] $transfers
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Warehouse extends Model
{
	protected $table = 'warehouses';

	protected $fillable = [
		'name',
		'location',
		'type',
		'status'
	];

	public function inventories()
	{
		return $this->hasMany(Inventory::class);
	}

	public function products()
	{
		return $this->hasMany(Product::class);
	}

	public function stock_adjustments()
	{
		return $this->hasMany(StockAdjustment::class);
	}

	public function stock_transfers()
	{
		return $this->hasMany(StockTransfer::class, 'to_warehouse_id');
	}

	public function stocks()
	{
		return $this->hasMany(Stock::class);
	}

	public function transfers()
	{
		return $this->hasMany(Transfer::class, 'to_warehouse_id');
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'warehouse_user')
					->withPivot('id')
					->withTimestamps();
	}
}
