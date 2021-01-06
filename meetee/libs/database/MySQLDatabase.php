<?php

namespace Meetee\Libs\Database;

use Meetee\Libs\Database\DatabaseTemplate;
use Meetee\Libs\Database\Query_builders\MySQLQueryBuilder;

class MySQLDatabase extends DatabaseTemplate
{
	public function __construct(array $connectionDetails = [])
	{
		$connectionDetails['driver'] = 'mysql';
		$connectionDetails['port'] ??= 3306;

		parent::__construct(new MySQLQueryBuilder(), $connectionDetails);
	}

	public function select(array $values): self
	{
		$this->query = 'SELECT';

		return self;
	}

	public function create(array $values): self
	{
		$this->query = 'CREATE';

		return self;
	}

	public function update(array $values): self
	{
		$this->query = 'UPDATE';

		return self;
	}

	public function delete(array $ids): self
	{
		$this->query = 'DELETE';

		return self;
	}

	public function where(array $conditions): self
	{
		$this->conditions = $conditions;
	}

	public function limit(int $limit): self
	{
		$this->limit = $limit;
	}

	public function offset(int $offset): self
	{
		$this->offset = $offset;
	}

	public function getResult()
	{

	}
}