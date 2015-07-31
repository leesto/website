<?php

namespace App\Http\Requests;

class PollRequest extends Request
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
			'question'     => 'required',
			'option'       => 'array|each:required',
			'show_results' => 'boolean',
		];
	}

	/**
	 * Define the custom messages.
	 * @return array
	 */
	public function messages()
	{
		return [
			'question.required'    => 'Please enter the poll\'s question',
			'option.each.required' => 'Please enter the answer text',
		];
	}
}
