<?php
namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;

class UserProvider extends EloquentUserProvider
{
	/**
	 * Override the Eloquent method to check the account is enabled.
	 * @param  mixed  $identifier
	 * @param  string $token
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveByToken($identifier, $token)
	{
		$user = parent::retrieveByToken($identifier, $token);

		return $user
			?
			($user->status ? $user : null)
			:
			null;
	}

	/**
	 * Override the Eloquent method to check the account is enabled.
	 * @param array $credentials
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveByCredentials(array $credentials)
	{
		// Add the status requirement to credentials
		return parent::retrieveByCredentials($credentials + [
				'status' => 1,
			]);
	}

	/**
	 * Override the Eloquent method to check the account is enabled.
	 * @param mixed $id
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveById($id)
	{
		$user = parent::retrieveById($id);

		return $user
			?
			($user->status ? $user : null)
			:
			null;
	}
}