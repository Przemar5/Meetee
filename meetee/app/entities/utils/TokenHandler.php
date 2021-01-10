<?php

namespace Meetee\App\Entities\Utils;

use Meetee\App\Entities\Token;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;
use Meetee\Libs\Security\AuthFacade;

class TokenHandler
{
	public static function validate(string $name): bool
	{
		if (!self::validateInitiallyFromRequest($name))
			return false;

		if (!self::validateWithDatabaseAndDelete($name))
			return false;

		return true;
	}

	private static function validateInitiallyFromRequest(string $name, string $method = 'POST'): bool
	{
		$method = self::getGlobalByMethod($method);
		$validator = new TokenValidator();
		$values = [
			'name' => $name,
			'value' => $method[$name],
			'user_id' => AuthFacade::getUserId(),
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

	private static function validateWithDatabaseAndDelete(string $name): bool
	{
		$token = TokenFactory::getFromRequest($name);

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