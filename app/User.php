<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Szykra\Notifications\Flash;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
	use Authenticatable, CanResetPassword, EntrustUserTrait;

	/**
	 * User account types
	 */
	const MEMBER    = 1;
	const COMMITTEE = 2;
	const ASSOCIATE = 3;
	const SU        = 4;
	const ADMIN     = 5;

	/**
	 * Define the list of user account types for the create form.
	 * @var array
	 */
	public static $CreateAccountTypes = [
		self::MEMBER    => 'Standard Member',
		self::COMMITTEE => 'Committee',
		self::ASSOCIATE => 'Associate',
		self::SU        => 'SU Officer',
	];

	/**
	 * Define the list of user account types for the edit form.
	 * @var array
	 */
	public static $EditAccountTypes = [
		self::MEMBER    => 'Standard Member',
		self::COMMITTEE => 'Committee',
		self::ASSOCIATE => 'Associate',
		self::SU        => 'SU Officer',
		self::ADMIN     => 'Web Admin (not committee)',
	];

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
		'forename',
		'surname',
		'email',
		'password',
		'status',
		'phone',
		'address',
		'tool_colours',
		'dob',
		'show_email',
		'show_phone',
		'show_address',
		'show_age',
		'type',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * Define the attributes which should be provided as Carbon dates.
	 * @var array
	 */
	protected $dates = [
		'dob',
	];

	protected $casts = [
		'show_email'   => 'boolean',
		'show_phone'   => 'boolean',
		'show_address' => 'boolean',
		'show_age'     => 'boolean',
	];

	/**
	 * Override the default ::create method to automatically assign some attributes.
	 * This also automatically sets up the role and sends the new user an email.
	 * @param array $attributes
	 * @param bool  $sendEmail
	 * @return static
	 */
	public static function create(array $attributes = [], $sendEmail = true)
	{
		// Set up the default parameters
		$newAttributes             = [];
		$newAttributes['username'] = $attributes['username'];
		$newAttributes['email']    = $newAttributes['username'] . '@bath.ac.uk';
		$newAttributes['name']     = $attributes['name'];
		$password                  = str_random(15);
		$newAttributes['password'] = bcrypt($password);
		$newAttributes['status']   = true;

		// Create the user
		$user = parent::create($newAttributes);

		if($user) {
			// Send an email to the new user
			// TODO: re-enable
			//Mail::queue('emails.users.create', [
			//	'name'     => $user->forename,
			//	'password' => $password,
			//], function ($message) use ($user) {
			//	$message->subject('Your new Backstage account')
			//	        ->to($user->email, $user->name);
			//});
		}

		return $user;
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

	public function getProfileValidationRules()
	{
		return [
			'name'         => 'required|name',
			'username'     => 'required|regex:/[a-z0-9]+/i|unique:users,username,' . $this->id,
			'email'        => 'required|email|unique:users,email,' . $this->id,
			'phone'        => 'phone',
			'dob'          => 'date_format:Y-m-d',
			'show_email'   => 'boolean',
			'show_phone'   => 'boolean',
			'show_address' => 'boolean',
			'show_age'     => 'boolean',
		];
	}

	public function getProfileValidationMessages()
	{
		return [

		];
	}

	/**
	 * Test if the user is a BTS member.
	 * @return bool
	 */
	public function isMember()
	{
		return $this->hasRole(['member', 'committee', 'associate'], false);
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
	 * Get the user's full name.
	 * @return string
	 */
	public function getNameAttribute()
	{
		return $this->forename . ' ' . $this->surname;
	}

	/**
	 * Allow setting the forename and surname as a "name".
	 * @param $value
	 * @return $this
	 */
	public function setNameAttribute($value)
	{
		list($this->forename, $this->surname) = explode(' ', $value);

		return $this;
	}

	/**
	 * Get the user's forename.
	 * @return string
	 */
	public function getForenameAttribute()
	{
		return ucfirst($this->attributes['forename']);
	}

	/**
	 * Get the user's surname.
	 * @return string
	 */
	public function getSurnameAttribute()
	{
		return ucfirst($this->attributes['surname']);
	}

	/**
	 * Get the user's role as an "account type".
	 * @return int
	 */
	public function getTypeAttribute()
	{
		if($this->isAdmin() && !$this->isMember()) {
			return self::ADMIN;
		} else if($this->isSU()) {
			return self::SU;
		} else if($this->isAssociate()) {
			return self::ASSOCIATE;
		} else if($this->isCommittee()) {
			return self::COMMITTEE;
		} else {
			return self::MEMBER;
		}
	}

	/**
	 * Set the user's role from an "account type".
	 * @param $value
	 */
	public function setTypeAttribute($value)
	{
		if($value == self::ADMIN) {
			$this->roles()->sync([Role::where('name', 'super_admin')->first()->id]);
		} else if($value == self::SU) {
			$this->roles()->sync([Role::where('name', 'su')->first()->id]);
		} else if($value == self::ASSOCIATE) {
			$this->makeAssociate();
		} else if($value == self::COMMITTEE) {
			$this->makeCommittee();
		} else {
			$this->roles()->sync([Role::where('name', 'member')->first()->id]);
		}
	}

	/**
	 * Make the user account archived
	 * @return bool
	 */
	public function archive()
	{
		// Check the selected user isn't the current user
		if(Auth::check() && $this->id == Auth::user()->id) {
			Flash::warning('You cannot archive your own account');

			return false;
		}

		// Change status
		return $this->update(['status' => false]);
	}

	/**
	 * Make the user a committee member.
	 * @return bool
	 */
	public function makeCommittee()
	{
		if($this->id == Auth::user()->id) {
			Flash::warning('You cannot make yourself a committee member');

			return false;
		}


		$this->roles()->sync([Role::where('name', 'committee')->first()->id]);

		return true;
	}

	/**
	 * Make the user an associate
	 * @return bool
	 */
	public function makeAssociate()
	{
		if($this->id == Auth::user()->id) {
			Flash::warning('You cannot make yourself an associate');

			return false;
		}

		$this->roles()->sync([Role::where('name', 'associate')->first()->id]);

		return true;
	}

	/**
	 * Change the user's avatar and resize to 500x500.
	 * @param UploadedFile $image
	 * @return $this
	 */
	public function setAvatar(UploadedFile $image)
	{
		// Convert, resize and save
		Image::make($image)
		     ->fit(500, 500)
		     ->save($this->getAvatarPath(true));

		return $this;
	}

	/**
	 * Get the URL of the user's profile picture to be used in img tags.
	 * @return string
	 */
	public function getAvatarUrl()
	{
		return $this->getAvatarPath(false, true);
	}

	/**
	 * Check if the user has a custom avatar image.
	 * @return bool
	 */
	public function hasAvatar()
	{
		return file_exists($this->getAvatarPath(true, false));
	}

	/**
	 * Get the URL or path of the user's avatar.
	 * @param bool $absolute
	 * @param bool $checkExists
	 * @return string
	 */
	public function getAvatarPath($absolute = false, $checkExists = false)
	{
		$basePath = '/images/profiles/';
		$imgPath  = $basePath . $this->username . '.jpg';
		$path     = !$checkExists || $this->hasAvatar() ? $imgPath : ($basePath . 'blank.jpg');

		return ($absolute ? base_path('public') : '') . $path;

	}
}
