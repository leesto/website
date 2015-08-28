<?php

namespace App;

class TrainingSkill extends Model
{
	/**
	 * The attributes fillable by mass assignment.
	 * @var array
	 */
    protected $fillable = [
	    'name',
	    'description',
	    'requirements_level1',
	    'requirements_level2',
	    'requirements_level3',
    ];

	/**
	 * Define the relationship to the category.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function category()
	{
		return $this->belongsTo('App\TrainingCategory', 'category_id');
	}
}
