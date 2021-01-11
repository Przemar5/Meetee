<?php

namespace Meetee\Libs\Security;

use Meetee\App\Entities\User;
use Meetee\App\Entities\Token;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\NullUser;
use Meetee\Libs\Storage\Session;
use Meetee\Libs\Utils\RandomStringGenerator;

class AuthFacade
{
	public static function getUser(): User
	{
		$id = Session::get('Bs7Kf05jenMIft42Aj8');

		if (!preg_match('/^[1-9][0-9]*$/', $id))
			return new NullUser();

		return User::find($id) ?? new NullUser();
	}

	public static function getUserId(): int
	{
		$user = self::getUser();

		return $user->getId() ?? 0;
	} 

	public static function checkToken(Token $token): bool
	{
		return $token->isValid();
	}

	public static function login(User $user): void
	{
		Session::set('user_id', $user->getId());

		echo Session::get('user_id');
	}

	public static function getLoggedUser(): ?User
	{
		$id = Session::get('user_id');

		if (!preg_match('/^[1-9][0-9]*$/', $id))
			return null;

		return User::find($id);
	}
}