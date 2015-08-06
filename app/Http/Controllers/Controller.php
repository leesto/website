<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
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
			App::abort(Response::HTTP_TEMPORARY_REDIRECT, '', ['Location' => route(Route::current()->getName(), ['page' => 1])]);
		}
	}

	/**
	 * Require that the request is send by AJAX.
	 * @param \Illuminate\Http\Request $request
	 */
	protected function requireAjax(Request $request)
	{
		if(!$request->ajax()) {
			App::abort(Response::HTTP_NOT_FOUND);
		}
	}
}
