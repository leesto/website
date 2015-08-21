<?php

use App\CommitteeRole;
use Illuminate\Database\Seeder;

class CommitteeSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
		CommitteeRole::create([
			'name'        => 'Chair',
			'description' => '[name] is the face of the society within the union. He looks after the society as a whole and can be found helping out in lots of areas.',
			'email'       => 'chair@bts-crew.com',
			'user_id'     => null,
			'order'       => 1,
		]);
		CommitteeRole::create([
			'name'        => 'Production Manager',
			'description' => '[name] is in charge of events, from accepting booking requests to finding crew. If you want to get involved with an event he\'s the one to talk to .',
			'email'       => 'pm@bts-crew.com',
			'user_id'     => null,
			'order'       => 2,
		]);
		CommitteeRole::create([
			'name'        => 'Treasurer',
			'description' => '[name] watches carefully over our finances. Whenever we buy or hire anything there are forms to fill in and [name] is the one to chat to.',
			'email'       => 'treas@bts-crew.com',
			'user_id'     => null,
			'order'       => 3,
		]);
		CommitteeRole::create([
			'name'        => 'Training and Safety Officer',
			'description' => '[name] looks after the required safety paperwork and policies, along with making sure everyone gets training in the areas they are interested in.',
			'email'       => 'safety@bts-crew.com',
			'user_id'     => null,
			'order'       => 4,
		]);
		CommitteeRole::create([
			'name'        => 'Equipment Officer',
			'description' => '[name] is responsible for making sure all of our kit is in working order and that it is properly used and stored.',
			'email'       => 'equip@bts-crew.com',
			'user_id'     => null,
			'order'       => 5,
		]);
		CommitteeRole::create([
			'name'        => 'Secretary',
			'description' => '[name] is in charge of the admin. He takes minutes at meetings, books rooms, looks after the office and maintains the mailing lists.',
			'email'       => 'sec@bts-crew.com',
			'user_id'     => null,
			'order'       => 6,
		]);
		CommitteeRole::create([
			'name'        => 'Social Secretary',
			'description' => '[name] is here to ensure we have an excellent social life! He\'s the one to talk to if you have any ideas for socials .',
			'email'       => 'socials@bts-crew.com',
			'user_id'     => null,
			'order'       => 7,
		]);
	}
}
