<?php

namespace Meetee\Libs\Database\Factories;

use Meetee\Libs\Database\DatabaseTemplate;
use Meetee\Libs\Database\MySQLDatabase;
use Meetee\Libs\Database\Query_builders\QueryBuilderTemplate;
use Meetee\Libs\Database\Query_builders\MySQLQueryBuilder;
use Meetee\Libs\Converters\Converter;

class DatabaseAbstractFactory
{
	public static function createDatabase(): DatabaseTemplate
	{
		$config = file_get_contents('./config/database.json');
		$details = Converter::jsonToArray($config);

		switch ($details['driver']) {
			case 'mysql': 	return MySQLDatabase::getInstance($details);
			default: 		return MySQLDatabase::getInstance($details);
		}
	}

	public static function createQueryBuilder(): QueryBuilderTemplate
	{
		$config = file_get_contents('./config/database.json');
		$details = Converter::jsonToArray($config);

		switch ($details['driver']) {
			case 'mysql': 	return new MySQLQueryBuilder();
			default: 		return new MySQLQueryBuilder();
		}
	}
}