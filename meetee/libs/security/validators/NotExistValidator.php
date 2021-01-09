<?php

namespace Meetee\Libs\Security\Validators;

use Meetee\Libs\Security\Validators\Validator;
use Meetee\Libs\Database\Tables\TableFactory;

class NotExistValidator extends Validator
{
	private string $table;
	private string $column;

	public function setTable(string $table): void
	{
		$this->table = $table;
	}

	public function setColumn(string $column): void
	{
		$this->column = $column;
	}

	public function run($value): bool
	{
		if (!isset($this->table))
			throw new \Exception("Table isn't set.");

		if (!isset($this->column))
			throw new \Exception("Table column isn't set.");

		$table = TableFactory::createTable($this->table);
		
		if (!isset($table))
			throw new \Exception(
				sprintf("Cannot access table '%s'.", $this->table));

		return (bool) $table->findOneWhere([$this->column => $value]);
	}
}