<?php

namespace Meetee\App\Entities\Utils;

use Meetee\App\Entities\Token;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;
use Meetee\Libs\Security\AuthFacade;

class TokenHandler
{
	public function getTokenUserId(): int
	{
		TokenFactory::getFromRequest()
	}

	public static function validate(
		string $name, 
		?User $user = null, 
		?string $method = 'POST'
	): bool
	{
		if (!self::validateInitiallyFromRequest($name, $user, $method))
			return false;

		if (!self::validateWithDatabaseAndDelete($name, $user))
			return false;

		return true;
	}

	public static function validateInitiallyFromRequest(
		string $name, 
		?User $user = null,
		?string $method = 'POST' 
	): bool
	{
		if (is_null($user))
			$user = AuthFacade::getUser();

		$method = self::getGlobalByMethod($method);
		$validator = new TokenValidator();
		$values = [
			'name' => $name,
			'value' => $method[$name],
			'user_id' => $user->getId(),
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

	public static function validateWithDatabaseAndDelete(
		string $name, 
		?User $user = null
	): bool
	{
		if (is_null($user))
			$user = AuthFacade::getUser();

		$token = TokenFactory::getFromRequest($name, $user);

		if (is_null($token))
			return false;

		$tokenTable = new TokenTable();
		$fromTable = $tokenTable->getValidWithoutId($token);
		
		if (!$fromTable)
			return false;

		$fromTable->delete();

		return true;
	}
}