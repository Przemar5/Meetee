<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\Token;

class TokenFactory
{
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

	public static function getCsrfTokenFromRequest(): Token
	{
		//
	}
}