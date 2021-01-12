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

	public static function find(int $id): ?Entity
	{
		$entity = new static();

		return $entity->table->find($id);
	}

	public static function findOneWhere(array $conditions): ?Entity
	{
		$entity = new static();

		return $entity->table->findOneWhere($conditions);
	}

	public static function findManyWhere(array $conditions)
	{
		$entity = new static();

		return $entity->table->findManyWhere($conditions);
	}

	public function save(): void
	{
		$this->table->save($this);
		$this->id = $this->table->lastInsertId();
	}

	// abstract public static function find(int $id): static;

	// abstract public function save(): void;
}