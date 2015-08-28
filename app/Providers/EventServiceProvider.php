<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 * @var array
	 */
	protected $listen = [];

	/**
	 * Register any other events for your application.
	 * @param  \Illuminate\Contracts\Events\Dispatcher $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);

		// Listen to all emails to prepend the
		// subject with '[Backstage Website]'
		Event::listen('mailer.sending', function ($message) {
			$message->setSubject('[Backstage Website] ' . $message->getSubject());
		});
	}
}
