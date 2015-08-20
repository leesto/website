<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class MembersController extends Controller
{
	public function __construct()
	{
		// Require authentication
		$this->middleware('auth.permission:member', [
			'except' => [
				'dash',
			],
		]);

		parent::__construct();
	}

	public function dash()
	{

	}

	/**
	 * @param $username
	 * @return Response
	 */
	public function profile($username)
	{
		$user = User::where('username', $username)->active()->member()->firstOrFail();

		return View::make('members.profile')->with('user', $user);
	}

	public function getMyProfile()
	{

	}

	public function postMyProfile()
	{

	}

	/**
	 * View the BTS membership list.
	 * @return Response
	 */
	public function membership()
	{
		// Get all of the active members
		$members = User::active()
		               ->member()
		               ->orderBy('surname', 'ASC')
		               ->orderBy('forename', 'ASC')
		               ->get();

		return View::make('members.membership')->with('members', $members);
	}
}
