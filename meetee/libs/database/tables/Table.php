<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\App\Entities\Entity;

abstract class Table
{
	protected string $name;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	abstract public function select(int $id): ?Entity;

	abstract public function create(Entity $entity): void;
	
	abstract public function update(Entity $entity): void;
	
	abstract public function delete(Entity $entity): void;
}