<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenFacadey;
use Meetee\App\Entities\Factories\UserFactory;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\App\Emails\EmailFacade;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\Validators\Compound\Users\UserEmailValidator;
use Meetee\Libs\Security\Validators\Compound\Forms\ResetPasswordFormValidator;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Security\Hash;

class DeleteAccountController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_delete_account_token';
	private array $errors = [];

	public function page(): void
	{
		$token = TokenFactory::generate(self::$tokenName);

		$this->render('auth/delete_account', [
			'token' => $token,
			'errors' => $this->errors,
		]);
	}

	public function process(): void
	{
		try {
			$this->trimValues();
			$this->returnToPageIfTokenInvalid(self::$tokenName);

			$this->successfulRequestValidationEvent();
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
		if (!TokenFacade::popIfRequestValidByNameAndUser(
			$name, AuthFacade::getUser())) {

			$this->page();
			die;
		}
	}

	private function successfulRequestValidationEvent(): void
	{
		$this->deleteUser();
		$this->redirect('home');
	}

	private function deleteUser(): void
	{
		$user = AuthFacade::getUser();
		$user->deleted = true;
		$table = new UserTable();
		$table->save($user);
	}

	private function redirect(string $route): void
	{
		$router = RouterFactory::createComplete();
		$router->redirectTo($route);
	}
}