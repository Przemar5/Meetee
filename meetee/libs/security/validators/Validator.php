<?php

namespace Meetee\Libs\Security\Validators;

abstract class Validator
{
	public string $errorMsg;

	abstract public function run($value): bool;
}