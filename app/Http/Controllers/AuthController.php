<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Szykra\Notifications\Flash;

class AuthController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesUsers, ThrottlesLogins, ResetsPasswords {
		AuthenticatesUsers::redirectPath insteadof ResetsPasswords;
	}

	/**
	 * The name of the 'username' attribute (for authentication).
	 * @var string
	 */
	protected $username = 'username';

	/**
	 * The path of the login form.
	 * @var string
	 */
	protected $loginPath = 'login';

	/**
	 * The path to redirect to on logout (login is overridden using authenticated()).
	 * @var string
	 */
	protected $redirectPath = '';

	/**
	 * Create a new authentication controller instance.
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);
		parent::__construct();
	}

	/**
	 * Flash a message on successful authenticated.
	 * @return \Illuminate\Http\Response
	 */
	public function authenticated()
	{
		// Flash message and redirect to the dashboard
		Flash::success('Success', 'You were logged in successfully.');

		return redirect()->intended('members');
	}

	/**
	 * Override the default method to provide a flash message on success.
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function postReset(Request $request)
	{
		$this->validate($request, [
			'token'    => 'required',
			'email'    => 'required|email',
			'password' => 'required|confirmed',
		]);

		$credentials = $request->only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function ($user, $password) {
			$this->resetPassword($user, $password);
		});

		switch($response) {
			case Password::PASSWORD_RESET:
				Flash::success('Success', 'Your password was changed successfully and you are now logged in.');

				return redirect($this->redirectPath());

			default:
				return redirect()->back()
				                 ->withInput($request->only('email'))
				                 ->withErrors(['summary' => trans($response)]);
		}
	}
}
