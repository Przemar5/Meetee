<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Country;
use Meetee\App\Entities\Group;
use Meetee\App\Entities\GroupRole;
use Meetee\App\Entities\Pivots\UserPost;
use Meetee\Libs\Http\Routing\Data\Route;
use Meetee\Libs\Security\AuthenticationFacade;
use Meetee\App\Entities\Traits\Timestamps;
use Meetee\App\Entities\Traits\SoftDelete;
use Meetee\App\Entities\Factories\RoleFactory;
use Meetee\Libs\Utils\StringableDate;
use Meetee\Libs\Database\Tables\Pivots\GroupUserRoleTable;
use Meetee\Libs\Database\Tables\GroupRoleTable;

class User extends Entity
{
	use Timestamps;
	use SoftDelete;

	public string $login;
	public string $name;
	public ?string $secondName;
	public string $surname;
	public string $email;
	public string $secondaryEmail;
	public ?Country $country;
	public ?string $city;
	public ?string $zipCode;
	public string $gender;
	public string $password;
	public ?string $profile = null;
	public bool $verified = false;
	protected \DateTime $birth;
	protected \DateTime $sessionExpiry;
	protected array $roles = [];
	protected array $friends = [];
	protected bool $updatePivots = false;

	public function hasAccess(Route $route): bool
	{
		$common = array_intersect($route->getAccess(), $this->roles);

		return !empty($common);
	}

	public function addFriend(User $user): void
	{
		$this->friends[] = $user;
		$this->updatePivots = true;
	}

	public function removeFriend(User $user): void
	{
		$this->friends = array_filter($this->friends, fn($u) => $u !== $u);
		$this->updatePivots = true;
	}

	public function setFriends(array $friends): void
	{
		$this->friends = $friends;
	}

	public function getFriends(): array
	{
		return $this->friends;
	}

	public function hasFriend(User $user): bool
	{
		return in_array($user, $this->friends);
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

	public function setSessionExpiry($sessionExpiry = null): void
	{
		if (is_null($sessionExpiry))
			$this->sessionExpiry = null;
		elseif (is_string($sessionExpiry))
			$this->sessionExpiry = new \DateTime($sessionExpiry);
		elseif ($sessionExpiry instanceof \DateTime)
			$this->sessionExpiry = $sessionExpiry;
		else
			throw new \Exception(sprintf(
				"Birth date must be type string or DateTime object, '%s' given.",
				gettype($sessionExpiry)));
	}

	public function setSessionExpirySecondsFromNow(int $seconds): void
	{
		$this->sessionExpiry = new \DateTime('@'. time()+$seconds);
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

	public function getBirth(): \DateTime
	{
		return $this->birth;
	}

	public function getSessionExpiry(): \DateTime
	{
		return $this->sessionExpiry;
	}

	public function getRoles(): array
	{
		return $this->roles ?? [];
	}

	public function getPosts(): ?array
	{
		$pivot = new UserPost();

		return $pivot->postsForUser($this);
	}

	public function hasToUpdatePivots(): bool
	{
		return $this->updatePivots;
	}

	public function isInGroup(Group $group): bool
	{
		$table = new GroupUserRoleTable();

		return $table->isUserInGroup($this, $group);
	}

	public function hasRoleIdInGroup(int $roleId, Group $group): bool
	{
		$table = new GroupUserRoleTable();

		return $table->userHasRoleIdInGroup($this, $roleId, $group);
	}

	public function hasRequestedRoleIdInGroup(int $roleId, Group $group): bool
	{
		$table = new GroupUserRoleTable();

		return $table->userHasRequestedForRoleIdInGroup($this, $roleId, $group);
	}

	public function hasRoleInGroup($role, Group $group): bool
	{
		$table = new GroupRoleTable();

		if (is_string($role))
			$role = $table->findOneBy(['name' => $role]);
		
		else if (!is_a($role, GroupRole::class))
			return false;

		return $this->hasRoleIdInGroup($role->getId(), $group);
	}

	public function hasRequestedRoleInGroup($role, Group $group): bool
	{
		$table = new GroupRoleTable();

		if (is_string($role))
			$role = $table->findOneBy(['name' => $role]);
		
		else if (!is_a($role, GroupRole::class))
			return false;

		return $this->hasRequestedRoleIdInGroup($role->getId(), $group);
	}
}