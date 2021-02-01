<?php

namespace Meetee\Libs\Database\Query_builders;

use Meetee\Libs\Database\Query_builders\QueryBuilderTemplate;

class PostgresQueryBuilder extends QueryBuilderTemplate
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
		$this->appendAllWhereParts();
		$this->appendOrderByPartIfExists();
		$this->appendLimitPartIfExists();
		$this->appendOffsetPartIfExists();
	}

	private function appendAllWhereParts(): void
	{
		$conditions = array_merge(
			$this->getWherePart(),
			$this->getWhereNullPart(),
			$this->getWhereFalsePart(),
			$this->getWhereTruePart(),
			$this->whereAre
		);

		$this->query .= implode(' AND ', $conditions);
	}

	private function getWherePart(): array
	{
		$conditions = [];

		foreach ($this->conditions as $key => $value) {
			$conditions[] = sprintf('%s = :%s', $key, $key);
			$this->additionalBindings[":$key"] = $value;
		}

		return $conditions;
	}

	private function getWhereNullPart(): array
	{
		$mapFunc = fn($c) => sprintf('%s IS NULL', $c);

		return array_map($mapFunc, $this->whereNull);
	}

	private function getWhereFalsePart(): array
	{
		$mapFunc = fn($c) => sprintf('%s IS FALSE', $c);

		return array_map($mapFunc, $this->whereFalse);
	}

	private function getWhereTruePart(): array
	{
		$mapFunc = fn($c) => sprintf('%s IS TRUE', $c);

		return array_map($mapFunc, $this->whereTrue);
	}

	private function appendOrderByPartIfExists(): void
	{
		if (is_null($this->orderBy))
			return;

		$orderBy = implode(', ', $this->orderBy);
		$direction = ($this->orderDesc) ? 'DESC' : 'ASC';

		$this->query .= sprintf(' ORDER BY %s %s', $orderBy, $direction);
	}

	private function appendLimitPartIfExists(): void
	{
		if (!is_null($this->limit))
			$this->query .= ' LIMIT ' . $this->limit;
	}

	private function appendOffsetPartIfExists(): void
	{
		if (!is_null($this->offset))
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
		$this->additionalBindings = [];
		$toBind = [];

		for ($i = 0; $i < count($this->values); $i++) {
			$additional = array_fill(0, count($columns), $i);
			$otherToBind = array_map(
				fn($c, $i) => sprintf(":%s_%s", $c, $i), $columns, $additional);
			$toBind[] = '(' . implode(', ', $otherToBind) . ')';
			
			$this->additionalBindings = array_merge(
				$this->additionalBindings,
				array_combine($otherToBind, array_values($this->values[$i]))
			);
		}
		$columns = implode(', ', $columns);
		$toBind = implode(', ', $toBind);

		$this->query = sprintf('INSERT INTO %s (%s) VALUES %s', 
			$this->table, $columns, $toBind);
	}

	public function getBindings(): array
	{
		$allToBind = array_merge($this->values, $this->conditions);
		$keys = array_keys($allToBind);
		$keys = array_map(fn($k) => ":$k", $keys);
		$values = array_values($allToBind);
		$bindings = array_combine($keys, $values);

		return array_merge($bindings, $this->additionalBindings);
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
		$this->query = sprintf('DELETE FROM %s', $this->table);
		$this->appendOptionalParts();
	}
}