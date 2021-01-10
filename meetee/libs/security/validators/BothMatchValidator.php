<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class BothMatchValidator extends Validator
{
	private string $expected;

	public function setExpected(string $expected): void
	{
		$this->expected = $expected;
	}

	public function run($value): bool
	{
		if (!isset($this->expected))
			throw new \Exception("Comparision value isn't set.");

		return $value === $this->type;
	}
}