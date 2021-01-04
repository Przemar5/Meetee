<?php

namespace Meetee\App\Entities\Managers;

use Meetee\Libs\Database\Database;

abstract class EntityManager
{
	protected Database $database;

	abstract public function select(string $table, array $props): void;

	abstract public function create(Entity $entity): void;

	abstract public function update(Entity $entity): void;

	abstract public function softDelete(Entity $entity): void;

	abstract public function delete(Entity $entity): void;
}