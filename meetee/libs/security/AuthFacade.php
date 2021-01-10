<?php

namespace Meetee\Libs\Security;

use Meetee\App\Entities\User;
use Meetee\App\Entities\Token;
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

	public static function generateCsrfToken(): Token
	{
		$user = self::getUser();
		$token = new Token();
		$token->setName('csrf_token');
		$token->setValue(RandomStringGenerator::generate(64));
		$token->setUserId($user->getId() ?? 0);
		$token->setExpires(new \DateTime('+2 hour'));
		$token->save();

		return $token;
	}

	public static function checkCsrfToken(Token $token): bool
	{
		Token::generateFromRequest();
		return $token->isValid($token);
	}
}