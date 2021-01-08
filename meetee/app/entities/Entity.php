<?php

namespace Meetee\App\Entities;

use Meetee\Libs\Database\Tables\Table;

abstract class Entity
{
	protected array $tableColumns = [];
	protected Table $table;

	public function __construct(Table $table)
	{
		$this->table = $table;
	}

	public function save(): void
	{
		$this->table->save($this);
	}

	// abstract public static function find(int $id): static;

	// abstract public function save(): void;
}