<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class PatternValidator extends Validator
{
	private string $pattern;

	public function setPattern(string $pattern): void
	{
		$this->pattern = $pattern;
	}

	public function run($value): bool
	{
		if (!is_string($value))
			throw new \Exception("Value must be a string.");

		if (!isset($this->pattern))
			throw new \Exception("Pattern isn't set.");

		return preg_match($this->pattern, $value);
	}
}