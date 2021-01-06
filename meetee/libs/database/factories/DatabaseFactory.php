<?php

namespace Meetee\Libs\Database\Factories;

use Meetee\Libs\Database\MysqlDatabase;

class DatabaseFactory
{
	public static function createMysql(): MysqlDatabase
	{
		$details = [
			'host' => '127.0.0.1',
			'port' => 3306,
		]
	}
}