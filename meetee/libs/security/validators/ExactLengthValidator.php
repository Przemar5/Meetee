<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class ExactLengthValidator extends Validator
{
	private int $expected;

	public function setExpected(int $expected): void
	{
		$this->expected = $expected;
	}

	public function run($value): bool
	{
		if (!is_string($value))
			throw new \Exception("Value must be a string.");

		if (!isset($this->expected))
			throw new \Exception("Expected length isn't set.");

		return strlen($value) == $this->expected;
	}
}