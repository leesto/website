<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
		// Insert the default roles
		$role_member    = Role::create([
			"name"         => "member",
			"display_name" => "BTS Member",
		]);
		$role_committee = Role::create([
			"name"         => "committee",
			"display_name" => "Committee Member",
		]);
		$role_associate = Role::create([
			"name"         => "associate",
			"display_name" => "Associate",
		]);
		$role_su        = Role::create([
			"name"         => "su",
			"display_name" => "SU Officer",
		]);
		$role_super     = Role::create([
			"name"         => "super_admin",
			"display_name" => "Super Admin",
		]);

		// Insert the default permissions
		$permission_member = Permission::create([
			"name"         => "member",
			"display_name" => "BTS Member",
		]);
		$permission_admin  = Permission::create([
			"name"         => "admin",
			"display_name" => "Administrator",
		]);

		// Link roles and permissions
		$permission_member->roles()->saveMany([
			$role_member,
			$role_committee,
			$role_associate,
		]);
		$permission_admin->roles()->saveMany([
			$role_committee,
			$role_super,
		]);
	}
}
