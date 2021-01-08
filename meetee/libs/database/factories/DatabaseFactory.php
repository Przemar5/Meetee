<?php

namespace Meetee\Libs\Database\Factories;

use Meetee\Libs\Database\DatabaseTemplate;
use Meetee\Libs\Database\MySQLDatabase;
use Meetee\Libs\Converters\Converter;

class DatabaseFactory
{
	public static function create(): DatabaseTemplate
	{
		$config = file_get_contents('./config/database.json');
		$details = Converter::jsonToArray($config);

		switch ($details['driver']) {
			case 'mysql': 	return MySQLDatabase::getInstance($details);
			default: 		return MySQLDatabase::getInstance($details);
		}
	}
}