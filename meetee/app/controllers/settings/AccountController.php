<?php

namespace Meetee\App\Controllers\Settings;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Http\CurrentRequestFacade;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;

class AccountController extends ControllerTemplate
{
	private static string $tokenName = 'settings_account_csrf_token';

	public function page(): void
	{
		$user = AuthFacade::getUser();
		$token = TokenFactory::generate(self::$tokenName, $user);

		$this->render('settings/account', [
			'user' => $user,
			'token' => $token,
		]);
	}

	public function process(): void
	{
		$user = AuthFacade::getUser();
		$token = TokenFactory::getFromAjax(self::$tokenName);
		$validator = new TokenValidator();
		$data = [
			'name' => $token->name,
			'value' => $token->value,
		];
		
		if (!$validator->run($data))
			die;

		$table = new TokenTable();
		$token = $table->getValidBy($data);

		if ($token->userId !== $user->getId())
			die;

		$request = CurrentRequestFacade::getAjax();
		$this->dispatchRequestHandling($request, $user);
	}

	private function dispatchRequestHandling(
		array $data, 
		User $user
	): void
	{
		$data = [
			'login' => $user->login,
			'email' => $user->email,
			'name' => $user->name,
			'surname' => $user->surname,
			'birth' => $user->getBirth(),
		];
		echo json_encode($data);
	}

	private function trimValues(): void
	{
		foreach ($_POST as $key => $value)
			if (is_string($value))
				$_POST[$key] = trim($value);
	}
}