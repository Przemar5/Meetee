<?php

namespace Meetee\Libs\Database\Tables;

interface Table
{
	public function findOne(int $id): ?Entity;

	public function findOneBy(array $conditions): ?Entity;
	
	public function findManyBy(array $conditions): array;
	
	public function save(Entity $entity): void;
	
	public function insert(Entity $entity): void;

	public function update(Entity $entity): void;

	public function delete(int $id): void;

	public function deleteBy(array $conditions): void;

	public function pop(int $id): ?Entity;

	public function popBy(array $conditions): ?Entity;
}