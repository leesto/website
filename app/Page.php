<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Page extends Model
{
	/**
	 * The attributes fillable by mass assignment.
	 * @var array
	 */
	protected $fillable = [
		'title',
		'slug',
		'content',
		'published',
		'user_id',
	];

	/**
	 * Allow retrieving a page by its slug.
	 * @param string $slug
	 * @return Page|null
	 */
	public static function findBySlug($slug)
	{
		return static::where(['slug' => $slug])->get()->first();
	}

	/**
	 * Method automatically throw a 404 if the page isn't found, or isn't published.
	 * @param string $slug
	 * @param bool   $requirePublished
	 * @return \App\Page|null
	 * @throws NotFoundHttpException
	 */
	public static function findBySlugOrFail($slug, $requirePublished = true)
	{
		$page = static::findBySlug($slug);

		if(!$page || (!$page->published && $requirePublished)) {
			throw new NotFoundHttpException;
		}

		return $page;
	}

	/**
	 * Create the foreign key link.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function author()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

	/**
	 * The published() scope that allows filtering pages by whether they're published or not.
	 * @param $query
	 */
	public function scopePublished($query)
	{
		$query->where('published', 1);
	}
}
