<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class DateAfterValidator extends Validator
{
	private \DateTime $lowerBound;

	public function setLowerBound(\DateTime $date): void
	{
		$this->lowerBound = $date;
	}

	public function run($value): bool
	{
		if (!is_string($value))
			throw new \Exception("Value must be a string.");

		if (!isset($this->lowerBound))
			throw new \Exception("Lower bound isn't set.");

		$date = new \DateTime($value);

		return $date > $this->lowerBound;
	}
}