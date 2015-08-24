<?php

namespace App;

use Zizaco\Entrust\EntrustPermission as PermissionModel;

class Permission extends PermissionModel
{
	/**
	 * Define the attributes that are mass-assignable.
	 * @var array
	 */
	protected $fillable = [
		"name",
		"display_name",
		"description",
	];
}
