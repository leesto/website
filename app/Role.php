<?php

namespace App;

use Zizaco\Entrust\EntrustRole as Model;

class Role extends Model
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
