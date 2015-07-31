<?php

namespace App;

use Zizaco\Entrust\EntrustPermission as Model;

class Permission extends Model
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
