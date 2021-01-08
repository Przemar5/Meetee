<?php

namespace Meetee\Libs\Database;

use Meetee\Libs\Database\DatabaseTemplate;
use Meetee\Libs\Database\Query_builders\MySQLQueryBuilder;

class MySQLDatabase extends DatabaseTemplate
{
	protected function __construct(array $connectionDetails = [])
	{
		$connectionDetails['driver'] = 'mysql';
		$connectionDetails['port'] ??= 3306;

		parent::__construct($connectionDetails);
	}

	public function sendQuery(string $query, ?array $bindings = []): void
	{
		//
	}
}