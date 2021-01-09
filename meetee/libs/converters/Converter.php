<?php

namespace Meetee\Libs\Converters;

class Converter
{
	public static function jsonToArray(string $json): array
	{
		return json_decode($json, true);
	}
}