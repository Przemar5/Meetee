<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Factories\UserFactory;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Forms\RegistrationForm;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\App\Emails\EmailFacade;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\Validators\Compound\Users\UserEmailValidator;

class RegistrationController extends ControllerTemplate
{
	public function page(?array $errors = []): void
	{
		$token = TokenFactory::generate('csrf_registration_token');
		// $tokenTable = new TokenTable();
		// $tokenTable->popOneWhere();

		$this->render('auth/register', [
			'token' => $token,
			'errors' => $errors,
		]);
	}

	public function process(): void
	{
		try {
			$this->trimValues();
			$this->returnToPageIfTokenInvalid('csrf_registration_token');
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
			$this->page($form->getErrors());
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
		EmailFacade::sendRegistrationConfirmEmail($user);
		Notification::addSuccess('Check Your mailbox for an activation email!');
		$this->redirect('login_page');
	}

	public function verify(): void
	{
		try {
			$token = TokenFactory::popRegistrationConfirmEmailTokenIfRequestValid();

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

	public function resendEmailPage(?array $errors = []): void
	{
		$token = TokenFactory::generate('csrf_registration_resend_token');

		$this->render('auth/register_resend', [
			'token' => $token,
			'errors' => $errors,
		]);
	}

	public function resendEmailProcess(): void
	{
		try {
			$this->trimValues();
			$this->returnToResendEmailPageIfTokenInvalid('csrf_registration_resend_token');
			$this->returnToResendEmailPageWithErrorsIfFormDataInvalid();
			$this->returnToResendEmailPageWithErrorsIfEmailNotExist();

			$this->successfulRegistrationResendRequestValidationEvent();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function returnToResendEmailPageIfTokenInvalid(string $name): void
	{
		if (!TokenFactory::popIfRequestValid($name)) {
			$this->resendEmailPage();
			die;
		}
	}

	private function returnToResendEmailPageWithErrorsIfFormDataInvalid(): void
	{
		$validator = new UserEmailValidator();

		if (!$validator->run($_POST['email'])) {
			$this->resendEmailPage([
				'general' => "Email doesn't exist in our database."]);
			die;
		}
	}

	private function returnToResendEmailPageWithErrorsIfEmailNotExist(): void
	{
		$this->user = UserFactory::getByEmail($_POST['email']);

		if (!$this->user) {
			$this->resendEmailPage([
				'general' => "Email doesn't exist in our database."]);
			die;
		}
	}

	private function successfulRegistrationResendRequestValidationEvent(): void
	{
		EmailFacade::sendRegistrationConfirmEmail($this->user);
		Notification::addSuccess('Check Your mailbox for an activation email!');
		$this->redirect('login_page');
	}
}