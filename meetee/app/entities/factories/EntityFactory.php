<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\Managers\EntityManager;
use Meetee\App\Entities\Entity;

abstract class EntityFactory
{
	public function newUser(): User
	{
		$manager = EntityManager::getInstance();
		$user = new User();
		$user->setEntityManager($manager);

		return $user;
	}

	public function createUserFromFetchedData(array $data): User
	{
		
	}
} 