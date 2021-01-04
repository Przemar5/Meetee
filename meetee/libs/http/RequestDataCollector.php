<?php

namespace Meetee\Libs\Http;

class RequestDataColector
{
	public static function getForLogging(): string
	{
		$content = sprintf("URI:\t%s\r\n", '');
		$content .= sprintf("Method:\t%s\r\n", $_SERVER['REQUEST_METHOD']);
		$requestData = static::getRequestArrayData($_REQUEST);
		$content .= sprintf("Request:\r\n%s", $requestData);

		return $content;
	}

	private static function getRequestArrayData(mixed $request): string
	{
		$data = "";

		foreach ($request as $key => $value) {
			if (is_array($value))
				$value = static::getRequestArrayData($value);

			$data .= sprintf("\t%s: %s\r\n", $key, $value);
		}

		return $data;
	}
}