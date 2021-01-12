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

	public static function popIfRequestValid(
		string $name, 
		bool $ignoreUserId = false
	): ?Token
	{
		$token = static::getFromRequest($name);
		$validator = new TokenValidator();
		$valid = $validator->run([
			'name' => $token->getName(),
			'value' => $token->getValue(),
			'user_id' => $token->getUserId(),
		]);

		if (!$valid)
			return null;

		$data = [];
		$data['name'] = $token->getName();
		$data['value'] = $token->getValue();

		if (!$ignoreUserId)
			$data['user_id'] = $token->getUserId();

		$tokenTable = new TokenTable();

		return $tokenTable->popValidWhere($data);
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