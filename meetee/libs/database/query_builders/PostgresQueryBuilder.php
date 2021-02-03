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
		$this->query .= (!empty($where)) ? ' WHERE ' . $where : '';
	}

	private function parseWhereClause($data): string
	{
		$result = [];
		$type = (isset($data[0]) && is_string($data[0]))
			? array_shift($data) : 'AND';

		if (strcasecmp($type, 'AND') !== 0 || strcasecmp($type, 'OR') !== 0)
			return '';

		foreach ($data as $key => $value) {
			if (is_integer($key)) {
				$result[] = parse($value);
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
				$result[] = sprintf('%s = :%s', $key, $bindingName);
				$this->additionalBindings[':'.$bindingName] = $value;
			}
			elseif (is_array($value) && isset($value[0]) && isset($value[1]) &&
				in_array($value[0], ['=', '<', '<=', '>', '>='])) {
				$bindingName = $this->getNewBindingName();
				$result[] = sprintf('%s %s :%s', $key, $value[0], $bindingName);
				$this->additionalBindings[':'.$bindingName] = $value;
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
		unset($this->values['id']);
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
		$keys = array_keys($this->values);
		$keys = array_map(fn($k) => ":$k", $keys);
		$values = array_values($this->values);
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