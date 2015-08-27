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
			'name'        => Event::getValidationRule('name'),
			'em_id'       => Event::getValidationRule('em_id'),
			'type'        => Event::getValidationRule('type'),
			'description' => Event::getValidationRule('description'),
			'venue'       => Event::getValidationRule('venue'),
			'venue_type'  => Event::getValidationRule('venue_type'),
			'client_type' => Event::getValidationRule('client_type'),
			'date_start'  => 'required|date_format:d/m/Y',
			'date_end'    => 'required|date_format:d/m/Y|after:date_start',
		];
	}

	/** Get the validation messages.
	 * @return array
	 */
	public function messages()
	{
		return array_merge(
			Event::getValidationMessages('name'),
			Event::getValidationMessages('em_id'),
			Event::getValidationMessages('type'),
			Event::getValidationMessages('description'),
			Event::getValidationMessages('venue'),
			Event::getValidationMessages('venue_type'),
			Event::getValidationMessages('client_type'),
			[
				'date_start.required'    => 'Please enter when this event starts',
				'date_start.date_format' => 'Please enter a valid date',
				'date_end.required'      => 'Please enter when this event ends',
				'date_end.date_format'   => 'Please enter a valid date',
				'date_end.after'         => 'This must be after the start date',
			]
		);
	}
}
