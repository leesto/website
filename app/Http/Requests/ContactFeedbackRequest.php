<?php

namespace App\Http\Requests;

class ContactFeedbackRequest extends Request
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
			'event'                => 'required|string',
			'feedback'             => 'required|string',
			'g-recaptcha-response' => 'recaptcha',
		];
	}

	/**
	 * Define the custom messages
	 * @return array
	 */
	public function messages()
	{
		return [
			'event.required'    => 'Please enter the event name',
			'event.string'      => 'Please enter the event name',
			'feedback.required' => 'Please enter your feedback',
			'feedback.string'   => 'Please enter your feedback',
		];
	}
}
