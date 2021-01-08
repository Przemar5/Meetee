<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Http\Routing\Data\Route;
use Meetee\Libs\Security\AuthenticationFacade;
// use Meetee\Libs\Database\DatabaseMediator;

class User extends Entity
{
	private string $table = 'users';
	private ?int $id = null;
	private string $username;
	private string $password;
	private array $roles;

	public function hasAccess(Route $route): bool
	{
		$common = array_intersect($route->getAccess(), $this->roles);

		return !empty($common);
	}

	public function login(): void
	{
		//
	}

	public function logout(): void
	{
		//
	}

	public function softDelete(): void
	{
		//
	}

	public function delete(): void
	{
		//
	}

	public function setId(int $id): void
	{
		$this->id = $id;
	}

	public function setUsername(string $username): void
	{
		$this->username = $username;
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	public function setRoles(array $roles): void
	{
		$this->roles = $roles;
	}

	public function addRole(string $role): void
	{
		$this->roles[] = $role;
	}

	public function removeRole(string $role): void
	{
		$this->roles = array_filter(fn($r) => $r != $role, $this->roles);
	}

	public function hasRole(string $role): bool
	{
		return in_array($role, $this->roles);
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function getRole(): string
	{
		return $this->role;
	}
}