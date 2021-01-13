<?php

namespace Meetee\Libs\Security;

class Hash
{
	public static function make(string $value): string
	{
		return password_hash($value, PASSWORD_DEFAULT);
	}

	public static function verify(string $password, string $hash): bool
	{
		return password_verify($password, $hash);
	}
}