<?php

namespace Meetee\Libs\Security\Sanitizers;

use Meetee\Libs\Security\Sanitizers\Sanitizer;

class UriSanitizer implements Sanitizer
{
	public static function run(mixed $value): mixed
	{
		while (!isset($endLength) || $startLength = strlen($value) != $endLength) {
			if (!is_string($value))
				throw new \Exception("URI must be string");

			if (strlen($value) > 255)
				$value = substr($value, 0, 255);

			$value = filter_var($value, FILTER_SANITIZE_URL);
			$value = htmlspecialchars($value);
			$value = stripslashes($value);
			$value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
			$endLength = strlen($value);
		}

		return $value;
	}
}