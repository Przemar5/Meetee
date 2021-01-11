<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenHandler;
use Meetee\App\Forms\LoginForm;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\Security\Hash;
use Meetee\Libs\Security\AuthFacade;

class LoginController extends ControllerTemplate
{
	private ?User $user = null;

	public function page(?array $errors = []): void
	{
		if (AuthFacade::getLoggedUser()) {
			$router = RouterFactory::createComplete();
			$router->redirectTo('home');
		}
		
		$token = TokenFactory::generate('csrf_login_token');

		$this->render('auth/login', [
			'token' => $token,
			'errors' => $errors,
		]);
	}

	public function process(): void
	{
		try {
			$this->trimValues();
			$this->returnToPageIfTokenInvalid();
			$this->returnToPageWithErrorsIfFormDataInvalid();
			$this->loginUser();

			$router = RouterFactory::createComplete();
			$router->redirectTo('login');
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function returnToPageIfTokenInvalid(): void
	{
		if (!TokenHandler::validate('csrf_login_token')) {
			$this->page();
			die;
		}
	}

	private function returnToPageWithErrorsIfFormDataInvalid(): void
	{
		$form = new LoginForm();

		if (!$form->validate())
			$this->returnPageWithError();

		$userTable = new UserTable();
		
		if (!$this->user = $userTable->findOneWhere(['email' => $_POST['email']]))
			$this->returnPageWithError();

		if (!Hash::verify($_POST['password'], $this->user->getPassword()))
			$this->returnPageWithError();
	}

	private function returnPageWithError(): void
	{
		$this->page(['general' => 'Invalid credentials. Please try again.']);
		die;
	}

	private function trimValues(): void
	{
		foreach ($_POST as $key => $value)
			if (is_string($value))
				$_POST[$key] = trim($value);
	}

	public function loginUser(): void
	{
		AuthFacade::login($this->user);
	}
}