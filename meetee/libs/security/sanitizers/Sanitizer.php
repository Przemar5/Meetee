<?php

namespace Meetee\Libs\Security\Sanitizers;

interface Sanitizer
{
	public function run(mixed $value): mixed;
}