<?php

namespace Meetee\App\Entities\Pivots;

use Meetee\App\Entities\Pivots\Pivot;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Role;

class UserRole extends Pivot
{
	private User $user;
	private Role $role;

	public function setUser(User $user): void
	{
		$this->user = $user;
	}

	public function setRole(User $role): void
	{
		$this->role = $role;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function getRole(): Role
	{
		return $this->role;
	}
}