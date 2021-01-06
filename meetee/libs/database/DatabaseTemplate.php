<?php

namespace Meetee\Libs\Database;

use Meetee\Libs\Database\Query_builders\QueryBuilderTemplate;

abstract class DatabaseTemplate
{
	protected \PDO $database;
	protected QueryBuilderTemplate $queryBuilder;

	public function __construct(
		QueryBuilderTemplate $queryBuilder,
		array $connectionDetails = []
	)
	{
		$getDetailOrThrowException = fn($value, $msg) => 
			$connectionDetails[$value] ?? throw new \Exception($msg);

		$this->queryBuilder = $queryBuilder;
		$dsn = sprintf('%s:host=%s;port=%s;dbname=%s;',
			$getDetailOrThrowException('driver', 'Database driver is not specified'),
			$getDetailOrThrowException('host', 'Database host is not specified'),
			$getDetailOrThrowException('port', 'Database port is not specified'),
			$getDetailOrThrowException('dbname', 'Database name is not specified')
		);

		$user = $getDetailOrThrowException('user', 'Username is missing');
		$password = $getDetailOrThrowException('password', 'Password is missing');

		$this->database = new \PDO($dsn, $user, $password);
	}

	// abstract public function select(string $table, array $options);
}