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
		// Loop through each element
		foreach($value as $n => $v) {
			// Test each parameter
			foreach($parameters as $rule) {
				$method      = "validate{$rule}";

				// If failed, add the message
				if($this->isValidatable($rule, $attribute, $value) && !$this->$method($attribute, $v, $parameters, $this)) {
					$valid     = false;
					$customKey = $attribute . '.each.' . $rule;
					$message   = isset($this->customMessages[$customKey]) ? $this->customMessages[$customKey] : $this->translator->trans('validation.' . $rule);
					$this->messages->add($attribute . '.' . $n, $message);
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

		return $this->validateRegex($attribute, $value, ["/^(([+][\d]{2})|(0))[\d]{10}$/"]);
	}

	/**
	 * Validate as a name.
	 * @param $attribute
	 * @param $value
	 * @return bool
	 */
	public function validateName($attribute, $value)
	{
		return $this->validateRegex($attribute, $value, ["/^[a-zA-Z-]+\s[a-zA-Z-]+$/"]);
	}
}