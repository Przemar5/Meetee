<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\Token;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Utils\RandomStringGenerator;
use Meetee\Libs\Http\CurrentRequestFacade;
use Meetee\Libs\Storage\Session;

class TokenFactory
{
	public static function generate(
		string $name, 
		?User $user = null,
		?string $delay = '+2 hours'
	): Token
	{
		if (is_null($user))
			$user = AuthFacade::getUser();
		
		$token = new Token();
		$token->name = $name;
		$token->value = RandomStringGenerator::generate(64);
		$token->userId = $user->getId() ?? 0;
		$token->setExpiry($delay);
		$table = new TokenTable();
		$table->save($token);

		return $token;
	}

	public static function getFromArray(
		string $name,
		array $array
	): ?Token
	{
		if (!isset($array[$name]))
			return null;
		
		$token = new Token();
		$token->name = $name;
		$token->value = $array[$name];

		return $token;
	}

	public static function getFromPostRequest(string $name): ?Token
	{
		return static::getFromArray($name, $_POST);
	}

	public static function getFromAjax(string $name): ?Token
	{
		$data = CurrentRequestFacade::getAjax();

		return static::getFromArray($name, $data);
	}
}