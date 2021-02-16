<?php

namespace Meetee\Libs\Security;

use Meetee\App\Entities\User;
use Meetee\App\Entities\Token;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\NullUser;
use Meetee\Libs\Storage\Session;
use Meetee\Libs\Storage\Cookie;
use Meetee\Libs\Utils\RandomStringGenerator;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\App\Entities\Factories\RoleFactory;

class AuthFacade
{
	public static function getUser(): User
	{
		$id = Session::get('user_id');

		if (!preg_match('/^[1-9][0-9]*$/', $id))
			return new NullUser();

		$table = new UserTable();

		return $table->find($id) ?? new NullUser();
	}

	public static function getUserId(): int
	{
		$user = self::getUser();

		return $user->getId();
	} 

	public static function checkToken(Token $token): bool
	{
		return $token->isValid();
	}

	public static function verifyUser(User $user): void
	{
		$user->verified = true;
		$user->addRole(RoleFactory::createVerifiedRole());
		$table = new UserTable();
		$table->save($user);
	}

	public static function login(User $user): void
	{
		Session::set('user_id', $user->getId());
		Session::set('user_last_activity_time', time());
		setcookie('user_last_activity_time', time() + SESSION_LIFETIME, 
				time() + SESSION_LIFETIME);
	}

	public static function logout(): void
	{
		Session::unset('user_id');
		Session::unset('user_last_activity_time');
		Cookie::unset('user_last_activity_time');
	}

	public static function getLoggedUser(): ?User
	{
		$id = Session::get('user_id');

		if (!preg_match('/^[1-9][0-9]*$/', $id))
			return null;

		$userTable = new UserTable();

		return $userTable->find($id);
	}

	public static function isGranted(string $role): bool
	{
		$user = self::getUser();

		return $user->hasRole($role);
	}

	public static function isLogged(?User $user = null): bool
	{
		if (!$user && !$user = static::getLoggedUser())
			return false;

		return Session::isset('user_id');
	}
}