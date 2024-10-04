<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Branch
 * 
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int|null $manager_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Branch extends Model
{
	protected $table = 'branches';

	protected $casts = [
		'manager_id' => 'int'
	];

	protected $fillable = [
		'name',
		'address',
		'manager_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'manager_id');
	}

	public function users()
	{
		return $this->belongsToMany(User::class)
					->withPivot('id')
					->withTimestamps();
	}
}
