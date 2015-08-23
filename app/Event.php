<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	/**
	 * Define the constants for the event type codes.
	 */
	const TYPE_EVENT    = 1;
	const TYPE_TRAINING = 2;
	const TYPE_SOCIAL   = 3;
	const TYPE_MEETING  = 4;
	const TYPE_HIDDEN   = 5;
	const TYPE_BOOKING  = 6;


	/**
	 * Define the types of events.
	 * @var array
	 */
	public static $Types = [
		self::TYPE_EVENT    => 'Event',
		self::TYPE_TRAINING => 'Training',
		self::TYPE_SOCIAL   => 'Social',
		self::TYPE_MEETING  => 'Meeting',
		self::TYPE_HIDDEN   => 'Hidden (BTS only)',
	];

	/**
	 * Define the HTML classes for each event type.
	 * @var array
	 */
	public static $TypeClasses = [
		self::TYPE_EVENT    => 'event',
		self::TYPE_TRAINING => 'training',
		self::TYPE_SOCIAL   => 'social',
		self::TYPE_MEETING  => 'meeting',
		self::TYPE_HIDDEN   => 'bts',
		self::TYPE_BOOKING  => 'booking',
	];

	/**
	 * Define the client types.
	 * @var array
	 */
	public static $Clients = [
		1 => 'Students\' Union',
		2 => 'University',
		3 => 'External',
	];

	/**
	 * Define the venue types.
	 * @var array
	 */
	public static $VenueTypes = [
		1 => 'On-campus',
		2 => 'Off-campus',
	];

	/**
	 * The attributes fillable by mass assignment.
	 * @var array
	 */
	protected $fillable = [
		'name',
		'venue',
		'em_id',
		'description',
		'description_public',
		'type',
		'crew_list_status',
		'client_type',
		'venue_type',
	];

	/**
	 * Define the foreign key relationship with the EM.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function em()
	{
		return $this->belongsTo('App\User', 'em_id');
	}

	/**
	 * Define the foreign key relationship with the event times.
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function times()
	{
		return $this->hasMany('App\EventTime');
	}

	/**
	 * Get a list of events which have one or more event times on a particular date.
	 * @param                $query
	 * @param \Carbon\Carbon $date
	 */
	public function scopeOnDate($query, Carbon $date)
	{
		// Get the times for the beginning and end of the day
		$start = $date->setTime(0, 0, 0)->toDateTimeString();
		$end   = $date->setTime(23, 59, 59)->toDateTimeString();

		// Build the query
		$query->select('events.*')
		      ->join('event_times', 'events.id', '=', 'event_times.event_id')
		      ->whereNested(function ($query) use ($start, $end) {
			      $query->whereBetween('event_times.start', [$start, $end])
			            ->orWhere(function ($query) use ($start, $end) {
				            $query->whereBetween('event_times.end', [$start, $end]);
			            });
		      })
		      ->distinct();
	}

	/**
	 * Get a list of events that start in the future.
	 * @param $query
	 */
	public function scopeFuture($query)
	{
		$query->select('events.*')
		      ->join('event_times', 'events.id', '=', 'event_times.event_id')
		      ->where('event_times.start', '>', Carbon::now()->toDateTimeString())
		      ->distinct();
	}

	/**
	 * Order the events by when they start, ascending (soonest first)
	 * @param $query
	 */
	public function scopeOrderAsc($query)
	{
		$query->orderBy('event_times.start', 'ASC');
	}

	/**
	 * Get the type as a human-readable string.
	 * @return string
	 */
	public function getTypeStringAttribute()
	{
		return isset(self::$Types[$this->type]) ? self::$Types[$this->type] : self::$Types[self::TYPE_EVENT];
	}

	/**
	 * Get the HTML class of the type.
	 * @return string
	 */
	public function getTypeClassAttribute()
	{
		return isset(self::$TypeClasses[$this->type]) ? self::$TypeClasses[$this->type] : self::$TypeClasses[self::TYPE_EVENT];
	}

	/**
	 * Get the earliest start time for an event on a particular day.
	 * @param \Carbon\Carbon $date
	 * @return string
	 */
	public function getEarliestStart(Carbon $date)
	{
		$date->setTime(23, 59, 59);
		foreach($this->times as $time) {
			if($time->start->isSameDay($date) && $time->start->lt($date)) {
				$date->setTime($time->start->hour, $time->start->minute, $time->start->second);
			}
		}

		return $date->format('H:i');
	}

	/**
	 * Get the latest end time for an event on a particular day.
	 * @param \Carbon\Carbon $date
	 * @return string
	 */
	public function getLatestEnd(Carbon $date)
	{
		$date->setTime(00, 00, 00);
		foreach($this->times as $time) {
			if($time->end->isSameDay($date) && $time->end->gt($date)) {
				$date->setTime($time->end->hour, $time->end->minute, $time->end->second);
			}
		}

		return $date->format('H:i');
	}

	/**
	 * Check if the event is a training session.
	 * @return bool
	 */
	public function isTraining()
	{
		return $this->type == self::TYPE_TRAINING;
	}

	/**
	 * Check if the event is a social.
	 * @return bool
	 */
	public function isSocial()
	{
		return $this->type == self::TYPE_SOCIAL;
	}

	/**
	 * Check if crew 'attendance' needs to be tracked.
	 * @return bool
	 */
	public function isTracked()
	{
		return $this->isTraining() || $this->isSocial();
	}
}
