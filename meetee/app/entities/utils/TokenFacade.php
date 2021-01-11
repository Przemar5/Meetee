<?php

namespace Meetee\App\Entities\Utils;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenHandler;

class TokeFacade
{
	public static function validateAndGetUser(
		string $name, 
		?User $user = null
	): ?User
	{
		if (is_null($user))
			$user = AuthFacade::getUser();

		$token = TokenFactory::getFromRequest($name, $user);

		if (is_null($token))
			return null;

		if (!TokenHandler::validateInitiallyFromRequest())
			return null;
		
		$token = Token::findOneWhere([
			'name' => $name,
			'value' => $token->getValue(),
		]);

		if (is_null($token))
			return null;
		
		$token->delete();

		return true;
	}

	public static function getTokenIfValid(string $name): ?Token
	{
		$token = TokenFactory::getFromRequest($name);
		$token->completeIfValid();

		if (empty($token->getId()))
			return null;

		$token->getName();

		$token->
	}
}