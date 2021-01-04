<?php

namespace Meetee\Libs\Files\Factories;

use Meetee\Libs\Files\Logger;

class LoggerFactory
{
	public static function createRequestLogger(): Logger
	{
		return new Logger('./logs/requests.log');
	}
}