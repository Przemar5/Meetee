<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class UriValidator extends Validator
{
	public function run($value): bool
	{
		return (bool) filter_var($value, FILTER_VALIDATE_URL);
	}
}