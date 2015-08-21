<?php

namespace App\Http\Requests;

class ContactEnquiryRequest extends Request
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
			'name'                 => 'required|regex:/[a-zA-z]+\s[a-zA-z]+/',
			'email'                => 'required|email',
			'phone'                => 'phone',
			'message'              => 'required|string',
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
			'name.required'    => 'Please enter your name',
			'name.regex'       => 'Please enter both your forename and surname, using letters only',
			'email.required'   => 'Please enter your email address',
			'email.email'      => 'Please enter a valid email address',
			'phone.phone'      => 'Please enter a valid phone number',
			'message.required' => 'Please enter your enquiry',
			'message.string'   => 'Please enter your enquiry',
		];
	}
}
