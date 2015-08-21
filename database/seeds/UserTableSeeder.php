<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
		// Add the user
		$user_su2bc = User::create([
			"username" => "su2bc",
			"email"    => "su2bc@bath.ac.uk",
			"password" => '$2y$10$bHOXZBkEdvb/swW2d2dfAe2x5RlkkjtU1OCom/d1.mCxIYEnnWZBy',
			"forename" => "Super",
			"surname"  => "Admin",
			"status"   => true,
		], false);

		// Add the permissions
		$user_su2bc->roles()->save(Role::where('name', 'super_admin')->first());
	}
}
