<?php

namespace Meetee\App\Entities\Managers;

use Meetee\Libs\Database\DatabaseMediator;

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

	public function save(Entity $entity): void
	{
		
	}
}