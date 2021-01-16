<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\Token;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\App\Entities\User;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;
use Meetee\Libs\Utils\RandomStringGenerator;
use Meetee\Libs\Http\CurrentRequestFacade;

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
		string $name
	): ?Token
	{
		if (!isset($_POST[$name]))
			return null;
		
		$token = new Token();
		$token->name = $name;
		$token->value = $_POST[$name];

		return $token;
	}

	public static function popIfRequestValidByNameAndUser(
		string $name,
		?User $user = null
	): ?Token
	{
		$token = static::getFromRequest($name);

		if (!$token || !static::tokenValidates($token))
			return null;

		$data = static::tokenToData($token, $user);
		$tokenTable = new TokenTable();

		return $tokenTable->popValidBy($data);
	}

	private static function tokenValidates(Token $token): bool
	{
		$validator = new TokenValidator();
		
		return $validator->run([
			'name' => $token->name,
			'value' => $token->value,
		]);
	}

	private static function tokenToArray(Token $token, ?User $user = null): array
	{
		$data = [];
		$data['name'] = $token->name;
		$data['value'] = $token->value;
		
		if ($user) {
			$userId = (!is_null($user) && !empty($user->getId())) 
				? $user->getId() : 0;
			$data['user_id'] = $userId;
		}

		return $data;
	}

	public static function popIfAjaxRequestValidByNameAndUser(
		string $name, 
		User $user
	): ?Token
	{

	}

	public static function getFromAjax(string $name): ?Token
	{
		$data = CurrentRequestFacade::getAjax();

		if (isset($data[$name])) {
			$token = new Token();
			$token->name = $name;
			$token->value = $data[$name];

			return $token;
		}

		return null;
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
		return static::popIfRequestValidByNameAndUser(
			'registration_email_verify');
	}

	// public static function popRegistrationConfirmEmailTokenIfRequestValid(): ?Token
	// {
	// 	return static::popIfRequestValidByNameAndUser('registration_confirm_email_token');
	// }
}