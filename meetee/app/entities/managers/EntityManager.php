<?php

namespace Meetee\App\Entities\Managers;

use Meetee\Libs\Database\DatabaseMediator;
use Meetee\App\Entities\User;

class EntityManager
{
	private self $instance;

	private function __construct()
	{
		//
	}

	public static function getInstance(): self
	{
		if (!$this->instance) 
			$this->instance = new self();

		return $this->instance;
	}

	// public function save(Entity $entity): void
	// {
	// 	if ($entity instanceof User::class)
			
	// }
}