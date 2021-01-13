<?php

namespace Meetee\Libs\Security\Validators\Compound;

use Meetee\Libs\Security\Validators\Validator;

abstract class CompoundValidator extends Validator
{
	protected array $validators = [];

	public function __construct(array $validators = [])
	{
		$this->validators = $validators;
	}

	public function run($value): bool
	{
		$this->errorMsg = null;

		foreach ($this->validators as $validator) {
			if (!$validator->run($value)) {
				$this->errorMsg = $validator->errorMsg;

				return false;
			}
		}

		return true;
	}
}