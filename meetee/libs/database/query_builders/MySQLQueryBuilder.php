<?php

namespace Meetee\Libs\Database\Query_builders;

use Meetee\Libs\Database\Query_builders\QueryBuilderTemplate;

class MySQLQueryBuilder extends QueryBuilderTemplate
{
	public function getResult()
	{
		$this->throwExceptionIfSomethingMissing();

		return $this->getPreparedQuery();
	}

	private function getPreparedQuery(): void
	{
		switch ($this->action) {
			case 'SELECT': $this->query = $this->prepareSelect(); break;
			case 'INSERT': $this->query = $this->prepareInsert(); break;
			case 'UPDATE': $this->query = $this->prepareUpdate(); break;
			case 'DELETE': $this->query = $this->prepareDelete(); break;
		}
	}

	private function prepareSelect(): void
	{
		$columns = implode(', ', $this->values);
		$this->query = sprintf('SELECT %s FROM `%s`', $columns, $this->table);

		$this->appendOptionalParts();
	}

	private function appendOptionalParts(): void
	{
		$this->appendWherePartIfExists();
		$this->appendLimitPartIfExists();
		$this->appendOffsetPartIfExists();
	}

	private function appendWherePartIfExists(): void
	{
		if (is_null($this->conditions))
			return;

		$conditions = implode(' AND ', $this->conditions);
		$this->query .= ' WHERE ' . $conditions;
	}

	private function appendLimitPartIfExists(): void
	{
		if (is_null($this->limit))
			return;

		$this->query .= ' LIMIT ' . $this->limit;
	}

	private function appendOffsetPartIfExists(): void
	{
		if (is_null($this->offset))
			return;

		$this->query .= ' OFFSET ' . $this->offset;
	}

	private function prepareInsert(): void
	{
		$columns = array_keys($this->values);
		$toBind = array_map(fn($c) => ":c", $columns);

		$this->query = sprintf(
			'INSERT INTO %s (%s) VALUES (%s)', $this->table, $columns, $toBind);
	}

	public function getBindings(): array
	{
		$keys = array_keys($this->values);
		$keys = array_map(fn($k) => ":$k", $keys);
		$values = array_values($this->values);

		return array_combine($keys, $values);
	}

	private function prepareUpdate(): void
	{
		$columns = array_keys($this->values);
		$toBind = array_map(fn($c) => ":$c", $columns);
		$updatePart = [];

		for ($i = 0; $i < count($columns); $i++)
			$updatePart[] = sprintf('%s = %s', $columns[$i], $toBind[$i]);
		
		$updatePart = implode(', ', $updatePart);

		$this->query = sprintf('UPDATE %s SET %s', $this->table, $updatePart);
		$this->appendOptionalParts();
	}

	private function prepareDelete(): void
	{
		if (!empty($this->conditions))
			throw new \Exception(
				'Conditions are missing. Cannot delete all records.');

		$this->query = sprintf('DELETE FROM %s', $this->table);
		$this->appendOptionalParts();
	}
}