<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventTime;
use App\Http\Requests;
use App\Http\Requests\EventRequest;
use App\Http\Requests\EventTimeRequest;
use App\Http\Requests\GenericRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
				'update',
				'toggleVolunteer',
			],
		]);

		parent::__construct();
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
	 * View a list of events.
	 * @return mixed
	 */
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
	 * Add an event to the diary.
	 * @param \App\Http\Requests\EventRequest $request
	 * @return \Illuminate\Support\Facades\Response
	 */
	public function store(EventRequest $request)
	{
		// Create the event
		$event = Event::create($request->stripped('name', 'venue', 'description', 'type', 'client_type', 'venue_type') + [
				'em_id'              => $request->get('em_id') ?: null,
				'description_public' => $request->get('desc_public') ? $request->stripped('description') : '',
				'crew_list_status'   => 1,
				'paperwork'          => [
					'risk_assessment'  => false,
					'insurance'        => false,
					'finance_em'       => false,
					'finance_treas'    => false,
					'event_report'     => false,
					'committee_report' => false,
				],
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
		if(!$this->user->isAdmin() && !$event->isEM($this->user, false)) {
			return Response::json(['error' => 'You need to be the EM or an admin to do that'], 403);
		}

		switch($action) {
			case 'add-time':
				return $this->update_AddTime($request, $event);
			case 'update-time':
				return $this->update_EditTime($request, $event);
			case 'delete-time':
				return $this->update_DeleteTime($request, $event);
			case 'add-crew':
				return $this->update_AddCrew($request, $event);
			case 'update-crew':
				return $this->update_EditCrew($request, $event);
			case 'delete-crew':
				return $this->update_DeleteCrew($request, $event);
			default:
				return Response::json(['error' => 'Unknown action'], 404);
		}
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

		// Get a list of users not signed up
		$users = User::notCrewingEvent($event)->member()->nameOrder()->getSelect();

		return View::make('events.view')->with([
			'event'    => $event,
			'user'     => $this->user,
			'users'    => $users,
			'isMember' => $this->user->isMember(),
			'isAdmin'  => $this->user->isAdmin(),
			'isEM'     => $event->isEM($this->user),
			'canEdit'  => $this->user->isAdmin() || $event->isEM($this->user, false),
		]);
	}

	/**
	 * @param                                   $id
	 * @param \App\Http\Requests\GenericRequest $request
	 * @return \Illuminate\Support\Facades\Response
	 */
	public function toggleVolunteer($id, GenericRequest $request)
	{
		// Require ajax
		$this->requireAjax($request);

		// Get the event
		$event = Event::findOrFail($id);

		// Test if they are the EM
		if($event->isEM($this->user)) {
			return Response(['error' => 'You can\'t unvolunteer as you are the EM!'], 422);
		}

		// Test if the user is already crew
		$crew = $event->crew->where('user_id', $this->user->id)->first();
		if($crew) {
			$crew->delete();
			Flash::success('You have unvolunteered');
		} else {
			$event->crew()->create([
				'name'    => null,
				'user_id' => $this->user->id,
			]);
			Flash::success('You have volunteered');

			// TODO: email
		}

		return Response::json(true);
	}

	/**
	 * Add a new event time.
	 * @param \App\Http\Requests\GenericRequest $request
	 * @param \App\Event                        $event
	 * @return \Illuminate\Support\Facades\Response
	 */
	private function update_AddTime(GenericRequest $request, Event $event)
	{
		// Validate
		$this->validateEventTime($request);

		// Create the time
		$event->times()->create([
			'name'  => $request->get('name'),
			'start' => Carbon::createFromFormat('d/m/Y H:i', $request->get('date') . ' ' . $request->get('start_time')),
			'end'   => Carbon::createFromFormat('d/m/Y H:i', $request->get('date') . ' ' . $request->get('end_time')),
		]);

		Flash::success('Event time created');

		return Response::json(true);
	}

	/**
	 * Update the details of an event time.
	 * @param \App\Http\Requests\GenericRequest $request
	 * @param \App\Event                        $event
	 * @return Reponse
	 */
	private function update_EditTime(GenericRequest $request, Event $event)
	{
		// Get the event time
		$time = $event->times()->find($request->get('id'));
		if(!$time) {
			return Response::json(['error' => 'Couldn\'t find the event time'], 404);
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
	 * @return Response
	 */
	private function update_DeleteTime(GenericRequest $request, Event $event)
	{
		// Get the event time
		$time = $event->times()->find($request->get('id'));
		if(!$time) {
			return Response::json(['error' => 'Couldn\'t find the event time'], 404);
		} else {
			$time->delete();
			Flash::success('Event time deleted');

			return Response::json(true);
		}
	}

	/**
	 * Add a user to the crew.
	 * @param \App\Http\Requests\GenericRequest $request
	 * @param \App\Event                        $event
	 */
	private function update_AddCrew(GenericRequest $request, Event $event)
	{
		// Validate
		$this->validateCrew($request);

		// Check if the user is already crewing
		$user = User::find($request->get('user_id'));
		if($event->isCrew($user)) {
			return Response::json(['error' => 'That user is already on the crew'], 422);
		}

		// Create
		$event->crew()->create([
			'user_id' => $user->id,
			'name'    => $request->get('core') ? $request->get('name') : null,
			'em'      => $request->get('core') ? $request->has('em') : false,
		]);

		// TODO: Send email

		Flash::success('Crew role created');

		return Response::json(true);
	}

	/**
	 * Update a user's crew role.
	 * @param \App\Http\Requests\GenericRequest $request
	 * @param \App\Event                        $event
	 */
	private function update_EditCrew(GenericRequest $request, Event $event)
	{
		// Validate
		$this->validateCrew($request);

		// Get the event crew
		$crew = $event->crew()->find($request->get('id'));
		if(!$crew) {
			return Response::json(['error' => 'Couldn\'t find the crew entry:' . $request->get('id')], 404);
		}

		// Update
		$crew->update([
			'name' => $request->get('core') ? $request->get('name') : null,
			'em'   => $request->get('core') ? $request->has('em') : false,
		]);
		Flash::success('Crew role updated');

		return Response::json(true);
	}

	/**
	 * Delete a crew role.
	 * @param \App\Http\Requests\GenericRequest $request
	 * @param \App\Event                        $event
	 * @return mixed
	 */
	private function update_DeleteCrew(GenericRequest $request, Event $event)
	{
		// Get the event crew
		$crew = $event->crew()->find($request->get('id'));
		if(!$crew) {
			return Response::json(['error' => 'Couldn\'t find the crew entry:' . $request->get('id')], 404);
		}

		// Delete
		$crew->delete();
		Flash::success('Crew role deleted');

		return Response::json(true);
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

	/**
	 * Validate a event crew form submission.
	 * @param \App\Http\Requests\GenericRequest $request
	 */
	private function validateCrew(GenericRequest $request)
	{
		$this->validate($request, [
			'user_id' => 'required|exists:users,id',
			'name'    => 'required_if:core,1',
		], [
			'user_id.required' => 'Please select a member',
			'user_id.exists'   => 'Please select a member',
			'name.required_if' => 'Please enter a role title',
		]);
	}
}