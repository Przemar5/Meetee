<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Forms\LoginForm;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\Security\Hash;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Http\Routing\RoutingFacade;

class LoginController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_login_token';
	private ?User $user = null;
	private array $errors = [];

	public function page(): void
	{
		$token = TokenFactory::generate(self::$tokenName);

		$this->render('auth/login', [
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
			
			$this->successfulRequestValidationEvent();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
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
		$form = new LoginForm();

		if (!$form->validate())
			$this->returnPageWithError('Invalid credentials. Please try again.');

		$table = new UserTable();
		$this->user = $table->findOneBy([
			'email' => $_POST['email'],
		]);
		
		if (!$this->user)
			$this->returnPageWithError('Invalid credentials. Please try again.');

		if (!Hash::verify($_POST['password'], $this->user->password))
			$this->returnPageWithError('Invalid credentials. Please try again.');

		if (!$this->user->verified)
			$this->returnPageWithError(
				$this->getResendVerificationEmailMessage());
	}

	private function returnPageWithError(string $msg): void
	{
		$this->errors = ['general' => $msg];
		$this->page();
		die;
	}

	private function getResendVerificationEmailMessage(): string
	{
		return sprintf('Check Your email for registration confirmation email. ' . 
				'If it\'s missing, we may <a href="%s">resend</a> it again!', 
				RoutingFacade::getLinkTo(
					'registration_resend_page')
			);
	}

	private function successfulRequestValidationEvent(): void
	{
		AuthFacade::login($this->user);
		$router = RouterFactory::createComplete();
		$router->redirectTo('main_page');
	}

	private function trimValues(): void
	{
		foreach ($_POST as $key => $value)
			if (is_string($value))
				$_POST[$key] = trim($value);
	}

	public function logout(): void
	{
		AuthFacade::logout();
		$router = RouterFactory::createComplete();
		$router->redirectTo('home');
	}
}