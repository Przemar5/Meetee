<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenHandler;
use Meetee\App\Forms\RegistrationForm;
use Meetee\App\Entities\User;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\Security\Hash;

class RegisterController extends ControllerTemplate
{
	public function page(?array $errors = []): void
	{
		$token = TokenFactory::generate('csrf_registration_token');

		$this->render('auth/register', [
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
			$this->registerUser();

			$router = RouterFactory::createComplete();
			$router->redirectTo('login');
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function returnToPageIfTokenInvalid(): void
	{
		if (!TokenHandler::validate('csrf_registration_token')) {
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

	private function registerUser(): void
	{
		$user = new User();
		$user->setLogin($_POST['login']);
		$user->setEmail($_POST['email']);
		$user->setName($_POST['name']);
		$user->setSurname($_POST['surname']);
		$user->setBirth($_POST['birth']);
		$user->setPassword(Hash::create($_POST['password']));
		$user->save();
	}
}