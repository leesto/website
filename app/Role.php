<?php

namespace App;

use Zizaco\Entrust\EntrustRole as RoleModel;

class Role extends RoleModel
{
	/**
	 * Set the attributes that are mass-assignable.
	 * @var array
	 */
	protected $fillable = [
		'name',
		'display_name',
		'description',
	];
}
