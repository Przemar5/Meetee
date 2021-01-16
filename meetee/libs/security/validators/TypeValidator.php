<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class TypeValidator extends Validator
{
	private string $type;

	public function setType(string $type): void
	{
		$this->type = $type;
	}

	public function run($value): bool
	{
		if (!isset($this->type))
			throw new \Exception("Comparision type isn't set.");

		return gettype($value) === $this->type;
	}
}