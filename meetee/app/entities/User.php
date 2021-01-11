<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Http\Routing\Data\Route;
use Meetee\Libs\Security\AuthenticationFacade;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\App\Entities\Traits\Timestamps;
use Meetee\App\Entities\Traits\SoftDelete;

class User extends Entity
{
	use Timestamps;
	use SoftDelete;

	protected ?int $id = null;
	protected string $login;
	protected string $name;
	protected string $surname;
	protected string $email;
	protected \DateTime $birth;
	protected string $password;
	protected array $roles = [];

	public function __construct()
	{
		parent::__construct(new UserTable());
	}

	public function save(): void
	{
		$this->updatedAt = new \DateTime();
		$this->table->save($this);
	}

	public static function findByLogin(string $login): ?self
	{
		$user = new self();

		return $user->table->findByLogin($login);
	}

	public function hasAccess(Route $route): bool
	{
		$common = array_intersect($route->getAccess(), $this->roles);

		return !empty($common);
	}

	public function setId(int $id): void
	{
		if (is_null($this->id))
			$this->id = $id;
	}

	public function setLogin(string $login): void
	{
		$this->login = $login;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function setSurname(string $surname): void
	{
		$this->surname = $surname;
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

	public function setPassword(string $password): void
	{
		$this->password = $password;
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

	public function getLogin(): string
	{
		return $this->login;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getSurname(): string
	{
		return $this->surname;
	}

	public function getBirth(): \DateTime
	{
		return $this->birth;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function getRoles(): array
	{
		return $this->roles ?? [];
	}
}