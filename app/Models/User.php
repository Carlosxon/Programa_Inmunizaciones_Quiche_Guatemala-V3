<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property bool $is_admin
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $photo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $warehouse_id
 * 
 * @property Warehouse|null $warehouse
 * @property Collection|Branch[] $branches
 * @property Collection|InventoryExit[] $inventory_exits
 * @property Collection|Permission[] $permissions
 * @property Collection|Role[] $roles
 * @property Collection|Warehouse[] $warehouses
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime',
		'is_admin' => 'bool',
		'warehouse_id' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token',
		'is_admin',
		'address',
		'phone',
		'photo',
		'warehouse_id'
	];

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class);
	}

	public function branches()
	{
		return $this->hasMany(Branch::class, 'manager_id');
	}

	public function inventory_exits()
	{
		return $this->hasMany(InventoryExit::class);
	}

	public function permissions()
	{
		return $this->belongsToMany(Permission::class)
					->withPivot('id')
					->withTimestamps();
	}

	public function roles()
	{
		return $this->belongsToMany(Role::class);
	}

	public function warehouses()
	{
		return $this->belongsToMany(Warehouse::class, 'warehouse_user')
					->withPivot('id')
					->withTimestamps();
	}
}
