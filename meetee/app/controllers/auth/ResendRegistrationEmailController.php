<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\Factories\UserFactory;
use Meetee\App\Forms\RegistrationForm;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\App\Emails\EmailFacade;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\Validators\Compound\Users\UserEmailValidator;

class ResendRegistrationEmailController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_registration_resend_token';
	private ?User $user = null;
	private array $errors = [];

	public function page(): void
	{
		$token = TokenFactory::generate(self::$tokenName);

		$this->render('auth/register_resend', [
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
			$this->returnToPageWithErrorsIfEmailNotExist();

			$this->successfulRegistrationResendRequestValidationEvent();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function trimValues(): void
	{
		foreach ($_POST as $key => $value)
			if (is_string($value))
				$_POST[$key] = trim($value);
	}

	private function returnToPageIfTokenInvalid(string $name): void
	{
		if (!TokenFacade::popTokenIfValidByName($name)) {
			$this->page();
			die;
		}
	}

	private function returnToPageWithErrorsIfFormDataInvalid(): void
	{
		$validator = new UserEmailValidator();

		if (!$validator->run($_POST['email'])) {
			$this->errors = ['general' => "Email doesn't exist in our database."];
			$this->page();
			die;
		}
	}

	private function returnToPageWithErrorsIfEmailNotExist(): void
	{
		$this->user = UserFactory::getByEmail($_POST['email']);

		if (!$this->user) {
			$this->errors = ['general' => "Email doesn't exist in our database."];
			$this->page();
			die;
		}
	}

	private function successfulRegistrationResendRequestValidationEvent(): void
	{
		EmailFacade::sendRegistrationConfirmEmail(
			'registration_confirm_email_token', $this->user);
		Notification::addSuccess('Check Your mailbox for an activation email!');
		$this->redirect('login_page');
	}
}