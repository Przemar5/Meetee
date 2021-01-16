<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\Role;

class RoleFactory
{
	public static function createGuestRole(): Role
	{
		$role = new Role();
		$role->name = 'GUEST';

		return $role;
	}

	public static function createUserRole(): Role
	{
		$role = new Role();
		$role->setId(1);
		$role->name = 'USER';

		return $role;
	}

	public static function createVerifiedRole(): Role
	{
		$role = new Role();
		$role->setId(2);
		$role->name = 'VERIFIED';

		return $role;
	}

	public static function createAdminRole(): Role
	{
		$role = new Role();
		$role->setId(3);
		$role->name = 'ADMIN';

		return $role;
	}

	public static function createOwnerRole(): Role
	{
		$role = new Role();
		$role->setId(4);
		$role->name = 'OWNER';

		return $role;
	}

	public static function createBannedRole(): Role
	{
		$role = new Role();
		$role->setId(5);
		$role->name = 'BANNED';

		return $role;
	}
}