<?php

namespace App;

class EventCrew extends Model
{
	/**
	 * The database table used by the model.
	 * @var string
	 */
	protected $table = 'event_crew';

	/**
	 * Disable timestamps
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The attributes fillable by mass assignment.
	 * @var array
	 */
	protected $fillable = [
		'event_id',
		'user_id',
		'name',
		'em',
		'confirmed',
	];

	/**
	 * Define the foreign key relationship with the event.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function event()
	{
		return $this->belongsTo('App\Event');
	}

	/**
	 * Define the foreign key relationship with the user.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('App\User');
	}
}