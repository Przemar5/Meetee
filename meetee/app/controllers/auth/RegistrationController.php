<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Forms\RegistrationForm;
use Meetee\App\Entities\User;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\Security\Hash;
use Meetee\App\Emails\EmailFacade;

class RegistrationController extends ControllerTemplate
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
			// $this->trimValues();
			// $this->returnToPageIfTokenInvalid('csrf_registration_token');
			// $this->returnToPageWithErrorsIfFormDataInvalid();
			// $user = $this->registerAndGetUser();
			$user = $this->registerAndGetMockUser();
			EmailFacade::sendRegistrationConfirmEmail($user);

			$this->redirect('login');
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
		if (!TokenFacade::validate($name)) {
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

	private function registerAndGetUser(): User
	{
		$user = new User();
		$user->setLogin($_POST['login']);
		$user->setEmail($_POST['email']);
		$user->setName($_POST['name']);
		$user->setSurname($_POST['surname']);
		$user->setBirth($_POST['birth']);
		$user->setPassword(Hash::create($_POST['password']));
		$user->save();

		return $user;
	}

	private function registerAndGetMockUser(): User
	{
		$user = new User();
		$user->setLogin('test');
		$user->setEmail('1234567890localhost@gmail.com');
		$user->setName('test');
		$user->setSurname('test');
		$user->setBirth(new \DateTime());
		$user->setPassword(Hash::create('test'));
		$user->save();

		return $user;
	}

	public function verify(): void
	{
		try {
			$token = TokenFactory::popIfRequestValid(
				'registration_confirm_email_token', true);

			if (!$token)
				$this->redirect('registration');

			$user = User::find($token->getUserId());

			if (!$user)
				$this->redirect('registration');

			$this->verifyUser($user);
			$this->redirect('login');
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function verifyUser(User $user): void
	{
		$user->setVerified(true);
		$user->save();
	}
}