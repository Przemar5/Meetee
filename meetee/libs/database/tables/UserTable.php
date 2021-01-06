<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\Table;
use Meetee\App\Entities\User;

class UserTable extends Table
{
	public function __construct()
	{
		parent::__construct('user');
	}

	public function select(int $id): ?User
	{
		
	}

	public function create(Entity $entity): void;
	
	public function update(Entity $entity): void;
	
	public function delete(Entity $entity): void;
}