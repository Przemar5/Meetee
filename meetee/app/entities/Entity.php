<?php

namespace Meetee\App\Entities;

use Meetee\Libs\Database\Tables\TableTemplate;

abstract class Entity
{
	protected TableTemplate $table;

	public function __construct(TableTemplate $table)
	{
		$this->table = $table;
	}

	public function save(): void
	{
		$this->table->save($this);
		$this->id = $this->table->lastInsertId();
	}
}