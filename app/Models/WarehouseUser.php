<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WarehouseUser
 * 
 * @property int $id
 * @property int $user_id
 * @property int $warehouse_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Warehouse $warehouse
 *
 * @package App\Models
 */
class WarehouseUser extends Model
{
	protected $table = 'warehouse_user';

	protected $casts = [
		'user_id' => 'int',
		'warehouse_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'warehouse_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class);
	}
}
