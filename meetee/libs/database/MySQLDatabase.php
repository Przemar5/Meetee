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
		$stmt = $this->database->prepare($query);
		$stmt->execute($bindings);
	}

	public function findOne(string $query, ?array $bindings = [])
	{
		$stmt = $this->database->prepare($query);
		$stmt->execute($bindings);

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function findMany(string $query, ?array $bindings = [])
	{
		$stmt = $this->database->prepare($query);
		$stmt->execute($bindings);

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
}