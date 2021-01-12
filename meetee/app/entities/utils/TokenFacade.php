<?php

namespace Meetee\App\Entities\Utils;

use Meetee\App\Entities\Token;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;
use Meetee\App\Entities\Factories\TokenFactory;

class TokenFacade
{
	public static function getIfRequestValid(string $name, bool $ignoreUserId): ?Token
	{
		$token = TokenFactory::getFromRequest($name);


		if (!static::validate($token))
			return null;

		$data = [];
		$data['name'] = $token->getName();
		$data['value'] = $token->getValue();

		if (!$ignoreUserId)
			$data['user_id'] = $token->getUserId();

		// return $token->pop();
	}

	public static function validate(
		string $name, 
		?string $method = 'POST'
	): bool
	{
		$token = TokenFactory::getFromRequest($name);
		$method = self::getGlobalByMethod($method);
		$validator = new TokenValidator();
		$values = [
			'name' => $token->getName(),
			'value' => $token->getValue(),
			'user_id' => $token->getUserId(),
		];

		return $validator->run($values);
	}

	private static function getGlobalByMethod(string $method): array
	{
		switch ($method) {
			case 'GET': 	return $_GET;
			case 'POST': 	return $_POST;
			default: 		return $_GET;
		}
	}
}