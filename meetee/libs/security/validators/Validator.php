<?php

namespace Meetee\Libs\Security\Validators;

abstract class Validator
{
	public string $errorMsg;

	public function run($value): bool;
}