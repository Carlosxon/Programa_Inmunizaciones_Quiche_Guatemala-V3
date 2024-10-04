<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventoryExitItem
 * 
 * @property int $id
 * @property int $inventory_exit_id
 * @property int $inventory_item_id
 * @property int $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property InventoryExit $inventory_exit
 * @property Inventory $inventory
 *
 * @package App\Models
 */
class InventoryExitItem extends Model
{
	protected $table = 'inventory_exit_items';

	protected $casts = [
		'inventory_exit_id' => 'int',
		'inventory_item_id' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'inventory_exit_id',
		'inventory_item_id',
		'quantity'
	];

	public function inventory_exit()
	{
		return $this->belongsTo(InventoryExit::class);
	}

	public function inventory()
	{
		return $this->belongsTo(Inventory::class, 'inventory_item_id');
	}
}
