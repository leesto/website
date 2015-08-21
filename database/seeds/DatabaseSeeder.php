<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call(RolesSeeder::class);
		$this->call(UserTableSeeder::class);
		$this->call(PageTableSeeder::class);
		$this->call(CommitteeSeeder::class);

		Model::reguard();
	}
}
