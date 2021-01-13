<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Factories\UserFactory;
use Meetee\App\Forms\RegistrationForm;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\App\Emails\EmailFacade;
use Meetee\Libs\View\Utils\Notification;

class RegistrationController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_registration_token';
	private array $errors = [];

	public function page(): void
	{
		$token = TokenFactory::generate(self::$tokenName);

		$this->render('auth/register', [
			'token' => $token,
			'errors' => $this->errors,
		]);
	}

	public function process(): void
	{
		try {
			$this->trimValues();
			$this->returnToPageIfTokenInvalid(self::$tokenName);
			$this->returnToPageWithErrorsIfFormDataInvalid();

			$this->successfulRegisterRequestValidationEvent();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function redirect(string $route): void
	{
		$router = RouterFactory::createComplete();
		$router->redirectTo($route);
	}

	private function returnToPageIfTokenInvalid(string $name): void
	{
		if (!TokenFactory::popIfRequestValid($name)) {
			$this->page();
			die;
		}
	}

	private function returnToPageWithErrorsIfFormDataInvalid(): void
	{
		$form = new RegistrationForm();

		if (!$form->validate()) {
			$this->errors = $form->getErrors();
			$this->page();
			die;
		}
	}

	private function trimValues(): void
	{
		foreach ($_POST as $key => $value)
			if (is_string($value))
				$_POST[$key] = trim($value);
	}

	private function successfulRegisterRequestValidationEvent(): void
	{
		$user = UserFactory::createAndSaveUserFromPostRequest();
		EmailFacade::sendRegistrationConfirmEmail(
			'registration_confirm_email_token', $user);
		Notification::addSuccess('Check Your mailbox for an activation email!');
		$this->redirect('login_page');
	}

	public function verify(): void
	{
		try {
			// $token = TokenFactory::popRegistrationConfirmEmailTokenIfRequestValid();
			$token = TokenFactory::popIfRequestValid('registration_confirm_email_token', true);

			if (!$token)
				$this->redirect('registration_page');

			$table = new UserTable();

			if (!$user = $table->find($token->userId))
				$this->redirect('registration_page');

			$this->successfulVerificationRequestValidationEvent($user);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function successfulVerificationRequestValidationEvent(
		User $user
	): void
	{
		$user->verify();
		Notification::addSuccess(
			'Your account was activated. Now You can login!');
		$this->redirect('login_page');
	}
}