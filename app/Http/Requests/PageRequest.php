<?php

namespace App\Http\Requests;

use App;
use Auth;

class PageRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::check() && Auth::user()->isAdmin();
	}

	/**
	 * Get the validation rules that apply to the request.
	 * @return array
	 */
	public function rules()
	{
		return [
			'title'     => 'required',
			'slug'      => 'required|' . ($this->route()->getName() == 'page.update' ? ('unique:pages,slug,' . $this->get('slug') . ',slug') : 'unique:pages') . '|alpha_dash',
			'content'   => 'required',
			'published' => 'required|boolean',
			'user_id'   => 'required|exists:users,id',
		];
	}

	/**
	 * Define the custom error messages.
	 * @return array
	 */
	public function messages()
	{
		return [
			"title.required"     => "Please enter the page's title",
			"slug.required"      => "Please enter the page's slug",
			"slug.unique"        => "That slug is already used by another page",
			"slug.alpha_dash"    => "Please use letters, numbers and dashes only for the slug",
			"content.required"   => "Please enter the page's content",
			"published.required" => "Please select the page's published state",
			"published.boolean"  => "Please select 'Yes' or 'No'",
			"user_id.required"   => "Please select the page's author",
			"user_id.exists"     => "Please select the page's author",
		];
	}
}
