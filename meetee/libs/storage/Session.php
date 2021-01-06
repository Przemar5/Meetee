<?php

namespace Meetee\Libs\Storage;

use Meetee\Libs\Storage\Storage;

class Session implements Storage
{
	public static function start(string $name): void
	{
		session_name($name);
		session_cache_limiter('nocache');
		session_start();
		session_regenerate_id(true);
	}

	public static function get(string $name)
	{
		if (isset($_SESSION[$name]))
			return $_SESSION[$name];
	}

	public static function set(string $name, $value): void
	{
		$_SESSION[$name] = $value;
	}

	public static function isset(string $name): bool
	{
		return isset($_SESSION[$name]);
	}

	public static function unset(string $name): void
	{
		if (isset($_SESSION[$name]))
			unset($_SESSION[$name]);
	}
}