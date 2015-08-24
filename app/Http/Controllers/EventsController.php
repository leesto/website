<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventTime;
use App\Http\Requests;
use App\Http\Requests\EventTimeRequest;
use App\Http\Requests\GenericRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Szykra\Notifications\Flash;

class EventsController extends Controller
{
	/**
	 * Set up the middleware permissions.
	 */
	public function __construct()
	{
		$this->middleware('auth.permission:admin', [
			'only' => [
				'create',
				'store',
				'index',
			],
		]);
		$this->middleware('auth.permission:member', [
			'only' => [
				'signup',
			],
		]);

		parent::__construct();
	}

	public function index()
	{
		$events = Event::select('events.*')
		               ->join('event_times', 'events.id', '=', 'event_times.event_id')
		               ->orderBy('event_times.start', 'DESC')
		               ->distinct()
		               ->distinctPaginate(15);
		$this->checkPagination($events);

		return View::make('events.index')->withEvents($events);
	}

	/**
	 * View the event's diary
	 * @param null $year
	 * @param null $month
	 * @return Response
	 */
	public function diary($year = null, $month = null)
	{
		// Get the dates
		$date = $year && $month ? Carbon::create($year, $month, 1) : Carbon::now();

		// Get the events
		$events = $this->getEventsInMonth($date, !$this->user->isMember());

		// Build the calendar
		return $this->renderEventsDiary($date, $events);
	}

	/**
	 * View an event.
	 * @param $id
	 * @return Response
	 */
	public function view($id)
	{
		// Get the event
		$event = Event::findOrFail($id);

		// Check the user can view it
		if($event->type != Event::TYPE_EVENT && !$this->user->isMember()) {
			App::abort(403);
		}

		return View::make('events.view')->with([
			'event'    => $event,
			'user'     => $this->user,
			'isMember' => $this->user->isMember(),
			'isAdmin'  => $this->user->isAdmin(),
			'isEM'     => $event->isEM($this->user),
			'canEdit'  => $this->user->isAdmin() || $event->isEM($this->user, false),
		]);
	}

	/**
	 * Update the details of an event.
	 * @param                                   $id
	 * @param                                   $action
	 * @param \App\Http\Requests\GenericRequest $request
	 * @return Response
	 */
	public function update($id, $action, GenericRequest $request)
	{
		// Make sure the request is AJAX
		$this->requireAjax($request);

		// Get the event
		$event = Event::find($id);
		if(!$event) {
			return Response::json(['error' => 'Couldn\'t find the event'], 404);
		}

		// Check that the user is either the EM or an admin
		if(!$this->user->isAdmin() && !$event->isEM($this->user)) {
			return Response::json(['error' => 'You need to be the EM or an admin to do that'], 422);
		}

		switch($action) {
			case 'add-time':
				return $this->updateAddTime($request, $event);
			case 'update-time':
				return $this->updateEditTime($request, $event);
			case 'delete-time':
				return $this->updateDeleteTime($request, $event);
			default:
				return Response::json(['error' => 'Unknown action'], 404);
		}
	}

	/**
	 * View the events needing an EM or crew.
	 * @return Response
	 */
	public function signup()
	{
		$require_em = Event::whereNull('em_id')
		                   ->future()
		                   ->orderBy('event_times.start', 'ASC')
		                   ->paginate(15);

		return View::make('events.signup')->with(compact('require_em'));
	}

	/**
	 * View the form to add an event.
	 * @return mixed
	 */
	public function create()
	{
		return View::make('events.create');
	}

	/**
	 * Add an event to the diary.
	 * @param \App\Http\Requests\GenericRequest $request
	 * @return Response
	 */
	public function store(GenericRequest $request)
	{
		// Validate the form
		$this->validate($request, [
			'name'        => 'required',
			'em_id'       => 'exists:users,id',
			'type'        => 'required|in:' . implode(',', array_keys(Event::$Types)),
			'description' => 'required',
			'venue'       => 'required',
			'venue_type'  => 'required|in:' . implode(',', array_keys(Event::$VenueTypes)),
			'client_type' => 'required|in:' . implode(',', array_keys(Event::$Clients)),
			'date_start'  => 'required|date_format:d/m/Y',
			'date_end'    => 'required|date_format:d/m/Y|after:date_start',
		], [
			'name.required'          => 'Please enter the event\'s name',
			'em_id.exists'           => 'Please select a valid user',
			'type.required'          => 'Please select an event type',
			'type.in'                => 'Please select a valid event type',
			'description.required'   => 'Please enter a description of the event',
			'venue.required'         => 'Please enter the venue',
			'venue_type.required'    => 'Please select the venue type',
			'venue_type.in'          => 'Please select a valid venue type',
			'client_type.required'   => 'Please select a client type',
			'client_type.in'         => 'Please select a valid client type',
			'date_start.required'    => 'Please enter when this event starts',
			'date_start.date_format' => 'Please enter a valid date',
			'date_end.required'      => 'Please enter when this event ends',
			'date_end.date_format'   => 'Please enter a valid date',
			'date_end.after'         => 'This must be after the start date',
		]);

		// Create the event
		$event = Event::create($request->stripped('name', 'venue', 'description', 'type', 'client_type', 'venue_type') + [
				'em_id'              => $request->get('em_id') ?: null,
				'description_public' => $request->get('desc_public') ? $request->stripped('description') : '',
				'crew_list_status'   => 1,
			]);

		// Create the event times
		$date_start = Carbon::createFromFormat('d/m/Y', $request->get('date_start'))->setTime(0, 0, 0);
		$date_end   = Carbon::createFromFormat('d/m/Y', $request->get('date_end'))->setTime(23, 59, 59);
		$date       = clone $date_start;
		while($date->lte($date_end)) {
			$event->times()->save(new EventTime([
				'name'  => $event->name,
				'start' => $date->setTime(0, 0, 0)->toDateTimeString(),
				'end'   => $date->setTime(23, 59, 59)->toDateTimeString(),
			]));
			$date->setTime(0, 0, 0);
			$date->day++;
		}

		// Create a flash message and redirect
		Flash::success('Event created');

		return redirect(route('events.diary', [
			'year'  => $date_start->year,
			'month' => $date_start->month,
		]));
	}

	/**
	 * Render the diary for a set of given events
	 * @param \Carbon\Carbon $date
	 * @param array          $calendar
	 * @return mixed
	 */
	private function renderEventsDiary(Carbon $date, array $calendar)
	{
		// Set the previous and next months
		$date->day = 1;
		$date_next = clone $date;
		$date_prev = clone $date;
		$date_next->addMonth();
		$date_prev->subMonth();

		// Calculate the spacings before and after the calendar
		$date->day    = 1;
		$blank_before = ($date->dayOfWeek ?: 7) - 1;
		$date->day    = $date->daysInMonth;
		$blank_after  = 7 - ($date->dayOfWeek ?: 7);

		return View::make('events.diary')->with([
			'title'        => 'Events Diary',
			'date'         => $date,
			'date_next'    => $date_next,
			'date_prev'    => $date_prev,
			'calendar'     => $calendar,
			'blank_before' => $blank_before,
			'blank_after'  => $blank_after,
		]);
	}

	/**
	 * @param \Carbon\Carbon $date
	 * @param bool           $public
	 * @return array
	 */
	private function getEventsInMonth(Carbon $date, $public = true)
	{
		$events = [];
		for($i = 1; $i <= $date->daysInMonth; $i++) {
			$date->day  = $i;
			$events[$i] = Event::onDate($date)
			                   ->whereIn('events.type', $public ? [Event::TYPE_EVENT] : array_keys(Event::$Types))
			                   ->orderBy('event_times.start', 'ASC')
			                   ->get();
		}

		return $events;
	}

	/**
	 * Add a new event time.
	 * @param \App\Http\Requests\GenericRequest $request
	 * @param \App\Event                        $event
	 * @return \Illuminate\Support\Facades\Response
	 */
	private function updateAddTime(GenericRequest $request, Event $event)
	{
		// Validate
		$this->validateEventTime($request);

		// Create the time
		$event->times()->save(new EventTime([
			'name'  => $request->get('name'),
			'start' => Carbon::createFromFormat('d/m/Y H:i', $request->get('date') . ' ' . $request->get('start_time')),
			'end'   => Carbon::createFromFormat('d/m/Y H:i', $request->get('date') . ' ' . $request->get('end_time')),
		]));

		Flash::success('Event time created');

		return Response::json(true);
	}

	/**
	 * Update the details of an event time.
	 * @param \App\Http\Requests\GenericRequest $request
	 * @param \App\Event                        $event
	 * @return mixed
	 */
	private function updateEditTime(GenericRequest $request, Event $event)
	{
		// Get the event time
		$time = EventTime::find($request->get('id'));
		if(!$time || $time->event_id != $event->id) {
			return Response::json(['error' => 'Couldn\'t find the event time to change'], 404);
		}

		// Validate
		$this->validateEventTime($request);

		// Update
		$time->update([
			'name'  => $request->get('name'),
			'start' => Carbon::createFromFormat('d/m/Y H:i', $request->get('date') . ' ' . $request->get('start_time')),
			'end'   => Carbon::createFromFormat('d/m/Y H:i', $request->get('date') . ' ' . $request->get('end_time')),
		]);

		Flash::success('Event time updated');

		return Response::json(true);
	}

	/**
	 * Delete an event time.
	 * @param \App\Http\Requests\GenericRequest $request
	 * @param \App\Event                        $event
	 */
	private function updateDeleteTime(GenericRequest $request, Event $event)
	{
		// Get the event time
		$time = EventTime::find($request->get('id'));
		if(!$time || $time->event_id != $event->id) {
			return Response::json(['error' => 'Couldn\'t find the event time to change'], 404);
		} else {
			$time->delete();
			Flash::success('Event time deleted');

			return Response::json(true);
		}
	}

	/**
	 * Validate an event time form submission.
	 * @param \App\Http\Requests\GenericRequest $request
	 */
	private function validateEventTime(GenericRequest $request)
	{
		$this->validate($request, [
			'name'       => 'required',
			'date'       => 'required|date_format:d/m/Y',
			'start_time' => 'required|date_format:H:i',
			'end_time'   => 'required|date_format:H:i|after:start_time',
		], [
			'name.required'          => 'Please enter a title for the time',
			'date.required'          => 'Please enter the date',
			'date.date_format'       => 'Please enter a valid date',
			'start_time.required'    => 'Please enter the start time',
			'start_time.date_format' => 'Please enter a valid time',
			'end_time.required'      => 'Please enter the end time',
			'end_time.date_format'   => 'Please enter a valid time',
			'end_time.after'         => 'It cannot end before it\'s begun!',
		]);
	}
}
