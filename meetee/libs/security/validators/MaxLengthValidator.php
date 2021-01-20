<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class MaxLengthValidator extends Validator
{
	private int $maximum;

	public function setMaximum(int $maximum): void
	{
		$this->maximum = $maximum;
	}

	public function run($value): bool
	{
		if (!is_string($value))
			throw new \Exception("Value must be a string.");

		if (!isset($this->maximum))
			throw new \Exception("Maximum isn't set.");

		return strlen($value) <= $this->maximum;
	}
}