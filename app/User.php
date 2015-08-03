<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
	use Authenticatable, CanResetPassword, EntrustUserTrait;

	/**
	 * The database table used by the model.
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = [
		'username',
		'name',
		'email',
		'password',
		'status',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	public static function forename($name)
	{
		list($forename) = explode(' ', $name);

		return ucfirst($forename);
	}

	/**
	 * Define the pages foreign key link.
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function pages()
	{
		return $this->hasMany('App\Page');
	}

	/**
	 * Define the quotes foreign key link
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function quotes()
	{
		return $this->hasMany('App\Quote', 'added_by');
	}

	/**
	 * Test if the user is a BTS member.
	 * @return bool
	 */
	public function isMember()
	{
		return $this->hasRole(['member', 'commmittee', 'associate'], false);
	}

	/**
	 * Test if the user is a committee member.
	 * @return bool
	 */
	public function isCommittee()
	{
		return $this->hasRole('committee');
	}

	/**
	 * Test if the user is an associate.
	 * @return bool
	 */
	public function isAssociate()
	{
		return $this->hasRole('associate');
	}

	/**
	 * Test if the user is an SU officer.
	 * @return bool
	 */
	public function isSU()
	{
		return $this->hasRole('su');
	}

	/**
	 * Test if the user is an admin.
	 * @return bool
	 */
	public function isAdmin()
	{
		return $this->isCommittee() || $this->hasRole('super_admin');
	}

	/**
	 * @return string
	 */
	public function getForename()
	{
		return self::forename($this->name);
	}

	public function getAvatarUrl()
	{
		return '/images/profiles/blank.png';
	}
}
