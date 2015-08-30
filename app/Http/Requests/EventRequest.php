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
		return Event::getValidationRules('name', 'em_id', 'type', 'description', 'venue', 'venue_type', 'client_type', 'date_start', 'date_end');
	}

	/** Get the validation messages.
	 * @return array
	 */
	public function messages()
	{
		return Event::getValidationMessages('name', 'em_id', 'type', 'description', 'venue', 'venue_type', 'client_type', 'date_start', 'date_end');
	}
}
