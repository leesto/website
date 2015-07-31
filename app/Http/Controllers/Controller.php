<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

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
		$this->user = Auth::user() ?: new User;
	}
}
