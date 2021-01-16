<?php

namespace Meetee\App\Controllers\Settings;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Http\CurrentRequestFacade;

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
		$this->returnToPageIfTokenInvalid(self::$tokenName, $user);

		$request = CurrentRequestFacade::getAjax();

		$token = TokenFactory::getFromAjax(self::$tokenName);

		dd($token);

		$data = [
			'login' => $user->login,
			'email' => $user->email,
			'name' => $user->name,
			'surname' => $user->surname,
			'birth' => $user->getBirth(),
		];

		echo json_encode($output);
		die;


		// try {
		// 	$this->trimValues();
		// 	$this->returnToPageIfTokenInvalid(self::$tokenName);
		// 	$this->returnToPageWithErrorsIfFormDataInvalid();
			
		// 	$this->successfulRequestValidationEvent();
		// }
		// catch (\Exception $e) {
		// 	die($e->getMessage());
		// }
	}

	private function trimValues(): void
	{
		foreach ($_POST as $key => $value)
			if (is_string($value))
				$_POST[$key] = trim($value);
	}

	private function returnToPageIfTokenInvalid(string $name, User $user): void
	{
		// if (!TokenFactory::popIfAjaxRequestValidByNameAndUser($name, $user)) {
		// 	die;
		// }
	}

	// private function returnToPageWithErrorsIfFormDataInvalid(): void
	// {
	// 	$form = new LoginForm();

	// 	if (!$form->validate())
	// 		$this->returnPageWithError('Invalid credentials. Please try again.');

	// 	$table = new UserTable();
	// 	$this->user = $table->findOneBy([
	// 		'email' => $_POST['email'],
	// 	]);
		
	// 	if (!$this->user)
	// 		$this->returnPageWithError('Invalid credentials. Please try again.');

	// 	if (!Hash::verify($_POST['password'], $this->user->password))
	// 		$this->returnPageWithError('Invalid credentials. Please try again.');

	// 	if (!$this->user->verified)
	// 		$this->returnPageWithError(
	// 			$this->getResendVerificationEmailMessage());
	// }
}