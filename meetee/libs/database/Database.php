<?php

namespace Meetee\Libs\Database;

interface Database
{
	public function in(string $table): self;

	public function select(array $values): self;

	public function create(array $values): self;

	public function update(array $values): self;

	public function delete(array $ids): self;

	public function where(array $conditions): self;

	public function limit(int $limit): self;

	public function offset(int $offset): self;

	public function getResult(): mixed;
}