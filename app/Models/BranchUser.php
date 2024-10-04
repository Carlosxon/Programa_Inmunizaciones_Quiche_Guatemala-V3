<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BranchUser
 * 
 * @property int $id
 * @property int $branch_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Branch $branch
 * @property User $user
 *
 * @package App\Models
 */
class BranchUser extends Model
{
	protected $table = 'branch_user';

	protected $casts = [
		'branch_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'branch_id',
		'user_id'
	];

	public function branch()
	{
		return $this->belongsTo(Branch::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
