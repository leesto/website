<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Menu\Items\Contents\Link;
use Menu\Menu;

class ViewServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 * @return void
	 */
	public function boot()
	{
		$this->composeFlash();
		$this->composeMainMenu();
		$this->composeSubMenus();
		$this->attachUserList();
	}

	/**
	 * Register the application services.
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Attach a composer to the flash view so that the view has access
	 * to the FA icons for each alert level.
	 */
	private function composeFlash()
	{
		// Attach the FA icons for flash messages
		View::composer('partials.flash.message', function ($view) {
			$view->with('flashIcons', [
				'success' => 'check',
				'info'    => 'info',
				'warning' => 'exclamation',
				'danger'  => 'remove',
			]);
		});
	}

	/**
	 * Build the main navigation bar.
	 */
	private function composeMainMenu()
	{
		View::composer('app', function ($view) {
			// Set up some permissions
			$user         = Auth::user();
			$isRegistered = !!$user;
			$isMember     = $isRegistered && $user->isMember();
			$isCommittee  = $isRegistered && $user->isCommittee();
			$isAssociate  = $isRegistered && $user->isAssociate();
			$isSU         = $isRegistered && $user->isSU();
			$isAdmin      = $isRegistered && $user->isAdmin();


			// Create the parent menu
			$menu = Menu::handler('mainNav');
			$menu->add(route('home'), 'Home');
			$menu->add(route('page.show', 'about'), 'About Us');
			$menu->add(route('committee.view'), 'The Committee');
			$menu->add('#', 'Galleries');
			$menu->add(route('members.dash'), 'Members\' Area', Menu::items('members'));
			if($isAdmin) {
				$menu->add('#', 'Committee', Menu::items('committee'));
			}
			$menu->add('#', 'Resources', Menu::items('resources'));
			$menu->add(route('contact.enquiries'), 'Enquiries');
			$menu->add(route('contact.book'), 'Book Us');

			// Build the members sub-menu
			if($isRegistered) {
				$members = $menu->find('members');

				if($isMember || $isAdmin) {
					$members->add('#', 'My Profile', Menu::items('members.profile'), [], ['class' => 'profile'])
					        ->add('#', 'Events Diary', Menu::items('members.events'), [], ['class' => 'events'])
					        ->add('#', 'The Membership')
					        ->add(route('quotes.index'), 'Quotes Board')
					        ->add('#', 'Equipment', Menu::items('members.equipment'), [], ['class' => 'equipment'])
					        ->add('#', 'Training', Menu::items('members.training'), [], ['class' => 'training'])
					        ->add(route('polls.index'), 'Polls')
					        ->raw('', null, ['class' => 'divider'])
					        ->add('#', 'Elections Home')
					        ->add('#', 'BTS Awards')
					        ->raw('', null, ['class' => 'divider']);
					if($isAdmin) {
						$members->add('#', 'View SU Area')
						        ->raw('', null, ['class' => 'divider']);
					}
					$members->add(route('contact.accident'), 'Report an Accident');

					// Build the profile sub-menu
					$menu->find('members.profile')
					     ->add('#', 'My training')
					     ->add('#', 'My events');

					// Build the events sub-menu
					$events = $menu->find('members.events');
					$events->add('#', 'My diary')
					       ->add('#', 'Event sign-up')
					       ->add('#', 'Submit event report');
					if($isAdmin) {
						$events->add('#', 'View booking requests');
					}

					// Build the equipment sub-menu
					$menu->find('members.equipment')
					     ->add('#', 'View repairs db')
					     ->add('#', 'Report broken kit');

					// Build the training sub-menu
					$training = $menu->find('members.training');
					$training->add('#', 'View skills')
					         ->add('#', 'Award skill');
					if($isAdmin) {
						$training->add('#', 'Skills log');
					}
				}
			}

			// Build the committee sub-menu
			if($isAdmin) {
				$menu->find('committee')
				     ->add(route('page.index'), 'Webpages', Menu::items('committee.webpages'), [], ['class' => 'admin-webpages'])
				     ->add(route('user.index'), 'Users', Menu::items('committee.users'), [], ['class' => 'admin-users']);


				$menu->find('committee.webpages')
				     ->add(route('page.index'), 'Webpage manager')
				     ->add(route('page.create'), 'Create a new page');

				$menu->find('committee.users')
				     ->add(route('user.index'), "User manager")
				     ->add(route('user.create'), "Create a user");
			}

			// Build the resources sub-menu
			$resources = $menu->find('resources');
			if($isMember || $isAdmin) {
				$resources->add('#', 'Event Reports')
				          ->add('#', 'Risk Assessments')
				          ->add('#', 'Meeting Minutes')
				          ->add('#', 'Meeting Agendas');
			}
			$resources->add('#', 'Safety Information')
			          ->add('#', 'Weather Forecast')
			          ->add(route('page.show', 'links'), 'Links');


			// Add the necessary classes
			$menu->addClass('nav navbar-nav')
			     ->getItemsByContentType(Link::class)
			     ->map(function ($item) {
				     if($item->hasChildren()) {
					     $item->addClass('dropdown');
					     $item->getChildren()->getAllItems()->map(function ($childItem) use ($item) {
						     if($childItem->isActive()) {
							     $item->addClass('active');
						     }
					     });
				     }
			     });
			$menu->getAllItemLists()
			     ->map(function ($itemList) {
				     if($itemList->hasChildren()) {
					     $itemList->addClass('dropdown-menu');
				     }
			     });


			$view->with('mainNav', $menu->render());
		});
	}

	/**
	 * Create all the necessary sub-menus
	 */
	private function composeSubMenus()
	{
		// Compose the contact sub-menu
		View::composer('contact.shared', function ($view) {
			$menu = Menu::handler('contactMenu');
			$menu->add(route('contact.enquiries'), 'General Enquiries')
			     ->add(route('contact.book'), 'Book Us')
			     ->add(route('contact.feedback'), 'Provide Feedback');

			// Add the necessary classes
			$menu->addClass('nav nav-tabs');

			$view->with('menu', $menu->render());
		});
	}

	/**
	 * Attach a list of user accounts
	 */
	private function attachUserList()
	{
		// Get the users
		$users        = User::orderBy('username', 'ASC')->get();
		$users_select = [];
		foreach($users as $user) {
			$users_select[$user->id] = sprintf('%s (%s)', $user->name, $user->username);
		}

		// Attach to each view
		View::composer([
			'committee.view',
			'pages.form',
		], function ($view) use ($users_select) {
			$view->with('users', $users_select);
		});
	}
}