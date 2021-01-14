<?php

namespace Meetee\Libs\Security;

use Meetee\App\Entities\User;
use Meetee\App\Entities\Token;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\NullUser;
use Meetee\Libs\Storage\Session;
use Meetee\Libs\Utils\RandomStringGenerator;
use Meetee\Libs\Database\Tables\UserTable;

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

	public static function login(User $user): void
	{
		Session::set('user_id', $user->getId());
	}

	public static function logout(): void
	{
		Session::unset('user_id');
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
}