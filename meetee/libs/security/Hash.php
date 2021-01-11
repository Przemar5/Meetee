<?php

namespace Meetee\Libs\Security;

class Hash
{
	public static function create(string $value): string
	{
		return password_hash($value, PASSWORD_DEFAULT);
	}
}