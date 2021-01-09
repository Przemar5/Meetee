<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class MinLengthValidator extends Validator
{
	private int $minimum;

	public function setMinimum(int $minimum): void
	{
		$this->minimum = $minimum;
	}

	public function run($value): bool
	{
		if (!is_string($value))
			throw new \Exception("Value must be a string.");

		if (!isset($this->minimum))
			throw new \Exception("Minimum isn't set.");

		return strlen($value) >= $this->minimum;
	}
}