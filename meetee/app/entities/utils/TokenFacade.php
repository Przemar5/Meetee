<?php

namespace Meetee\App\Entities\Utils;

use Meetee\App\Entities\User;
use Meetee\App\Entities\Token;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\Libs\Security\AuthFacade;

class TokenFacade
{
	public static function getTokenIfValidByNameAndUser(
		string $name,
		?User $user = null
	): ?Token
	{
		$token = TokenFactory::getFromAjax($name);
		
		if (!$token || !static::tokenValidates($token))
			return null;

		$data = static::serializeToken($token, $user);
		$table = new TokenTable();
		$token = $table->getValidByToken($token);

		if ($user && !empty($token->userId) && 
			$token->userId !== $user->getId())
			return null;

		return $token;
	}

	public static function popTokenIfValidByName(string $name): ?Token
	{
		return static::popTokenIfValidByNameAndUser($name, null);
	}

	public static function popTokenIfValidByNameAndUser(
		string $name,
		?User $user = null
	): ?Token
	{
		$token = TokenFactory::getFromPostRequest($name);

		if (!$token || !static::tokenValidates($token))
			return null;

		$data = static::serializeToken($token, $user);
		$table = new TokenTable();

		if ($user && !empty($token->userId) && 
			$token->userId !== $user->getId())
			return null;

		return $table->popValidBy($data);
	}

	public static function serializeToken(Token $token, ?User $user = null): array
	{
		$data = [];
		$data['name'] = $token->name;
		$data['value'] = $token->value;
		
		if ($user)
			$data['user_id'] = (!empty($user->getId())) ? $user->getId() : 0;

		return $data;
	}

	public static function getTokenIfValidByNameAndCurrentUser(
		string $name
	): ?Token
	{
		return static::getTokenIfValidByNameAndUser($name, AuthFacade::getUser());
	}

	public static function tokenValidates(Token $token): bool
	{
		$validator = new TokenValidator();
		$data = [
			'name' => $token->name,
			'value' => $token->value,
		];

		return $validator->run($data);
	}

	public static function isTokenValidForUser(Token $token, User $user): bool
	{
		if (!static::tokenValidates($token))
			return false;

		$data = static::serializeToken($token);
		$token = static::getTokenFromDatabaseBy($data);

		return $token->userId === $user->getId();
	}

	private static function isRequestTokenValidForUser(
		string $name,
		User $user,
		callable $func
	): bool
	{
		$token = $func($name);

		if (!$token)
			return false;

		return static::isTokenValidForUser($token, $user);
	}

	public static function isAjaxRequestTokenValidForUser(
		string $name, 
		User $user
	): bool
	{
		return static::isRequestTokenValidForUser(
			$name, $user, [TokenFactory::class, 'getFromAjax']);
	}

	public static function isPostRequestTokenValidForUser(
		string $name, 
		User $user
	): bool
	{	
		return static::isRequestTokenValidForUser(
			$name, $user, [TokenFactory::class, 'getFromPostRequest']);
	}

	private static function getTokenFromDatabaseBy(array $data): ?Token
	{
		$table = new TokenTable();
		
		return $table->getValidBy($data);
	}

	// generate
	// get token from array 
	// get token from array by user
	// get token from array by logged user

	// get token from get request
	// get token from get request for user
	// get token from get request for logged user
	// get token from post request
	// get token from post request for user
	// get token from post request for logged user
	// get token from ajax request
	// get token from ajax request for user
	// get token from ajax request for logged user


	// pop token from request
	// pop token from request for user
	// pop token from request for logged user


}