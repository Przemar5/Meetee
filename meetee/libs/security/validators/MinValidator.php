<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class MinValidator extends Validator
{
	private int $minimum;

	public function setMinimum(int $minimum): void
	{
		$this->minimum = $minimum;
	}

	public function run($value): bool
	{
		if (!isset($this->minimum))
			throw new \Exception("Minimum isn't set.");

		return $value >= $this->minimum;
	}
}