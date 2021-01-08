<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\Managers\EntityManager;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;

abstract class EntityFactory
{
	public function newUser(): User
	{
		// $table = new UserTable();
		// $user = new User();

		// $user->setEntityManager($manager);

		// return $user;
	}

	public function createUserFromFetchedData(array $data): User
	{

	}
} 