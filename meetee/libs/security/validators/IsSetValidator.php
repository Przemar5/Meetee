<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class IsSetValidator extends Validator
{
	public function run($value): bool
	{
		return isset($value);
	}
}