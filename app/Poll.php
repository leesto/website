<?php

namespace App;

class Poll extends Model
{
	/**
	 * The attributes fillable by mass-assignment.
	 * @var array
	 */
	protected $fillable = [
		'question',
		'description',
		'show_results',
		'user_id',
	];
	/**
	 * Store the total number of votes.
	 * @var int
	 */
	private $totalVotes;

	/**
	 * Test if the user can view the poll's results.
	 * @param \App\User $user
	 * @return bool
	 */
	public function canViewResults(User $user)
	{
		return $this->show_results || $this->voted($user);
	}

	/**
	 * Check if a user has voted in this poll.
	 * @param \App\User $user
	 * @return bool
	 */
	public function voted(User $user)
	{
		foreach($this->options as $option) {
			if($option->voted($user)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Calculate the total number of votes.
	 * @return int
	 */
	public function totalVotes()
	{
		if(is_null($this->totalVotes)) {
			$this->totalVotes = 0;
			foreach($this->options as $option) {
				$this->totalVotes += $option->numVotes();
			}
		}

		return $this->totalVotes;
	}

	/**
	 * Create the foreign link with the options.
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function options()
	{
		return $this->hasMany('App\PollOption');
	}

	/**
	 * Define the relationship with the user that created the poll.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function creator()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
}
