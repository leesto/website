<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Szykra\Notifications\Flash;

class MembersController extends Controller
{
	/**
	 * Add the permissions requirements for each route.
	 */
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

	/**
	 * Display the dashboard for BTS members.
	 * @return Response
	 */
	public function dash()
	{
		// If the user is an SU officer then
		// redirect to the SU dashboard
		if($this->user->isSU()) {
			return redirect(route('su.dash'));
		}

		// TODO: Make
		Flash::info('Not yet implemented', "I've not got around to making the member dashboard yet ... sorry");

		return redirect(route('home'));
	}

	/**
	 * Display the dashboard for SU officers.
	 * @return Response
	 */
	public function dashSU()
	{
		// The SU dashboard is restricted to SU
		// officers and BTS committee members
		if(!$this->user->isAdmin() || !$this->user->isSU()) {
			return redirect(route('members.dash'));
		}

		// TODO: Make
		Flash::info('Not yet implemented', "I've not got around to making the SU dashboard yet ... sorry");

		return redirect(route('home'));
	}

	/**
	 * Display a member's profile page.
	 * @param $username
	 * @return Response
	 */
	public function profile($username)
	{
		$user = User::where('username', $username)->active()->member()->firstOrFail();

		return View::make('members.profile')->with('user', $user);
	}

	/**
	 * Display the current user's profile page.
	 * @return Response
	 */
	public function getMyProfile()
	{
		// TODO: Make
		Flash::info('Not yet implemented', "I've not got around to making your profile page yet ... sorry");

		return redirect(route('home'));
	}

	/**
	 * Process changes to the user's profile using AJAX.§
	 * @param Request $request
	 * @return Response
	 */
	public function postMyProfile(Request $request)
	{
		// Require ajax
		$this->requireAjax($request);

		App::abort(422);
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
