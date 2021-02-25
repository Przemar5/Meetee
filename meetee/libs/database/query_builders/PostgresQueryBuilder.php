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
			case 'WITH': $this->prepareWith(); break;
			case 'SELECT': $this->prepareSelect(); break;
			case 'INSERT': $this->prepareInsert(); break;
			case 'INSERT MANY': $this->prepareInsertMultiple(); break;
			case 'UPDATE': $this->prepareUpdate(); break;
			case 'DELETE': $this->prepareDelete(); break;
		}
	}

	private function prepareWith(): void
	{
		//
	}

	private function prepareSelect(): void
	{
		$columns = implode(', ', $this->columns);
		$this->query = sprintf('SELECT %s FROM %s', $columns, $this->table);
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
		$this->appendOrderByPartIfExists();
		$this->appendLimitPartIfExists();
		$this->appendOffsetPartIfExists();
	}

	private function appendWherePartIfExists(): void
	{
		$where = $this->parseWhereClause($this->conditions);
		$this->query .= (!empty(trim($where, '()'))) ? ' WHERE ' . $where : '';
	}

	private function parseWhereClause($data): string
	{
		$result = [];
		$type = (isset($data[0]) && is_string($data[0]))
			? array_shift($data) : 'AND';

		// if (strcasecmp($type, 'AND') !== 0 || strcasecmp($type, 'OR') !== 0)
		// 	return '';

		foreach ($data as $key => $value) {
			if (is_integer($key)) {
				$result[] = $this->parseWhereClause($value);
			}
			elseif ($value === false) {
				$result[] = "$key = FALSE";
			}
			elseif ($value === true) {
				$result[] = "$key = TRUE";
			}
			elseif (is_null($value)) {
				$result[] = "$key IS NULL";
			}
			elseif (is_string($value) || is_integer($value) || is_float($value)) {
				$bindingName = $this->getNewBindingName();
				$result[] = sprintf('%s = %s', $key, $bindingName);
				$this->additionalBindings[$bindingName] = $value;
			}
			elseif (is_array($value) && isset($value[0]) && isset($value[1]) && 
				in_array($value[0], ['=', '<', '<=', '>', '>='])) {
				$bindingName = $this->getNewBindingName();
				$result[] = sprintf('%s %s %s', $key, $value[0], $bindingName);
				$this->additionalBindings[$bindingName] = $value[1];
			}
		}
		return "(" . implode(" $type ", $result) . ")";
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
		$fields = [];
		$values = [];

		foreach ($this->values as $key => $value) {
			if (is_string($value) || is_numeric($value)) {
				$bind = $this->getNewBindingName();
				$fields[] = $key;
				$values[] = $bind;
				$this->additionalBindings[$bind] = $value;
			}
			elseif ($value === false) {
				$fields[] = $key;
				$values[] = 'FALSE';
			}
			elseif ($value === true) {
				$fields[] = $key;
				$values[] = 'TRUE';
			}
			elseif ($value === null) {
				$fields[] = $key;
				$values[] = 'NULL';
			}
		}

		$this->query = sprintf('INSERT INTO %s (%s) VALUES (%s)', 
			$this->table, 
			implode(', ', $fields), 
			implode(', ', $values)
		);
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

		if (!empty($this->onConflict))
			$this->query .= ' ON CONFLICT DO ' . $this->onConflict;
	}

	public function getBindings(): array
	{
		return $this->additionalBindings;
	}

	private function prepareUpdate(): void
	{
		$fields = [];
		
		foreach ($this->values as $key => $value) {
			if (is_string($value) || is_numeric($value)) {
				$bind = $this->getNewBindingName();
				$fields[] = "$key = $bind";
				$this->additionalBindings[$bind] = $value;
			}
			elseif ($value === false) {
				$fields[] = "$key = FALSE";
			}
			elseif ($value === true) {
				$fields[] = "$key = TRUE";
			}
			elseif ($value === null) {
				$fields[] = "$key = NULL";
			}
		}

		$this->query = sprintf('UPDATE %s SET %s', 
			$this->table, 
			implode(', ', $fields)
		);
		$this->appendOptionalParts();
	}

	private function prepareDelete(): void
	{
		$this->query = sprintf('DELETE FROM %s', $this->table);
		$this->appendOptionalParts();
	}
}