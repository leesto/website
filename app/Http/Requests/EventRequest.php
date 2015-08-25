<?php

namespace App\Http\Requests;

use App\Event;

class EventRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 * @return array
	 */
	public function rules()
	{
		return [
			'name'        => 'required',
			'em_id'       => 'exists:users,id',
			'type'        => 'required|in:' . implode(',', array_keys(Event::$Types)),
			'description' => 'required',
			'venue'       => 'required',
			'venue_type'  => 'required|in:' . implode(',', array_keys(Event::$VenueTypes)),
			'client_type' => 'required|in:' . implode(',', array_keys(Event::$Clients)),
			'date_start'  => 'required|date_format:d/m/Y',
			'date_end'    => 'required|date_format:d/m/Y|after:date_start',
		];
	}

	/** Get the validation messages.
	 * @return array
	 */
	public function messages()
	{
		return [
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
		];
	}
}
