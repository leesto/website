<?php

namespace App;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;

abstract class Model extends EloquentModel
{
	/**
	 * A custom paginate method for when using the distinct() method. This fixes the incorrect 'total' reported by the default paginate.
	 * @param        $query
	 * @param int    $perPage
	 * @param array  $columns
	 * @param string $pageName
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 */
	public function scopeDistinctPaginate($query, $perPage = 15, $columns = ['*'], $pageName = 'page')
	{
		// Get the results
		$results = $query->forPage(Input::get($pageName, 1), $perPage)
		                 ->get($columns);

		// Create the paginator
		return new LengthAwarePaginator($results, static::count(), $perPage, Paginator::resolveCurrentPage(), ['path' => Paginator::resolveCurrentPath()]);
	}
}
