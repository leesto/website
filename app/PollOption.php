<?php

namespace App;

class PollOption extends Model
{
	/**
	 * No timestamps.
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The attributes fillable by mass-assignment.
	 * @var array
	 */
	protected $fillable = [
		'number',
		'text',
	];

	/**
	 * Store the number of votes for this option.
	 * @var int
	 */
	private $numVotes;

	/**
	 * Check if a user has voted for this option.
	 * @param \App\User $user
	 * @return bool
	 */
	public function voted(User $user)
	{
		foreach($this->votes as $vote) {
			if($vote->user->id == $user->id) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Calculate the number of votes cast for this option.
	 * @return int
	 */
	public function numVotes()
	{
		if(is_null($this->numVotes)) {
			$this->numVotes = $this->votes->count();
		}
		return $this->numVotes;
	}

	public function percentage()
	{
		$totalVotes = $this->poll->totalVotes();
		return $totalVotes == 0 ? 0 : ($this->numVotes() / $totalVotes * 100);
	}

	/**
	 * Create the foreign link with the poll.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function poll()
	{
		return $this->belongsTo('App\Poll');
	}

	/**
	 * Define the relationship with the votes.
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function votes()
	{
		return $this->hasMany('App\PollVote', 'option_id');
	}
}
