<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;

class RequestMethodValidator extends Validator
{
	public function run($value): bool
	{
		$methods = ['GET', 'POST'];
		
		return in_array($value, $methods);
	}
}