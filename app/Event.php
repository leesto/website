<?php

namespace App;

use Carbon\Carbon;

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
	 * Define the types of paperwork
	 * @var array
	 */
	public static $Paperwork = [
		'risk_assessment'  => 'Risk Assessment',
		'insurance'        => 'Insurance',
		'finance_em'       => 'EM Finance',
		'finance_treas'    => 'Treasurer Finance',
		'event_report'     => 'Event Report',
		'committee_report' => 'Committee Report',
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
		'paperwork',
	];

	/**
	 * Define any type-casting.
	 * @var array
	 */
	protected $casts = [
		'paperwork' => 'array',
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
		return $this->hasMany('App\EventTime')
		            ->orderBy('start', 'ASC');
	}

	/**
	 * Define the foreign key relationship with the event crew.
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function crew()
	{
		return $this->hasMany('App\EventCrew')
		            ->select('event_crew.*')
		            ->join('users', 'event_crew.user_id', '=', 'users.id')
		            ->orderBy('surname', 'ASC')
		            ->orderBy('forename', 'ASC');
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
	 * Add a scope for selecting events which are in the future.
	 * @param $query
	 */
	public function scopeFuture($query)
	{
		$query->select('events.*')
		      ->join('event_times', 'events.id', '=', 'event_times.event_id')
		      ->where('event_times.start', '>', Carbon::now()->setTime(0, 0, 0)->toDateTimeString())
		      ->distinct();
	}

	/**
	 * Add a scope for selecting events which are currently occuring.
	 * @param $query
	 */
	public function scopeActive($query)
	{
		$now = Carbon::now();
		$query->select('events.*')
		      ->join('event_times', 'events.id', '=', 'event_times.event_id')
		      ->where('event_times.start', '>=', $now->setTime(0, 0, 0)->toDateTimeString())
		      ->where('event_times.end', '<=', $now->setTime(23, 59, 59)->toDateTimeString())
		      ->distinct();
	}

	/**
	 * Add a scope for selecting events which have finished.
	 * @param $query
	 */
	public function scopePast($query)
	{
		$query->select('events.*')
		      ->join('event_times', 'events.id', '=', 'event_times.event_id')
		      ->where('event_times.end', '<', Carbon::now()->setTime(23, 59, 59)->toDateTimeString())
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
	 * Get the client type as a human-readable string.
	 * @return mixed
	 */
	public function getClientAttribute()
	{
		return isset(self::$Clients[$this->client_type]) ? self::$Clients[$this->client_type] : self::$Clients[1];
	}

	/**
	 * Get the crew list, ordered by crew role.
	 * @return array
	 */
	public function getCrewListAttribute()
	{
		$core    = [];
		$general = [];

		foreach($this->crew()->orderBy('event_crew.name')->get() as $crew) {
			if($crew->name) {
				@$core[$crew->name][] = $crew;
			} else {
				$general[] = $crew;
			}
		}

		return $core + (empty($general) ? [] : ['General Crew' => $general]);
	}

	/**
	 * Get the event times, ordered by date.
	 * @return array
	 */
	public function getEventTimesAttribute()
	{
		$days = [];
		foreach($this->times as $time) {
			$date = $time->start->format('d/m/Y');
			@$days[$date][] = $time;
		}

		return $days;
	}

	public function getStartDateAttribute()
	{
		return $this->times->first()->start->format('d/m/Y');
	}

	public function getEndDateAttribute()
	{
		return $this->times->last()->end->format('d/m/Y');
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

	/**
	 * Test if the event's crew list is open.
	 * @return bool
	 */
	public function crewListOpen()
	{
		return $this->crew_list_status == 1;
	}

	/**
	 * Test if a user is the EM.
	 * @param \App\User $user
	 * @param bool      $officialOnly
	 * @return bool
	 */
	public function isEM(User $user, $officialOnly = true)
	{
		if($this->em_id && $this->em_id == $user->id) {
			return true;
		} else if(!$officialOnly) {
			foreach($this->crew as $crew) {
				if($crew->user_id == $user->id && $crew->em) {
					return true;
				}
			}

			return false;
		}

		return false;
	}

	/**
	 * Test if a user is a crew member.
	 * @param \App\User $user
	 * @return bool
	 */
	public function isCrew(User $user)
	{
		if($this->isEM($user)) {
			return true;
		}
		foreach($this->crew as $crew) {
			if($crew->user_id == $user->id) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Test if the event has an EM.
	 * @return bool
	 */
	public function hasEM()
	{
		return !!$this->em_id;
	}
}
