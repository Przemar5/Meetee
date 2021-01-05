<?php

namespace Meetee\Libs\Security\Sanitizers;

interface Sanitizer
{
	public static function run(mixed $value): mixed;
}