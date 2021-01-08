<?php

namespace Meetee\Libs\Database\Query_builders;

use Meetee\Libs\Database\Query_builders\QueryBuilderTemplate;

class MySQLQueryBuilder extends QueryBuilderTemplate
{
	public function getResult()
	{
		$this->throwExceptionIfSomethingMissing();
		$this->prepareQuery();

		return $this->query;
	}

	private function prepareQuery(): void
	{
		switch ($this->action) {
			case 'SELECT': $this->prepareSelect(); break;
			case 'INSERT': $this->prepareInsert(); break;
			case 'INSERT MANY': $this->prepareInsertMultiple(); break;
			case 'UPDATE': $this->prepareUpdate(); break;
			case 'DELETE': $this->prepareDelete(); break;
		}
	}

	private function prepareSelect(): void
	{
		$columns = implode(', ', $this->columns);
		$this->query = sprintf('SELECT %s FROM `%s`', $columns, $this->table);
		$this->appendJoinPartIfExist();
		$this->appendOptionalParts();
	}

	private function appendJoinPartIfExist(): void
	{
		if (is_null($this->joinType))
			return;

		if (is_null($this->joinTable))
			return;

		if (is_null($this->joinOn))
			return;

		$joinOn = implode(', ', $this->joinOn);

		$this->query .= sprintf(" %s JOIN %s ON %s", 
			$this->joinType, $this->joinTable, $joinOn);
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
		$toBind = array_map(fn($c) => ":$c", $columns);
		$columns = implode(', ', $columns);
		$toBind = implode(', ', $toBind);

		$this->query = sprintf('INSERT INTO %s (%s) VALUES (%s)', 
			$this->table, $columns, $toBind);
	}

	private function prepareInsertMultiple(): void
	{
		$columns = array_keys($this->values[0]);
		$toBind = [];
		foreach ($i = 0; $i < count($this->values); $i++) {
			$otherToBind = array_map(fn($c) => ":$c_$i", $columns);
			$toBind = array_merge($toBind, $otherToBind);
		}

		$this->query = sprintf('INSERT INTO %s (%s) VALUES %s', 
			$this->table, $columns, $toBind);
	}

	public function getBindings(): array
	{
		$keys = array_keys($this->values ?? []);
		$keys = array_map(fn($k) => ":$k", $keys);
		$values = array_values($this->values ?? []);
		$bindings = array_combine($keys, $values);

		return array_merge($bindings, $this->additionalBindings ?? []);
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