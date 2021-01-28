<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;

class GroupController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_token';
	private array $errors = [];

	public function page(): void
	{
		$user = AuthFacade::getUser();
		$token = TokenFactory::generate(self::$tokenName, $user);

		$this->render('settings/account', [
			'user' => $user,
			'token' => $token,
		]);
	}
}