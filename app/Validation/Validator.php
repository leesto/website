<?php
namespace App\Validation;

use Illuminate\Validation\Validator as BaseValidator;

class Validator extends BaseValidator
{
	/**
	 * Validate each value in the array against each rule.
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return bool
	 */
	public function validateEach($attribute, $value, $parameters)
	{
		// Test the value is an array
		if(!$this->validateArray($attribute, $value)) {
			return false;
		}

		$valid = true;
		foreach($value as $n => $v) {
			foreach($parameters as $rule) {
				$validatable = $this->isValidatable($rule, $attribute, $value);
				$method = "validate{$rule}";

				if($validatable && !$this->$method($attribute, $v, $parameters, $this)) {
					$valid = false;
					$message = array_key_exists($attribute . '.each.' . $rule, $this->customMessages)
						?
						$this->customMessages[$attribute . '.each.' . $rule]
						:
						'validation.' . $rule;

					$this->messages->add($attribute . '.' . $n, trans($message));
				}
			}
		}

		return $valid;
	}

	/**
	 * Validate as a phone number.
	 * @param $attribute
	 * @param $value
	 * @return bool
	 */
	public function validatePhone($attribute, $value)
	{
		$value = preg_replace('/\s+/', '', $value);

		return $this->validateRegex($attribute, $value, ["/^(([+][\d]{2})|(0))[\d]{10}/"]);
	}
}