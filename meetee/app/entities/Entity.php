<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Managers\EntityManager;

abstract class Entity implements SplSubject
{
	protected ?EntityManager $manager;

	public function setManager(EntityManager $manager): void
	{
		$this->manager = $manager;
	}

	public save(): void
	{
		$this->manager->save($this);
	}
	// abstract public static function find(int $id): static;

	// abstract public function save(): void;
}