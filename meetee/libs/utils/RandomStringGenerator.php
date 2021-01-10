<?php

namespace Meetee\Libs\Utils;

class RandomStringGenerator
{
	public static function generate(
		?int $length = 32, 
		?string $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
	): string
	{
		if ($length < 0)
			throw new \Exception('Length must be positive.');

		$pieces = [];
		$max = mb_strlen($chars, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i)
			$pieces[] = $chars[random_int(0, $max)];
		
		return implode('', $pieces);
	}

	public static function generateHex(int $length = 32): string
	{
		return bin2hex(random_bytes($length * 2));
		return self::generate($length, '0123456789abcdef');
	}	
}