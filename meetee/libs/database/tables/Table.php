<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\App\Entities\Entity;

abstract class Table
{
	protected string $name;

	abstract function select(int $id): ?Entity;

	abstract function create(Entity $entity): void;
	
	abstract function update(Entity $entity): void;
	
	abstract function delete(Entity $entity): void;
}