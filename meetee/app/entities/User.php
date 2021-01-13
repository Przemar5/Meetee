<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Http\Routing\Data\Route;
use Meetee\Libs\Security\AuthenticationFacade;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\App\Entities\Traits\Timestamps;
use Meetee\App\Entities\Traits\SoftDelete;
use Meetee\App\Entities\Factories\RoleFactory;

class User extends Entity
{
	use Timestamps;
	use SoftDelete;

	protected ?int $id = null;
	public string $login;
	public string $name;
	public string $surname;
	public string $email;
	public string $password;
	public bool $verified = false;
	protected \DateTime $birth;
	protected array $roles = [];

	public function __construct()
	{
		parent::__construct(new UserTable());
	}

	public function hasAccess(Route $route): bool
	{
		$common = array_intersect($route->getAccess(), $this->roles);

		return !empty($common);
	}

	public function verify(): void
	{
		$this->verified = true;
		$this->addRole(RoleFactory::createVerifiedRole());
		$this->table->save($this);
	}

	public function setId(int $id): void
	{
		if (is_null($this->id))
			$this->id = $id;
	}

	public function setBirth($birth): void
	{
		if (is_string($birth))
			$this->birth = new \DateTime($birth);
		elseif ($birth instanceof \DateTime)
			$this->birth = $birth;
		else
			throw new \Exception(sprintf(
				"Birth date must be type string or DateTime object, '%s' given.",
				gettype($birth)));
	}

	public function setRoles(array $roles): void
	{
		$this->roles = array_unique($roles);
	}

	public function addRole(Role $role): void
	{
		if (!in_array($role, $this->roles))
			$this->roles[] = $role;
	}

	public function removeRole($role): void
	{
		$this->roles = array_filter($this->roles, fn($r) => $r != $role);
	}

	public function hasRole(string $role): bool
	{
		return in_array($role, $this->roles);
	}

	public function delete(): void
	{
		// $this->deleted = true;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getBirth(): \DateTime
	{
		return $this->birth;
	}

	public function getRoles(): array
	{
		return $this->roles ?? [];
	}
}