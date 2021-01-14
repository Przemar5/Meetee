<?php

namespace Meetee\Libs\Http;

class RequestDataCollector
{
	public static function getForLogging(): string
	{
		$content = sprintf("DATE:\t%s\r\n", date('Y.m.d H:i:s'));
		$content .= sprintf("URI:\t%s\r\n", $_SERVER['PATH_INFO'] ?? '/');
		$content .= sprintf("Method:\t%s\r\n", $_SERVER['REQUEST_METHOD']);
		$content .= sprintf("REMOTE USER:\t%s\r\n", $_SERVER['REMOTE_ADDR']);
		$requestData = static::getRequestArrayData($_REQUEST);
		$requestData .= sprintf("HEADERS:\r\n%s\r\n", 
			static::getRequestArrayData(getallheaders()));

		$content .= sprintf("Request:\r\n%s\n", $requestData);

		return $content;
	}

	private static function getRequestArrayData($request): string
	{
		$data = "";
		foreach ($request as $key => $value)
			$data .= sprintf("\t%s: %s\r\n", $key, print_r($value, true));

		return $data;
	}
}