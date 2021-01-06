<?php

namespace Meetee\Libs\Security\Validators;

interface Validator
{
	public function run($value): bool;
}