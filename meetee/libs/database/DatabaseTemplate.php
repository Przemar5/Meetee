<?php

namespace Meetee\Libs\Database;

use Meetee\Libs\Database\Query_builders\QueryBuilderTemplate;

abstract class DatabaseTemplate
{
	protected \PDO $database;
	protected QueryBuilderTemplate $queryBuilder;
	protected static DatabaseTemplate $instance;

	protected function __construct(array $connectionDetails = [])
	{
		$getDetailOrThrowException = fn($value, $msg) => 
			$connectionDetails[$value] ?? throw new \Exception($msg);

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

	public static function getInstance(?array $details = null): static
	{
		if (!isset(static::$instance)) {
			if (is_null($details))
				throw new \Exception('Details array is missing.');

			static::$instance = new static($details);
		}

		return static::$instance;
	}

	abstract public function sendQuery(string $query, ?array $bindings = []): void;
}