<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
	/**
	 * The attributes fillable by mass-assignment.
	 * @var array
	 */
	protected $fillable = [
		'user_id'
	];

	/**
	 * Define the relationship with the option.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function option()
	{
		return $this->belongsTo('App\PollOption', 'option_id');
	}

	/**
	 * Define the relationship with the user.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('App\User');
	}
}
