<?php

namespace Meetee\Libs\Storage;

use Meetee\Libs\Storage\Storage;

class Cookie implements Storage
{
	public static function get(string $name)
	{
		if (isset($_COOKIE[$name]))
			return $_COOKIE[$name];
	}

	public static function set(string $name, $value): void
	{
		setcookie($name, $value, time()+60*60*24*365);
	}

	public static function pop(string $name)
	{
		if (isset($_COOKIE[$name])) {
			$value = $_COOKIE[$name];
			static::unset($_COOKIE[$name]);

			return $value;
		}
	}

	public static function isset(string $name): bool
	{
		return isset($_COOKIE[$name]);
	}

	public static function unset(string $name): void
	{
		if (isset($_COOKIE[$name]))
			setcookie($name, null, -1);
	}
}