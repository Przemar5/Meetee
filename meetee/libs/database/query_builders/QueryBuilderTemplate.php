<?php

namespace Meetee\Libs\Database\Query_builders;

abstract class QueryBuilderTemplate
{
	protected ?string $query = null;
	protected ?string $table = null;
	protected ?string $action = null;
	protected ?array $columns = null;
	protected ?array $values = null;
	protected ?string $joinTable = null;
	protected ?string $joinType = null;
	protected ?array $joinOn = null;
	protected ?array $conditions = null;
	protected ?array $whereNull = null;
	protected ?int $limit = null;
	protected ?int $offset = null;
	protected ?array $additionalBindings = null;

	public function reset(): void
	{
		$this->query = null;
		$this->table = null;
		$this->action = null;
		$this->columns = null;
		$this->values = null;
		$this->joinTable = null;
		$this->joinType = null;
		$this->joinOn = null;
		$this->conditions = null;
		$this->whereNull = null;
		$this->limit = null;
		$this->offset = null;
		$this->additionalBindings = null;
	}

	public function in(string $table): void
	{
		$this->table = $table;
	}

	public function select(array $columns): void
	{
		$this->action = 'SELECT';
		$this->columns = $columns;
	}

	public function insert(array $values): void
	{
		$this->action = 'INSERT';
		$this->values = $values;
	}

	public function insertMultiple(array $values): void
	{
		$this->action = 'INSERT MANY';
		$this->values = $values;
	}

	public function update(array $values): void
	{
		$this->action = 'UPDATE';
		$this->values = $values;
	}

	public function delete(): void
	{
		$this->action = 'DELETE';
	}

	public function innerJoin(string $table): void
	{
		$this->joinType = 'INNER';
		$this->joinTable = $table;
	}

	public function on(array $joins): void
	{
		$this->joinOn = $joins;
	}

	public function where(array $conditions): void
	{
		$this->conditions = $conditions;
	}

	public function whereNull(array $whereNull): void
	{
		$this->whereNull = $whereNull;
	}

	public function limit(int $limit): void
	{
		$this->limit = $limit;
	}

	public function offset(int $offset): void
	{
		$this->offset = $offset;
	}

	public function setAdditionalBindings(array $bindings): void
	{
		$this->additionalBindings = $bindings;
	}

	abstract public function getBindings(): array;

	abstract public function getResult();

	public function getAdditionalBindings(): ?array
	{
		return $this->additionalBindings;
	}

	protected function throwExceptionIfSomethingMissing(): void
	{
		$this->throwExceptionIfTableMissing();
		$this->throwExceptionIfActionMissing();
	}

	protected function throwExceptionIfTableMissing(): void
	{
		if (is_null($this->table))
			throw new \Exception('Table name is missing');
	}

	protected function throwExceptionIfActionMissing(): void
	{
		if (is_null($this->action))
			throw new \Exception('Action is missing');
	}

	protected function throwExceptionIfValuesMissing(): void
	{
		if (is_null($this->values))
			throw new \Exception('Values are missing');
	}
}