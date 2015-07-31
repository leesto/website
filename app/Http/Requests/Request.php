<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
	/**
	 * Provide the ability for all requests to return values with the html
	 * tags stripped.
	 * @return array
	 */
	public function stripped()
	{
		if(func_num_args() > 0) {
			$inputs = $this->only(func_get_args());
		} else {
			$inputs = $this->all();
		}
		$inputs_stripped = [];

		foreach($inputs as $n => $v) {
			$inputs_stripped[$n] = is_array($v) ? array_map("strip_tags", $v) : strip_tags($v);
		}

		return count($inputs_stripped) == 1 ? array_shift($inputs_stripped) : $inputs_stripped;
	}
}
