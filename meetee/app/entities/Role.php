<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Database\Tables\RoleTable;

class Role extends Entity
{
	private ?int $id;
	private string $name;

	public function __construct()
	{
		parent::__construct(new RoleTable());
	}

	public function __toString()
	{
		return $this->name;
	}

	public static function getGuestRole(): self
	{
		$role = new self();
		$role->setId(1);
		$role->setName('GUEST');

		return $role;
	}

	public static function getUserRole(): self
	{
		$role = new self();
		$role->setId(2);
		$role->setName('USER');

		return $role;
	}

	public static function getAdminRole(): self
	{
		$role = new self();
		$role->setId(3);
		$role->setName('ADMIN');

		return $role;
	}

	public function setId(int $id): void
	{
		$this->id = $id;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}
}