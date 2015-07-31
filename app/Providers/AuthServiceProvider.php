<?php

namespace App\Providers;

use App\Auth\UserProvider;
use Auth;
use Illuminate\Auth\Guard;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 * @return void
	 */
	public function boot()
	{
		// Tell Laravel to use the custom UserProvider
		Auth::extend('eloquent', function ($app) {
			return new Guard(new UserProvider($app['hash'], $app['config']['auth.model']), $app['session.store']);
		});
	}

	/**
	 * Register the application services.
	 * @return void
	 */
	public function register()
	{
		//
	}
}
