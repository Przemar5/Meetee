<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class DateNotAfterValidator extends Validator
{
	private \DateTime $upperBound;

	public function setUpperBound(\DateTime $date): void
	{
		$this->upperBound = $date;
	}

	public function run($value): bool
	{
		if (!is_string($value))
			throw new \Exception("Value must be a string.");

		if (!isset($this->upperBound))
			throw new \Exception("Lower bound isn't set.");

		$date = new \DateTime($value);

		return $date <= $this->upperBound;
	}
}