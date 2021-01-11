<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\Token;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Utils\RandomStringGenerator;

class TokenFactory
{
	public static function generate(
		string $name, 
		?string $delay = '+2 hours'
	): Token
	{
		$user = AuthFacade::getUser();
		$token = new Token();
		$token->setName($name);
		$token->setValue(RandomStringGenerator::generate(64));
		$token->setUserId($user->getId() ?? 0);
		$token->setExpiry(new \DateTime($delay));
		$token->save();

		return $token;
	}

	public static function getFromRequest(string $name): ?Token
	{
		if ($_POST[$name]) {
			$user = AuthFacade::getUser();
			$token = new Token();
			$token->setName($name);
			$token->setValue($_POST[$name]);
			$token->setUserId($user->getId() ?? 0);

			return $token;
		}
	}
}