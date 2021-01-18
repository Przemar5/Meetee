<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Security\Validators\Compound\Users\UserEmailValidator;
use Meetee\App\Emails\EmailFacade;
use Meetee\Libs\View\Utils\Notification;

class ForgotPasswordController extends ControllerTemplate
{
	private static string $tokenName = 'forgot_password_token';
	private static string $emailTokenName = 'forgot_password_email_token';
	private ?User $user = null;
	private array $errors = [];

	public function page(): void
	{
		$token = TokenFactory::generate(self::$tokenName);

		$this->render('auth/forgot_password', [
			'token' => $token,
			'errors' => $this->errors,
		]);
	}

	public function process(): void
	{
		try {
			$this->returnToPageIfTokenInvalid(self::$tokenName);
			$this->returnToPageWithErrorsIfEmailInvalid();

			$this->successfulRequestValidationEvent();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function returnToPageIfTokenInvalid(
		string $name, 
		?User $user = null
	): void
	{
		if (!TokenFacade::popTokenIfValidByNameAndUser($name, $user)) {
			$this->page();
			die;
		}
	}

	private function returnToPageWithErrorsIfEmailInvalid(): void
	{
		if (!is_string($_POST['email'])) {
			$this->page();
			die;
		}

		$email = $_POST['email'] = trim($_POST['email']);
		$validator = new UserEmailValidator();
		
		if (!$validator->run($email))
			$this->returnPageWithError();

		$table = new UserTable();
		$this->user = $table->findOneBy(['email' => $email]);

		if (!$this->user)
			$this->returnPageWithError();
	}

	private function returnPageWithError(): void
	{
		$this->errors = ['general' => 'Given email does not exist in database.'];
		$this->page();
		die;
	}

	private function successfulRequestValidationEvent(): void
	{
		EmailFacade::sendForgotPasswordEmail(self::$emailTokenName, $this->user);
		Notification::addSuccess(
			'We send You an email for reseting Your password. ' . 
			'It will be valid for 2 hours.');
		$this->page();
	}

	public function verify(): void
	{
		try {
			$token = TokenFacade::popTokenIfValidByNameAndUser(
				self::$emailTokenName);

			if (!$token)
				$this->redirect('forgot_password_page');

			$table = new UserTable();

			if (!$this->user = $table->find($token->userId))
				$this->redirect('forgot_password_page');

			$this->successfulVerificationRequestValidationEvent();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function redirectToBa

	private function successfulVerificationRequestValidationEvent(): void
	{
		$this->user->verify();
		AuthFacade::login($this->user);
		$this->redirect('reset_password_page');
	}

	private function redirect(string $route): void
	{
		$router = RouterFactory::createComplete();
		$router->redirectTo($route);
	}
}