<?php

namespace Meetee\Libs\Database\Query_builders;

abstract class QueryBuilderTemplate
{
	protected ?string $query = null;
	protected ?string $table = null;
	protected ?string $action = null;
	protected ?array $values = null;
	protected ?array $conditions = null;
	protected ?int $limit = null;
	protected ?int $offset = null;

	public function reset(): void
	{
		$this->query = null;
		$this->table = null;
		$this->action = null;
		$this->values = null;
		$this->conditions = null;
		$this->limit = null;
		$this->offset = null;
	}

	public function in(string $table): void
	{
		$this->table = $table;
	}

	public function select(array $values): void
	{
		$this->action = 'SELECT';
		$this->values = $values;
	}

	public function insert(array $values): void
	{
		$this->action = 'INSERT';
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

	public function where(array $conditions): void
	{
		$this->conditions = $conditions;
	}

	public function limit(int $limit): void
	{
		$this->limit = $limit;
	}

	public function offset(int $offset): void
	{
		$this->offset = $offset;
	}

	abstract public function getBindings(): array;

	abstract public function getResult();

	protected function throwExceptionsIfSomethingMissing(): void
	{
		$this->throwExceptionIfTableMissing();
		$this->throwExceptionIfActionMissing();
		$this->throwExceptionIfValuesMissing();
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