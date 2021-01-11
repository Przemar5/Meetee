<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\Token;
use Meetee\App\Entities\User;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Utils\RandomStringGenerator;

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
		$token->setName($name);
		$token->setValue(RandomStringGenerator::generate(64));
		$token->setUserId($user->getId() ?? 0);
		$token->setExpiry(new \DateTime($delay));
		$token->save();

		return $token;
	}

	public static function getFromRequest(
		string $name, 
		?User $user = null
	): ?Token
	{
		if ($_POST[$name]) {
			if (is_null($user))
				$user = AuthFacade::getUser();
			
			$token = new Token();
			$token->setName($name);
			$token->setValue($_POST[$name]);
			$token->setUserId($user->getId() ?? 0);

			return $token;
		}
	}

	public static function getFromDatabaseByName(
		string $name, 
		?User $user = null
	): ?Token
	{
		$token = static::getFromRequest($name);
		
		if (is_null($token))
			return null;

		$token = static::getFromDatabase($token->getName(), $token->getValue());
		
		if (is_null($token))
			return null;

		
	}

	public static function getFromDatabase(
		string $name,
		string $value
	): ?Token
	{
		return Token::findOneWhere([
			'name' => $name,
			'value' => $value,
		]);
	}

	public static function generateResetPasswordEmailToken(User $user): ?Token
	{
		return static::generate('forgot_password_email_token', $user);
	}

	public static function generateRegistrationConfirmEmailToken(User $user): ?Token
	{
		return static::generate('registration_confirm_email_token', $user);
	}
}