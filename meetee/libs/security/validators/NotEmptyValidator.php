<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class NotEmptyValidator extends Validator
{
	public function run($value): bool
	{
		return !empty($value);
	}
}