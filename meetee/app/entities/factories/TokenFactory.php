<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\Token;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\App\Entities\User;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;
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
		$token->name = $name;
		$token->value = RandomStringGenerator::generate(64);
		$token->userId = $user->getId() ?? 0;
		$token->setExpiry($delay);
		$token->save();

		return $token;
	}

	public static function getFromRequest(
		string $name, 
		?User $user = null
	): ?Token
	{
		if (!isset($_POST[$name]))
			return null;

		if (is_null($user))
			$user = AuthFacade::getUser();
		
		$token = new Token();
		$token->name = $name;
		$token->value = $_POST[$name];
		$token->userId = $user->getId() ?? 0;

		return $token;
	}

	public static function popIfRequestValid(
		string $name, 
		bool $ignoreUserId = false
	): ?Token
	{
		$token = static::getFromRequest($name);
		$validator = new TokenValidator();
		$valid = $validator->run([
			'name' => $token->name,
			'value' => $token->value,
			'user_id' => $token->userId,
		]);

		if (!$valid)
			return null;

		$data = [];
		$data['name'] = $token->name;
		$data['value'] = $token->value;

		if (!$ignoreUserId)
			$data['user_id'] = $token->userId;

		$tokenTable = new TokenTable();

		return $tokenTable->popValidBy($data);
	}

	public static function generateResetPasswordEmailToken(User $user): ?Token
	{
		return static::generate('forgot_password_email_token', $user);
	}

	public static function generateRegistrationConfirmEmailToken(User $user): ?Token
	{
		return static::generate('registration_confirm_email_token', $user);
	}

	public static function generateCsrfRegistrationResendToken(User $user): ?Token
	{
		return static::generate('registration_confirm_email_token', $user);
	}

	public static function popRegistrationConfirmEmailTokenIfRequestValid(): ?Token
	{
		return static::popIfRequestValid('registration_confirm_email_token', true);
	}

	// public static function popRegistrationConfirmEmailTokenIfRequestValid(): ?Token
	// {
	// 	return static::popIfRequestValid('registration_confirm_email_token', true);
	// }
}