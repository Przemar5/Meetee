<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class MaxValidator extends Validator
{
	private int $maximum;

	public function setMaximum(int $maximum): void
	{
		$this->maximum = $maximum;
	}

	public function run($value): bool
	{
		if (!isset($this->maximum))
			throw new \Exception("Maximum isn't set.");

		return $value <= $this->maximum;
	}
}