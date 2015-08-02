<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;

abstract class Controller extends BaseController
{
	use DispatchesJobs, ValidatesRequests;

	/**
	 * Store the current user object for all controllers.
	 * @var User
	 */
	protected $user;

	/**
	 * Store the user.
	 */
	public function __construct()
	{
		$this->user = Auth::user() ?: null;
	}

	/**
	 * Redirect to page 1 if the paginator is empty.
	 * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	protected function checkPagination(LengthAwarePaginator $paginator)
	{
		if($paginator->count() == 0 && !is_null(Input::get('page')) && (int) Input::get('page') != 1) {
			return redirect(route(Route::current()->getName(), ['page' => 1]));
		}
	}
}
