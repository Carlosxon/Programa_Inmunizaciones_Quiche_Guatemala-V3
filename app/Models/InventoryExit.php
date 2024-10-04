<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class InventoryExit
 * 
 * @property int $id
 * @property string $exit_number
 * @property Carbon $exit_date
 * @property int $user_id
 * @property string $destination
 * @property string|null $description
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property Collection|InventoryExitItem[] $inventory_exit_items
 *
 * @package App\Models
 */
class InventoryExit extends Model
{
	use SoftDeletes;
	protected $table = 'inventory_exits';

	protected $casts = [
		'exit_date' => 'datetime',
		'user_id' => 'int'
	];

	protected $fillable = [
		'exit_number',
		'exit_date',
		'user_id',
		'destination',
		'description',
		'status'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function inventory_exit_items()
	{
		return $this->hasMany(InventoryExitItem::class);
	}
}
