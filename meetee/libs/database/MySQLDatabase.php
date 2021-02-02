<?php

namespace Meetee\Libs\Database;

use Meetee\Libs\Database\DatabaseTemplate;

class MySQLDatabase extends DatabaseTemplate
{
	protected \PDOStatement $stmt;

	protected function __construct(array $connectionDetails = [])
	{
		$connectionDetails['driver'] = 'mysql';
		$connectionDetails['port'] = $connectionDetails['port'] ?? 3306;

		parent::__construct($connectionDetails);
	}

	public function sendQuery(string $query, ?array $bindings = []): void
	{
		$this->stmt = $this->database->prepare($query);
		$this->bind($bindings);
		$this->stmt->execute();
	}

	private function bind(array $params): void
	{
		foreach ($params as $key => $value) {
			$this->stmt->bindValue($key, $value, $this->getPdoParamType($value));
		}
	}

	private function getPdoParamType($value = null): int
	{
		switch (gettype($value)) {
			case 'integer':	return \PDO::PARAM_INT;
			case 'string':	return \PDO::PARAM_STR;
			case 'boolean':	return \PDO::PARAM_BOOL;
			case 'null': 	return \PDO::PARAM_NULL;
			default: 		return \PDO::PARAM_NULL;
		}
	}

	public function findOne(
		string $query, 
		?array $bindings = []
	)
	{
		$this->stmt = $this->database->prepare($query);
		$this->bind($bindings);
		$this->stmt->execute();

		return $this->stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function findObject(
		string $query,
		?array $bindings = [],
		string $object = null
	)
	{
		$this->stmt = $this->database->prepare($query);
		$this->bind($bindings);
		$this->stmt->execute();

		return $this->stmt->fetchObject($object);
	}

	public function findMany(string $query, ?array $bindings = [])
	{
		$this->stmt = $this->database->prepare($query);
		$this->bind($bindings);
		$this->stmt->execute();

		return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function lastInsertId(): int
	{
		return $this->database->lastInsertId();
	}

	public function debug()
	{
		return $this->stmt->debugDumpParams();
	}
}