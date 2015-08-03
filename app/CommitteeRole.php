<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Szykra\Notifications\Flash;

class CommitteeRole extends Model
{
	/**
	 * Disable timestamps
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The attributes filleable by mass-assignment.
	 * @var array
	 */
	protected $fillable = [
		'name',
		'description',
		'email',
		'user_id',
		'order',
	];

	/**
	 * Create the relationship with the user.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function setUserIdAttribute($value)
	{


		// Update user permissions
		$role   = Role::where('name', 'committee')->get()->first();
		$old_id = $this->attributes['user_id'];
		if($role && $old_id != $value) {
			if($old_id == Auth::user()->id) {
				Flash::warning('You can\'t remove yourself from the committee');

				return;
			}

			// Remove from committee
			$old_user = User::find($old_id);
			if($old_user && $old_user->hasRole($role->name)) {
				$old_user->detachRole($role->id);
			}

			// Add to committee
			$new_user = User::find($value);
			if($new_user && !$new_user->hasRole($role->name)) {
				$new_user->roles()->attach($role);
			}
		}

		// Set the new id
		$this->attributes['user_id'] = $value;
	}
}
